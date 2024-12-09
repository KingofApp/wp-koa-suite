<?php
if (!defined('ABSPATH')) exit;

// Obtener configuraciones actuales
$config = get_option('koa_analytics_config', [
    'tracking_enabled' => true,
    'retention_days' => 30,
    'auto_cleanup' => true,
    'appstore_api' => [
        'key_id' => '',
        'issuer_id' => '',
        'private_key' => ''
    ],
    'playstore_api' => [
        'package_name' => '',
        'json_key' => ''
    ]
]);

// Guardar cambios si se envió el formulario
if (isset($_POST['save_analytics_config'])) {
    check_admin_referer('koa_analytics_config_nonce');
    
    $config['tracking_enabled'] = isset($_POST['tracking_enabled']);
    $config['retention_days'] = intval($_POST['retention_days']);
    $config['auto_cleanup'] = isset($_POST['auto_cleanup']);
    
    // App Store API configs
    $config['appstore_api']['key_id'] = sanitize_text_field($_POST['appstore_key_id']);
    $config['appstore_api']['issuer_id'] = sanitize_text_field($_POST['appstore_issuer_id']);
    if (!empty($_POST['appstore_private_key'])) {
        $config['appstore_api']['private_key'] = sanitize_textarea_field($_POST['appstore_private_key']);
    }
    
    // Play Store API configs
    $config['playstore_api']['package_name'] = sanitize_text_field($_POST['playstore_package_name']);
    if (!empty($_FILES['playstore_json_key']['tmp_name'])) {
        $json_content = file_get_contents($_FILES['playstore_json_key']['tmp_name']);
        if (json_decode($json_content) !== null) {
            $config['playstore_api']['json_key'] = $json_content;
        }
    }
    
    update_option('koa_analytics_config', $config);
    echo '<div class="notice notice-success"><p>Configuración guardada exitosamente.</p></div>';
}
?>

<div class="config-container p-4 mb-5 bg-white">
    <h2 class="mb-4">Configuración de Analíticas</h2>
    
    <form method="post" action="" enctype="multipart/form-data">
        <?php wp_nonce_field('koa_analytics_config_nonce'); ?>
        
        <div class="row g-4 mb-4">
            <!-- Configuración General -->
            <div class="col-12 col-lg-4">
                <div class="card h-100">
                    <div class="card-header bg-primary text-white">
                        <h3 class="h5 mb-0"><i class="fas fa-cog me-2"></i>Configuración General</h3>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-check-label">
                                <input type="checkbox" class="form-check-input" name="tracking_enabled" 
                                       <?php checked($config['tracking_enabled']); ?>>
                                Habilitar seguimiento de analíticas
                            </label>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Días de retención de datos</label>
                            <input type="number" class="form-control" name="retention_days" 
                                   value="<?php echo esc_attr($config['retention_days']); ?>" min="1" max="365">
                            <small class="form-text text-muted">Número de días que se mantendrán los datos</small>
                        </div>

                        <div class="mb-3">
                            <label class="form-check-label">
                                <input type="checkbox" class="form-check-input" name="auto_cleanup" 
                                       <?php checked($config['auto_cleanup']); ?>>
                                Limpieza automática de datos antiguos
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Configuración App Store -->
            <div class="col-12 col-lg-4">
                <div class="card h-100">
                    <div class="card-header bg-dark text-white">
                        <h3 class="h5 mb-0"><i class="fab fa-apple me-2"></i>App Store Connect API</h3>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label">Key ID</label>
                            <input type="text" class="form-control" name="appstore_key_id" 
                                   value="<?php echo esc_attr($config['appstore_api']['key_id']); ?>">
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Issuer ID</label>
                            <input type="text" class="form-control" name="appstore_issuer_id" 
                                   value="<?php echo esc_attr($config['appstore_api']['issuer_id']); ?>">
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Private Key</label>
                            <textarea class="form-control" name="appstore_private_key" rows="4" 
                                      placeholder="-----BEGIN PRIVATE KEY-----..."><?php echo esc_textarea($config['appstore_api']['private_key']); ?></textarea>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Configuración Play Store -->
            <div class="col-12 col-lg-4">
                <div class="card h-100">
                    <div class="card-header bg-success text-white">
                        <h3 class="h5 mb-0"><i class="fab fa-google-play me-2"></i>Google Play Console API</h3>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label">Package Name</label>
                            <input type="text" class="form-control" name="playstore_package_name" 
                                   value="<?php echo esc_attr($config['playstore_api']['package_name']); ?>"
                                   placeholder="com.tuapp.nombre">
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Service Account JSON Key</label>
                            <input type="file" class="form-control" name="playstore_json_key" accept="application/json">
                            <?php if (!empty($config['playstore_api']['json_key'])): ?>
                                <small class="form-text text-success">
                                    <i class="fas fa-check-circle"></i> Archivo JSON configurado
                                </small>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4 mb-4">
            <!-- Tutorial Configuración General -->
            <div class="col-12 col-lg-4">
                <div class="card">
                    <div class="card-header bg-light">
                        <h5 class="mb-0"><i class="fas fa-question-circle me-2"></i>¿Cómo configurar?</h5>
                    </div>
                    <div class="card-body">
                        <ol class="ps-3 mb-0">
                            <li>Activa el seguimiento de analíticas para comenzar a recopilar datos</li>
                            <li>Establece el período de retención según tus necesidades</li>
                            <li>Habilita la limpieza automática para mantener tu base de datos optimizada</li>
                        </ol>
                    </div>
                </div>
            </div>

            <!-- Tutorial App Store -->
            <div class="col-12 col-lg-4">
                <div class="card">
                    <div class="card-header bg-light">
                        <h5 class="mb-0"><i class="fab fa-apple me-2"></i>Cómo obtener las credenciales</h5>
                    </div>
                    <div class="card-body">
                        <ol class="ps-3 mb-0">
                            <li>Accede a <a href="https://appstoreconnect.apple.com" target="_blank">App Store Connect</a></li>
                            <li>Ve a "Usuarios y Acceso" > "Claves"</li>
                            <li>Genera una nueva clave API</li>
                            <li>Guarda el Key ID y la Private Key</li>
                            <li>El Issuer ID lo encontrarás en la configuración de tu cuenta</li>
                        </ol>
                    </div>
                </div>
            </div>

            <!-- Tutorial Play Store -->
            <div class="col-12 col-lg-4">
                <div class="card">
                    <div class="card-header bg-light">
                        <h5 class="mb-0"><i class="fab fa-google-play me-2"></i>Cómo obtener las credenciales</h5>
                    </div>
                    <div class="card-body">
                        <ol class="ps-3 mb-0">
                            <li>Ve a <a href="https://console.cloud.google.com" target="_blank">Google Cloud Console</a></li>
                            <li>Crea un nuevo proyecto o selecciona uno existente</li>
                            <li>Habilita la API de Google Play Developer</li>
                            <li>En "Credenciales", crea una cuenta de servicio</li>
                            <li>Descarga el archivo JSON de la cuenta de servicio</li>
                            <li>En Google Play Console, da acceso a la cuenta de servicio</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-4 mb-3">
            <div class="col-12">
                <button type="submit" name="save_analytics_config" class="btn btn-primary">
                    <i class="fas fa-save me-2"></i>Guardar Configuración
                </button>
            </div>
        </div>
    </form>
</div> 