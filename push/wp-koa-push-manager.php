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

register_activation_hook( __FILE__, 'koa_create_push_notifications_table' );