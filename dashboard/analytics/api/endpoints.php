<?php
add_action('rest_api_init', function() {
    register_rest_route('koa/v1', '/track', [
        'methods' => 'POST',
        'callback' => 'koa_track_event_endpoint',
        'permission_callback' => '__return_true'
    ]);
});

function koa_track_event_endpoint($request) {
    $params = $request->get_params();
    
    // Validar API key
    if ($params['api_key'] !== get_option('koa_analytics_api_key')) {
        return new WP_Error('invalid_api_key', 'API key inválida', ['status' => 403]);
    }
    
    // Trackear evento
    $tracker = KoaEventTracker::getInstance();
    $result = $tracker->trackEvent($params['event']['name'], $params['event']['data']);
    
    if ($result) {
        return ['status' => 'success'];
    }
    
    return new WP_Error('tracking_failed', 'Error al trackear evento', ['status' => 500]);
} 