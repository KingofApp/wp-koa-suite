<?php
function koa_get_tracking_code() {
    $api_key = get_option('koa_analytics_api_key');
    $app_id = get_option('koa_app_id');
    
    return <<<HTML
<!-- KOA Analytics Tracking Code -->
<script>
(function(w,d,k,a){
    w.KoaTracker = w.KoaTracker || {
        apiKey: k,
        appId: a,
        queue: [],
        track: function(eventName, eventData) {
            this.queue.push({
                name: eventName,
                data: eventData || {},
                timestamp: new Date().getTime()
            });
            this.processQueue();
        },
        processQueue: function() {
            if (this.queue.length === 0) return;
            
            var event = this.queue.shift();
            var xhr = new XMLHttpRequest();
            xhr.open('POST', '/wp-json/koa/v1/track', true);
            xhr.setRequestHeader('Content-Type', 'application/json');
            xhr.send(JSON.stringify({
                api_key: this.apiKey,
                app_id: this.appId,
                event: event
            }));
        }
    };
})(window,document,'$api_key','$app_id');
</script>
HTML;
} 