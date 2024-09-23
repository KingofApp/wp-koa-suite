<?php
/**
 * @package koa_suite
 * @version 1.0.3
 * @license GPLv2 or later
 */
/**
 * Plugin Name: King of app suite
 * Plugin URI: https://kingofapp.com/
 * Description: Manage and control all the koa plugins
 * Author: King of app
 * Author URI: https://kingofapp.com/
 * License: GPLv2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 */

/**
 * King of app suite is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 2 of the License, or
 * (at your option) any later version.
 *
 * King of app suite is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with King of app suite. If not, see https://www.gnu.org/licenses/gpl-2.0.html.
 */

/* init */
if ( is_admin() ) { // admin actions
	include( plugin_dir_path(__FILE__).'main/core.php' );
}else{
	include( plugin_dir_path(__FILE__).'main/noAdmin.php' );
}
include(WP_PLUGIN_DIR.'/koa-suite/menus/koa-wp-api-menus.php');