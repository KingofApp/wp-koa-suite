<?php

    add_action( 'rest_api_init', function () {
        register_rest_route( 'koapush/v1', 'push_code', array(
        'methods' => 'GET',
        'callback' => 'get_push_code',
        ) );
    } );
    
    function get_push_code(WP_REST_Request $request){
        $user = apply_filters('determine_current_user', false);
        if( !apply_filters('determine_current_user', false) ) return new WP_REST_Response( json_encode( array("error" => true, "code" =>"user_not_loged_in")), 400);
            
        $actionStatus = save_user_code($user, $request->get_param('push_code') );
        
        if( !$actionStatus && get_user_meta( $user, 'koa_push_code', true ) != "" ) return new WP_REST_Response( json_encode( array("error" => false, "success" => "code_alredy_set")), 200);
        
        return new WP_REST_Response( json_encode( array("error" => false, "success" => "code_set")), 200);
    }

    //save user push code
    function save_user_code( $userID, $push_code ){
        return update_user_meta( $userID, 'koa_push_code', $push_code );
    }