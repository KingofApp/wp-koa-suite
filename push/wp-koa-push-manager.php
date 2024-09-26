<?php
// Require Composer's autoload file for Google API Client Library
require_once WP_PLUGIN_DIR.'/koa-suite/push/includes/vendor/autoload.php';

// Define where to store the uploaded JSON credentials
define('FIREBASE_JSON_CREDENTIALS_PATH', WP_PLUGIN_DIR.'/koa-suite/push/includes/google-credentials.json');


add_action( 'admin_init', 'push_setings');

/* Setting the form fields */
function push_setings() {
    // Register our setting with a default value
    register_setting( 'koa-push-settings-group', 'firebase_project_ID', array(
        'type'              => 'string',
        'show_in_rest'      => false,
        'default'           => 'firebase project id',
    ));

    register_setting( 'koa-push-settings-group', 'koa_push_search', array(
        'type'              => 'string',
        'show_in_rest'      => false,
        'default'           => ""
    ));
}


//create a table on the database to store all the push 
function koa_create_push_notifications_table() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'koa_push_notifications';
    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE $table_name (
        id bigint(20) NOT NULL AUTO_INCREMENT,
        user_id bigint(20) NOT NULL,
        notification_title varchar(255) NOT NULL,
        notification_body text NOT NULL,
        notification_send_date datetime NOT NULL,
        notification_status varchar(20) NOT NULL,
        PRIMARY KEY  (id)
    ) $charset_collate;";

    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
    dbDelta( $sql );
}

register_activation_hook( WP_PLUGIN_DIR . '/koa-suite/koa-suite.php', 'koa_create_push_notifications_table' );


// Hook into wp_mail to trigger the REST API call when an email is sent
add_filter('wp_mail', 'trigger_firebase_notification_with_recipient_user_id');

function trigger_firebase_notification_with_recipient_user_id($args) {
    // Extract recipient email(s) - can be an array or a string
    $recipient_email = $args['to'];

    // If it's an array, handle multiple recipients
    if (is_array($recipient_email)) {
        foreach ($recipient_email as $email) {
            send_notification_for_email($email, $args);
        }
    } else {
        // Handle a single recipient
        send_notification_for_email($recipient_email, $args);
    }

    // Return the original email arguments to continue sending the email
    return $args;
}

function send_notification_for_email($email, $email_args) {
    // Attempt to get the user by their email address
    $user = get_user_by('email', $email);

    if ($user) {
        // If the user exists, get their ID
        $user_id = $user->ID;
    } else {
        // If the email doesn't belong to a registered user, use a default or "guest"
        $user_id = 'guest';
    }

    // Prepare notification content
    $title = 'New Email Notification';
    $body = 'An email has been sent with subject: ' . $email_args['subject'];

    // Prepare the data to be sent to the Firebase API
    $api_url = home_url('/wp-json/firebase/v1/send-notification/');

    $request_data = array(
        'user_id' => $user_id,  // Use the found user ID or "guest"
        'title'   => $title,
        'body'    => $body,
    );

    // Use wp_remote_post to call the custom REST API endpoint
    $response = wp_remote_post($api_url, array(
        'method'    => 'POST',
        'body'      => wp_json_encode($request_data),
        'headers'   => array(
            'Content-Type' => 'application/json',
        ),
    ));

    // Optionally, check for errors
    if (is_wp_error($response)) {
        error_log('Error sending notification: ' . $response->get_error_message());
    } else {
        error_log('Notification sent successfully for user: ' . $user_id);
    }
}