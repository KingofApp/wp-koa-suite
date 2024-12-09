<?php
// Verificar permisos de administrador
if (!current_user_can('manage_options')) {
    wp_die(__('No tienes permisos suficientes para acceder a esta página.'));
}

// Guardar configuraciones
if (isset($_POST['save_analytics_config'])) {
    // Google Play
    if (isset($_POST['google_play_credentials'])) {
        update_option('koa_google_play_credentials', sanitize_textarea_field($_POST['google_play_credentials']));
    }
    if (isset($_POST['google_play_package_name'])) {
        update_option('koa_google_play_package_name', sanitize_text_field($_POST['google_play_package_name']));
    }

    // App Store
    if (isset($_POST['appstore_api_key'])) {
        update_option('koa_appstore_api_key', sanitize_text_field($_POST['appstore_api_key']));
    }
    if (isset($_POST['appstore_app_id'])) {
        update_option('koa_appstore_app_id', sanitize_text_field($_POST['appstore_app_id']));
    }

    echo '<div class="notice notice-success"><p>Configuración guardada correctamente.</p></div>';
}

// Obtener valores guardados
$google_play_credentials = get_option('koa_google_play_credentials');
$google_play_package_name = get_option('koa_google_play_package_name');
$appstore_api_key = get_option('koa_appstore_api_key');
$appstore_app_id = get_option('koa_appstore_app_id');
?>

<div class="wrap">
    <h2>Configuración de Analytics</h2>
    <a href="?page=koa-suite&tab=analytics" class="page-title-action">
        <i class="fas fa-arrow-left me-1"></i>Volver a Analytics
    </a>
    <form method="post" action="">
        <h3 id="google-play"><i class="fab fa-google-play me-2"></i>Google Play Console</h3>
        <table class="form-table">
            <tr>
                <th scope="row">Credenciales JSON</th>
                <td>
                    <textarea name="google_play_credentials" rows="8" class="large-text code"><?php echo esc_textarea($google_play_credentials); ?></textarea>
                    <p class="description">Pega aquí el contenido del archivo JSON de credenciales de la Google Play Console.</p>
                </td>
            </tr>
            <tr>
                <th scope="row">Package Name</th>
                <td>
                    <input type="text" name="google_play_package_name" value="<?php echo esc_attr($google_play_package_name); ?>" class="regular-text">
                    <p class="description">El identificador de tu aplicación (ej: com.tuempresa.app)</p>
                </td>
            </tr>
        </table>

        <h3 id="app-store" class="mt-4"><i class="fab fa-app-store-ios me-2"></i>App Store Connect</h3>
        <table class="form-table">
            <tr>
                <th scope="row">API Key</th>
                <td>
                    <input type="text" name="appstore_api_key" value="<?php echo esc_attr($appstore_api_key); ?>" class="regular-text">
                    <p class="description">Tu clave API de App Store Connect</p>
                </td>
            </tr>
            <tr>
                <th scope="row">App ID</th>
                <td>
                    <input type="text" name="appstore_app_id" value="<?php echo esc_attr($appstore_app_id); ?>" class="regular-text">
                    <p class="description">El ID de tu aplicación en la App Store</p>
                </td>
            </tr>
        </table>

        <p class="submit">
            <input type="submit" name="save_analytics_config" class="button button-primary" value="Guardar Cambios">
        </p>
    </form>
</div> 