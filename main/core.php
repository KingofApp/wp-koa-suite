<?php 
//add plugin menu to wp

add_action( 'admin_menu', 'add_koa_menu' );

function add_koa_menu(){
    add_menu_page('Koa Suite', 'Koa Suite', 'manage_options', 'koa-suite-plugin', 'koa_suite_view', 'https://s3.eu-central-1.amazonaws.com/kingofapp.com/kKoa2.svg');
	echo("cosas");
    //import 
    /*include(WP_PLUGIN_DIR.'/wp-koa-suite/embed/main.php');
    include(WP_PLUGIN_DIR.'/wp-koa-suite/qr/koa-qr.php');
    include(WP_PLUGIN_DIR.'/wp-koa-suite/push/configform/setings.php');
    include(WP_PLUGIN_DIR.'/wp-koa-suite/menus/koa-wp-api-menus.php');

    require_once( plugin_dir_path(__FILE__).'/view.php' );*/
}