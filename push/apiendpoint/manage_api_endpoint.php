<?php
// Create a REST API endpoint to get the user device token
add_action( 'rest_api_init', function () {
    register_rest_route( 'koapush/v1', 'push_code', array(
    'methods' => 'GET',
    'callback' => 'get_push_code',
    ) );
} );
    
function get_push_code(WP_REST_Request $request){
	// Get user ID from request or current session
	$user = $request->get_param('app_user');
	if(empty($user)){
		$user = apply_filters('determine_current_user', false);
	}
	// Check if user is valid
    if( empty($user) || !get_user_by('ID', $user) ) {
        return new WP_REST_Response( wp_json_encode( array("error" => true, "code" =>"user_not_loged_in")), 400);
    }
    
	//Save the push code
    $actionStatus = save_user_code($user, $request->get_param('push_code') );
    
	//Response
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
        'permission_callback' => 'validate_api_request'
    ));
});

add_action('rest_api_init', function () {
    register_rest_route('firebase/v1', '/get-notifications/', array(
        'methods' => 'GET',
        'callback' => 'get_push_notifications',
        'permission_callback' => 'validate_api_request'
    ));
});

function send_firebase_notification( WP_REST_Request $request ) {
    global $wpdb;

    // Get parameters
    $user_id = sanitize_text_field( $request->get_param('user_id') );
    $title = sanitize_text_field( $request->get_param('title') );
    $body = sanitize_text_field( $request->get_param('body') );
    $device_token = $request->get_param('device_token');

    // Validate required fields
    if ( empty( $title ) || empty( $body ) ) {
        return new WP_Error( 'invalid_data', 'Title and body are required.', array( 'status' => 400 ) );
    }

    // Decide which device token to use
    if ( !empty( $device_token ) ) {
        // Use device_token from request
        $token_used = is_array($device_token) ? array_map('sanitize_text_field', $device_token) : sanitize_text_field($device_token);

        // Try to locate user by device token
        $user_query = new WP_User_Query([
            'meta_key'   => 'koa_push_code',
            'meta_value' => $token_used,
            'number'     => 1,
            'fields'     => 'ID'
        ]);
        $found_users = $user_query->get_results();
        $user_used = !empty($found_users) ? $found_users[0] : 1;
    } else {
        // Use device token from user meta
        $token_used = get_user_meta( $user_id, 'koa_push_code', true );
        $user_used = $user_id;
        if ( empty( $token_used ) ) {
            return new WP_Error( 'no_token', 'No device token found for this user.', array( 'status' => 404 ) );
        }
    }

    // Get Firebase Project ID from option, or extract from credentials file if not set
    $firebase_project_id = get_option('firebase_project_id');
    if (empty($firebase_project_id)) {
        $credentials_path = WP_PLUGIN_DIR . '/koa-suite/push/includes/google-credentials.json';
        if (!file_exists($credentials_path)) {
            return new WP_Error('no_credentials', 'Google JSON credentials file not found.', array('status' => 500));
        }
        $credentials_json = file_get_contents($credentials_path);
        $credentials = json_decode($credentials_json, true);
        if (empty($credentials['project_id'])) {
            return new WP_Error('no_project_id', 'Firebase project ID not found in credentials file.', array('status' => 500));
        }
        $firebase_project_id = $credentials['project_id'];
        update_option('firebase_project_id', $firebase_project_id);
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
            'token' => $token_used,
            'android' => array(
                'priority' => 'high',
                'notification' => array(
                    "title" => $title,
                    "body" => $body,
                    "color" => '#44ff00',
                    "sound" => 'default',
                    "default_sound" => true,
                    "default_vibrate_timings" => true,
                    "default_light_settings" => true,
                    "light_settings" => array(
                        "color" => array(
                            "red" => 1,
                            "green" => 1,
                            "blue" => 1,
                            "alpha" => 1.0
                        )
                    ),
                ),
            ),
            'apns' => array(
                'headers' => array(
                    'apns-priority' => '10'
                )
            ),
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
        'user_id'               => $user_used,
        'device_token'          => $token_used,
        'notification_title'    => $title,
        'notification_body'     => $body,
        'notification_send_date' => current_time( 'mysql' ),
        'notification_status'   => $status,
    ));

    if ( $status === 'failed' ) {
        $error = new WP_Error('send_error', 'Failed to send notification: ' . $error_message, array('status' => 500));
        error_log('Firebase Notification Error: ' . $error->get_error_message());
        return $error;
    }

    return rest_ensure_response( array( 'success' => true, 'message' => 'Notification sent successfully.' ) );
}

function get_push_notifications(WP_REST_Request $request) {
    global $wpdb;

    $device_token = $request->get_param('device_token');
    $table_name = $wpdb->prefix . 'koa_push_notifications';
    $page = $request->get_param('page');
    $per_page = 20;

    if (!empty($device_token)) {
        $device_token = sanitize_text_field($device_token);
        $where = $wpdb->prepare("WHERE device_token = %s", $device_token);
    } else {
        $user = apply_filters('determine_current_user', false);
        if (!$user) {
            return new WP_Error('not_logged_in', 'User must be logged in.', array('status' => 403));
        }
        $where = $wpdb->prepare("WHERE user_id = %d", $user);
    }

    if (empty($page)) {
        $sql = "SELECT * FROM $table_name $where ORDER BY notification_send_date DESC";
        $results = $wpdb->get_results($sql, ARRAY_A);
        $total_items = count($results);
        $total_pages = 1;
        $current_page = 1;
    } else {
        $total_items = $wpdb->get_var("SELECT COUNT(*) FROM $table_name $where");
        $total_pages = ceil($total_items / $per_page);
        $current_page = max(1, intval($page));
        $offset = ($current_page - 1) * $per_page;
        $query = "$where ORDER BY notification_send_date DESC LIMIT %d OFFSET %d";
        $sql = $wpdb->prepare("SELECT * FROM $table_name $query", $per_page, $offset);
        $results = $wpdb->get_results($sql, ARRAY_A);
    }

    return rest_ensure_response(array(
        'current_page'   => $current_page,
        'total_pages'    => $total_pages,
        'notifications'  => $results,
    ));
}

function validate_api_request($request) {
    // Allow if request comes from same domain
    $origin = isset($_SERVER['HTTP_ORIGIN']) ? $_SERVER['HTTP_ORIGIN'] : '';
    $referer = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '';
    $site_url = get_site_url();

    if (
        (strpos($origin, $site_url) === 0) || 
        (strpos($referer, $site_url) === 0)
    ) {
        return true;
    }

    // Fallback: require logged-in user with proper capability
    if (!is_user_logged_in()) {
        return new WP_Error('rest_forbidden', __('You do not have permission to access this API.'), array('status' => 403));
    }

    if (!current_user_can('manage_options')) {
        return new WP_Error('rest_forbidden', __('You do not have permission.'), array('status' => 403));
    }

    return true;
}