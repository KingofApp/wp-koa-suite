<?php

add_action('wp_mail', 'firebase_push_notification_send_email', 10, 4);

// Envía una notificación push a través de Firebase cuando se envía un correo electrónico
function firebase_push_notification_send_email($to, $subject, $message, $headers) {
    $data = array(
        'to' => '/topics/all',
        'notification' => array(
          'title' => 'Test Notification',
          'body' => 'This is a test notification from FCM',
        ),
      );
      
      $options = array(
        'headers' => array(
          'Authorization' => 'Bearer YOUR_FCM_SERVER_KEY',
          'Content-Type' => 'application/json',
        ),
        'body' => json_encode( $data ),
      );
      
      $response = wp_remote_post( 'https://fcm.googleapis.com/fcm/send', $options );
      
      if ( is_wp_error( $response ) ) {
        // There was an error making the request
        return;
      }
      
      $body = wp_remote_retrieve_body( $response );

      
        // Parse the response body as JSON
        $data = json_decode( $body, true );

        if ( ! $data ) {
            // There was an error parsing the JSON
            return;
        }
        echo $data;
        // Process the data
        foreach ( $data as $item ) {
            // Do something with each item
            echo $item;
        }
}

?>