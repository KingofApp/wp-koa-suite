<?php
// Create a REST API endpoint to get the user device token
add_action( 'rest_api_init', function () {
    register_rest_route( 'koapush/v1', 'push_code', array(
    'methods' => 'GET',
    'callback' => 'get_push_code',
    ) );
} );
    
function get_push_code(WP_REST_Request $request){
    $user = apply_filters('determine_current_user', false);
    if( !apply_filters('determine_current_user', false) ) {
        return new WP_REST_Response( wp_json_encode( array("error" => true, "code" =>"user_not_loged_in")), 400);
    }
        
    $actionStatus = save_user_code($user, $request->get_param('push_code') );
    
    if( !$actionStatus && get_user_meta( $user, 'koa_push_code', true ) != "" ) {
        return new WP_REST_Response( wp_json_encode( array("error" => false, "success" => "code_alredy_set")), 200);
    }
    
    return new WP_REST_Response( wp_json_encode( array("error" => false, "success" => "code_set")), 200);
}

//save user push code
function save_user_code( $userID, $push_code ){
    return update_user_meta( $userID, 'koa_push_code', $push_code );
}

/* ----------------------------------------------------------------------------------- */

// Require Composer's autoload file for Google API Client Library
require_once WP_PLUGIN_DIR.'/koa-suite/push/includes/vendor/autoload.php';

// Register the REST API endpoint for sending push notifications
add_action('rest_api_init', function () {
    register_rest_route('firebase/v1', '/send-notification/', array(
        'methods' => 'POST',
        'callback' => 'send_firebase_notification',
        'permission_callback' => '__return_true', // Adjust for real permissions
    ));
});


function send_firebase_notification( WP_REST_Request $request ) {
    global $wpdb;
    
    // Get the parameters from the request
    $user_id = sanitize_text_field( $request->get_param('user_id') );
    $title = sanitize_text_field( $request->get_param('title') );
    $body = sanitize_text_field( $request->get_param('body') );
    //$secret = sanitize_text_field( $request->get_param('secret') );

    // Validate the input
    if ( empty( $user_id ) || empty( $title ) || empty( $body ) ) {
        return new WP_Error( 'invalid_data', 'User ID, title, and body are required.', array( 'status' => 400 ) );
    }

    // Get the user's device token from user meta
    $user_token = get_user_meta( $user_id, 'koa_push_code', true );
    if ( empty( $user_token ) ) {
        return new WP_Error( 'no_token', 'No device token found for this user.', array( 'status' => 404 ) );
    }

    // Get Firebase Project ID from settings
    $firebase_project_id = get_option( 'firebase_project_id' );
    if ( empty( $firebase_project_id ) ) {
        return new WP_Error( 'no_project_id', 'Firebase project ID is not set.', array( 'status' => 500 ) );
    }

    // Define the Firebase credentials path (adjust the path accordingly)
    if ( !defined('FIREBASE_JSON_CREDENTIALS_PATH') ) {
        define('FIREBASE_JSON_CREDENTIALS_PATH', WP_PLUGIN_DIR . '/koa-suite/push/includes/google-credentials.json');
    }

    // Check if the credentials file exists
    if ( !file_exists( FIREBASE_JSON_CREDENTIALS_PATH ) ) {
        return new WP_Error( 'no_credentials', 'Google JSON credentials file not found.', array( 'status' => 500 ) );
    }

    // Get the OAuth 2.0 token using Google_Client
    $client = new Google_Client();
    $client->setAuthConfig( FIREBASE_JSON_CREDENTIALS_PATH );
    $client->addScope('https://www.googleapis.com/auth/firebase.messaging');

    try {
        $token_data = $client->fetchAccessTokenWithAssertion();
        if ( isset( $token_data['error'] ) ) {
            return new WP_Error( 'token_error', 'Failed to get OAuth token.', array( 'status' => 500 ) );
        }
        $auth_token = $token_data['access_token'];
    } catch ( Exception $e ) {
        return new WP_Error( 'token_exception', 'Error generating token: ' . $e->getMessage(), array( 'status' => 500 ) );
    }

    // Prepare the FCM request
    $fcm_url = "https://fcm.googleapis.com/v1/projects/$firebase_project_id/messages:send";
    $notification_data = array(
        'message' => array(
            'notification' => array(
                'title' => $title,
                'body' => $body,
            ),
            'token' => $user_token,
        )
    );
    $response = wp_remote_post( $fcm_url, array(
        'headers' => array(
            'Authorization' => 'Bearer ' . $auth_token,
            'Content-Type'  => 'application/json',
        ),
        'body'    => wp_json_encode( $notification_data ),
    ));

    // Check if there was an error sending the notification
    if ( is_wp_error( $response ) ) {
        $status = 'failed';
        $error_message = $response->get_error_message();
    } else {
        $response_body = wp_remote_retrieve_body( $response );
        $response_data = json_decode( $response_body, true );
        if ( isset( $response_data['error'] ) ) {
            $status = 'failed';
            $error_message = $response_data['error']['message'];
        } else {
            $status = 'success';
            $error_message = null;
        }
    }

    // Save the result in the database
    $table_name = $wpdb->prefix . 'koa_push_notifications';
    $wpdb->insert( $table_name, array(
        'user_id'               => $user_id,
        'notification_title'    => $title,
        'notification_body'     => $body,
        'notification_send_date' => current_time( 'mysql' ),
        'notification_status'   => $status,
    ));

    if ( $status === 'failed' ) {
        return new WP_Error( 'send_error', 'Failed to send notification: ' . $error_message, array( 'status' => 500 ) );
    }

    return rest_ensure_response( array( 'success' => true, 'message' => 'Notification sent successfully.' ) );
}