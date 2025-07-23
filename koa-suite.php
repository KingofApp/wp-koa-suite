<?php
/*
 * Plugin Name:       King of App Suite
 * Plugin URI:        https://kingofapp.com/
 * Description:       Manage and control all the KOA plugins
 * Version:          1.3.0
 * Requires PHP:      7.2
 * Author:           King of App
 * Author URI:       https://kingofapp.com/
 * Text Domain:      koa-suite
 * Domain Path:      /languages
 * License:          GPL v2 or later
 * License URI:      https://www.gnu.org/licenses/gpl-2.0.html
 *
 * This plugin is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 2 of the License, or
 * any later version.
 *
 * This plugin is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this plugin. If not, see https://www.gnu.org/licenses/gpl-2.0.html.
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Cargar Bootstrap y estilos
function koa_enqueue_bootstrap() {
    // Bootstrap CSS
    wp_enqueue_style('bootstrap-css', 
        'https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css', 
        array(), 
        '5.3.0'
    );

    // jQuery (asegurarnos de que está cargado)
    wp_enqueue_script('jquery');

    // Bootstrap JS y Popper
    wp_enqueue_script('bootstrap-bundle', 
        'https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js', 
        array('jquery'), 
        '5.3.0', 
        true
    );

    // Script personalizado para el modal
    wp_enqueue_script('koa-suite-js',
        plugin_dir_url(__FILE__) . 'assets/js/koa-suite.js',
        array('jquery', 'bootstrap-bundle'),
        '1.0.0',
        true
    );

    // Estilos propios del plugin
    wp_enqueue_style('koa-suite-css', 
        plugin_dir_url(__FILE__) . 'assets/css/koa-suite.css', 
        array('bootstrap-css'), 
        '1.1.2'
    );
}
add_action('admin_enqueue_scripts', 'koa_enqueue_bootstrap');

// Inicialización condicional
if (is_admin()) {
    // Incluir el core administrativo
    include(plugin_dir_path(__FILE__) . 'main/core.php');
} else {
    include(plugin_dir_path(__FILE__) . 'main/noAdmin.php');
}

// Incluir archivos adicionales
include(WP_PLUGIN_DIR . '/koa-suite/menus/koa-wp-api-menus.php');
include(WP_PLUGIN_DIR . '/koa-suite/push/wp-koa-push-manager.php');

// En la función que maneja las pestañas de analytics
function koa_analytics_tab_content() {
    $section = isset($_GET['section']) ? sanitize_text_field($_GET['section']) : 'overview';
    
    switch ($section) {
        case 'config':
            require_once plugin_dir_path(__FILE__) . 'dashboard/analytics/config.php';
            break;
        case 'overview':
        default:
            require_once plugin_dir_path(__FILE__) . 'dashboard/analytics/tabs/overview.php';
            break;
    }
}

// En la función que maneja el menú principal
function koa_admin_menu_page() {
    $tab = isset($_GET['tab']) ? sanitize_text_field($_GET['tab']) : 'dashboard';
    ?>
    <div class="wrap">
        <h1>Koa Suite</h1>
        
        <!-- Pestañas principales -->
        <nav class="nav-tab-wrapper">
            <a href="?page=koa-suite&tab=dashboard" class="nav-tab <?php echo $tab === 'dashboard' ? 'nav-tab-active' : ''; ?>">Dashboard</a>
            <a href="?page=koa-suite&tab=analytics" class="nav-tab <?php echo $tab === 'analytics' ? 'nav-tab-active' : ''; ?>">Analytics</a>
            <!-- Otras pestañas... -->
        </nav>

        <?php
        switch ($tab) {
            case 'analytics':
                koa_analytics_tab_content();
                break;
            case 'dashboard':
            default:
                require_once plugin_dir_path(__FILE__) . 'dashboard/dashboard.php';
                break;
        }
        ?>
    </div>
    <?php
}

function enqueue_koa_suite_assets() {
    // Agregar Font Awesome
    wp_enqueue_style(
        'font-awesome',
        'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css',
        array(),
        '6.5.1'
    );

    // Otros assets que ya tengas...
}
add_action('admin_enqueue_scripts', 'enqueue_koa_suite_assets');