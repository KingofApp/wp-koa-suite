<?php
/**
 * Plugin Name: King of app custom push
 * Plugin URI: https://kingofapp.com/
 * Description: Create a new endpoint to recive a device token and user info.
 * Version: 3.0
 * Author: King of app
 * Author URI: https://kingofapp.com/
 */



/* init */
if ( is_admin() ) { // admin actions

	require_once plugin_dir_path(__FILE__).'configform/form.php';
	require_once plugin_dir_path(__FILE__).'configform/setings.php';
	
} else {
	require_once plugin_dir_path(__FILE__).'apiendpoint/manage_api_endpoint.php';
}