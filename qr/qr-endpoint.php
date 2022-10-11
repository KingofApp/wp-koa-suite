<?php
add_action( 'rest_api_init', 'register_qr_endpoint' );
function register_qr_endpoint() {
    register_rest_route( 
        'koa/v1',
        'qr',
        array(
            'method' => 'GET',
            'callback' => 'get_os',
        )
    );
}

function get_os() {
    $iphone = strpos($_SERVER['HTTP_USER_AGENT'],"iPhone");
    $android = strpos($_SERVER['HTTP_USER_AGENT'],"Android");
    $palmpre = strpos($_SERVER['HTTP_USER_AGENT'],"webOS");
    $berry = strpos($_SERVER['HTTP_USER_AGENT'],"BlackBerry");
    $ipod = strpos($_SERVER['HTTP_USER_AGENT'],"iPod");

    $reqAndroid = get_option('koa_qr_android');
    $reqIos = get_option('koa_qr_ios');
    if ($android == true) 
    { 
        header("Location: ".$reqAndroid);
		exit();
    } else if ($iphone == true) {
        header("Location: ".$reqIos);
		exit();
    } else {
        echo "Sistema operativo no compatible";
    }
}
