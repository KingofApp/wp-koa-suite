<?php
/**
 * @package wp_koa_suite
 * @version 1.0.0
 */
/**
 * Plugin Name: King of app suite
 * Plugin URI: https://kingofapp.com/
 * Description: Manage and control all the koa plugins
 * Author: King of app
 * Author URI: https://kingofapp.com/
 */

/* init */
if ( is_admin() ) { // admin actions
	include( plugin_dir_path(__FILE__).'main/core.php' );
}else{
	include( plugin_dir_path(__FILE__).'main/noAdmin.php' );
}
include(WP_PLUGIN_DIR.'/wp-koa-suite/menus/koa-wp-api-menus.php');