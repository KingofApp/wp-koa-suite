<?php
if (!defined('ABSPATH')) exit;

// Agregar en la parte superior del archivo
wp_enqueue_script('chartjs', 'https://cdn.jsdelivr.net/npm/chart.js', [], '3.7.0', true);

// Obtener datos de analíticas
$data = [
    'android' => [],
    'ios' => [],
    'events' => []
];
?>

<div class="analytics-container">
    <!-- Selector de Vista -->
    <div class="col-12 mb-4">
        <ul class="nav nav-pills" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" data-bs-toggle="pill" data-bs-target="#overview" type="button">
                    <i class="fas fa-chart-line me-2"></i>Vista General
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" data-bs-toggle="pill" data-bs-target="#android" type="button">
                    <i class="fab fa-android me-2"></i>Android
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" data-bs-toggle="pill" data-bs-target="#ios" type="button">
                    <i class="fab fa-apple me-2"></i>iOS
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" data-bs-toggle="pill" data-bs-target="#events" type="button">
                    <i class="fas fa-flag me-2"></i>Eventos
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" data-bs-toggle="pill" data-bs-target="#retention" type="button">
                    <i class="fas fa-user-clock me-2"></i>Retención
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" data-bs-toggle="pill" data-bs-target="#config" type="button">
                    <i class="fas fa-cog me-2"></i>Configuración
                </button>
            </li>
        </ul>
    </div>

    <!-- Contenido de las pestañas -->
    <div class="tab-content">
        <div class="tab-pane fade show active" id="overview">
            <?php include(plugin_dir_path(__FILE__) . 'tabs/overview.php'); ?>
        </div>
        <div class="tab-pane fade" id="android">
            <?php include(plugin_dir_path(__FILE__) . 'tabs/android.php'); ?>
        </div>
        <div class="tab-pane fade" id="ios">
            <?php include(plugin_dir_path(__FILE__) . 'tabs/ios.php'); ?>
        </div>
        <div class="tab-pane fade" id="events">
            <?php include(plugin_dir_path(__FILE__) . 'tabs/events.php'); ?>
        </div>
        <div class="tab-pane fade" id="retention">
            <?php include(plugin_dir_path(__FILE__) . 'tabs/retention.php'); ?>
        </div>
        <div class="tab-pane fade" id="config">
            <?php include(plugin_dir_path(__FILE__) . 'tabs/config.php'); ?>
        </div>
    </div>
</div> 