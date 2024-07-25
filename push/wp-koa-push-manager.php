<?php

/* init */
if ( is_admin() ) { // admin actions

	require_once plugin_dir_path(__FILE__).'configform/form.php';
	require_once plugin_dir_path(__FILE__).'configform/setings.php';
	
} else {
	require_once plugin_dir_path(__FILE__).'apiendpoint/manage_api_endpoint.php';
	require_once plugin_dir_path(__FILE__).'automatic_push.php';
}