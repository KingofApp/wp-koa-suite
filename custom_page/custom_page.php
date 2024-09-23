<?php

// Hook to initialize custom routes
add_action('init', 'kingofapp_add_custom_route');

function kingofapp_add_custom_route() {
    add_rewrite_rule('^kingofapp/?$', 'index.php?kingofapp_page=1', 'top');
}

// Hook to add custom query variable
add_filter('query_vars', 'kingofapp_add_query_vars');

function kingofapp_add_query_vars($vars) {
    $vars[] = 'kingofapp_page';
    return $vars;
}

// Hook to load content for the custom route
add_action('template_redirect', 'kingofapp_load_custom_template');

function kingofapp_load_custom_template() {
    if (get_query_var('kingofapp_page')) {
        // Custom content to display on the /kingofapp route
        echo '<h1>Welcome to the King of App Page!</h1>';
        echo '<p>This is a custom page created through a WordPress plugin.</p>';
        exit; // Exit to prevent loading the default template
    }
}

// Hook to flush rewrite rules when the plugin is activated
register_activation_hook(__FILE__, 'kingofapp_rewrite_flush');

function kingofapp_rewrite_flush() {
    kingofapp_add_custom_route();
    flush_rewrite_rules(); // Flush rewrite rules to ensure the new route works immediately
}

// Hook to flush rewrite rules when the plugin is deactivated
register_deactivation_hook(__FILE__, 'kingofapp_rewrite_flush_on_deactivation');

function kingofapp_rewrite_flush_on_deactivation() {
    flush_rewrite_rules(); // Flush rewrite rules on deactivation to clean up
}

