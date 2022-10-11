<?php
add_action( 'admin_init', 'push_setings');

/* Seting the form fields */
function push_setings( ){
	 $koa_push_auth_default = array(
        'type'              => 'string',
        'show_in_rest'      => false,
        'default'           => "firebase code here"
    );
	$koa_push_search_default = array(
        'type'              => 'string',
        'show_in_rest'      => false,
        'default'           => ""
    );
   
	//register our settings
	register_setting( 'koa-push-settings-group', 'koa_push_auth',  $koa_push_auth_default);
	register_setting( 'koa-push-settings-group', 'koa_push_search',  $koa_push_search_default);
}