<?php
    
        add_action( 'admin_init', 'register_koa_embed_settings' );

        /* Seting the form fields */
        function register_koa_embed_settings() {
            $koa_embed_key_default = array(
                'type'              => 'string',
                'show_in_rest'      => false,
                'default'           => "kingOfApp"
            );
            $koa_embed_style_default = array(
                'type'              => 'string',
                'show_in_rest'      => false,
                'default'           => "header, footer{ display: none !important}"
            );
            //register our settings
            register_setting( 'koa-embed-settings-group', 'koa_embed_key',  $koa_embed_key_default);
            register_setting( 'koa-embed-settings-group', 'koa_embed_style', $koa_embed_style_default );
        }