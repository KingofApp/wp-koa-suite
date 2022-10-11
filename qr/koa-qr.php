<?php

/**
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License, version 2 or, at
 * your discretion, any later version, as published by the Free
 * Software Foundation.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

add_action( 'admin_init', 'register_koa_qr_settings');


/* Seting the form fields */
function register_koa_qr_settings() {
    $koa_qr_android = array(
          'type'              => 'string',
          'show_in_rest'      => false,
          'default'           => "https://play.google.com/store/apps?gl=ES"
      );
      $koa_qr_ios = array(
          'type'              => 'string',
          'show_in_rest'      => false,
          'default'           => "https://www.apple.com/es/store"
      );
      //register our settings
      register_setting( 'koa-qr-settings-group', 'koa_qr_android',  $koa_qr_android);
      register_setting( 'koa-qr-settings-group', 'koa_qr_ios', $koa_qr_ios);
 }