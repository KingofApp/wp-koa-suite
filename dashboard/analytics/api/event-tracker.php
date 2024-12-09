<?php
class KoaEventTracker {
    private static $instance = null;
    private $api_key;
    
    private function __construct() {
        $this->api_key = get_option('koa_analytics_api_key');
    }
    
    public static function getInstance() {
        if (self::$instance == null) {
            self::$instance = new KoaEventTracker();
        }
        return self::$instance;
    }
    
    public function trackEvent($event_name, $event_data = []) {
        // Validar API key
        if (!$this->api_key) {
            return false;
        }
        
        // Datos base del evento
        $event = [
            'name' => $event_name,
            'timestamp' => time(),
            'app_id' => get_option('koa_app_id'),
            'platform' => $this->detectPlatform(),
            'device_type' => $this->detectDeviceType(),
            'session_id' => $this->getSessionId(),
            'user_id' => $this->getUserId(),
            'data' => $event_data
        ];
        
        // Guardar evento en la base de datos
        global $wpdb;
        $table_name = $wpdb->prefix . 'koa_events';
        
        return $wpdb->insert($table_name, [
            'event_name' => $event_name,
            'event_data' => json_encode($event),
            'created_at' => current_time('mysql')
        ]);
    }
    
    private function detectPlatform() {
        $user_agent = $_SERVER['HTTP_USER_AGENT'];
        if (stripos($user_agent, 'android') !== false) {
            return 'android';
        } elseif (stripos($user_agent, 'iphone') !== false || stripos($user_agent, 'ipad') !== false) {
            return 'ios';
        }
        return 'web';
    }
    
    private function detectDeviceType() {
        $user_agent = $_SERVER['HTTP_USER_AGENT'];
        if (stripos($user_agent, 'tablet') !== false || stripos($user_agent, 'ipad') !== false) {
            return 'tablet';
        } elseif (stripos($user_agent, 'mobile') !== false || stripos($user_agent, 'iphone') !== false) {
            return 'phone';
        }
        return 'desktop';
    }
    
    private function getSessionId() {
        if (!isset($_COOKIE['koa_session_id'])) {
            $session_id = uniqid('koa_', true);
            setcookie('koa_session_id', $session_id, time() + 86400, '/');
            return $session_id;
        }
        return $_COOKIE['koa_session_id'];
    }
    
    private function getUserId() {
        if (!isset($_COOKIE['koa_user_id'])) {
            $user_id = uniqid('user_', true);
            setcookie('koa_user_id', $user_id, time() + (86400 * 365), '/');
            return $user_id;
        }
        return $_COOKIE['koa_user_id'];
    }
} 