<?php
if (!defined('ABSPATH')) exit;

// Obtener datos de analíticas móviles
$androidData = $data['android'] ?? [];
$iosData = $data['ios'] ?? [];
?>

<div class="row g-4">
    <!-- Selector de Plataforma -->
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
        </ul>
    </div>

    <!-- Contenido de las pestañas -->
    <div class="col-12">
        <div class="tab-content">
            <!-- Vista General -->
            <div class="tab-pane fade show active" id="overview">
                <?php include(plugin_dir_path(__FILE__) . 'tabs/overview.php'); ?>
            </div>

            <!-- Android -->
            <div class="tab-pane fade" id="android">
                <?php include(plugin_dir_path(__FILE__) . 'tabs/android.php'); ?>
            </div>

            <!-- iOS -->
            <div class="tab-pane fade" id="ios">
                <?php include(plugin_dir_path(__FILE__) . 'tabs/ios.php'); ?>
            </div>
        </div>
    </div>
</div>

<style>
.nav-pills .nav-link {
    color: #6c757d;
    padding: 0.5rem 1rem;
    border-radius: 50rem;
}

.nav-pills .nav-link.active {
    background-color: #0d6efd;
    color: white;
}

.nav-pills .nav-link:not(.active):hover {
    background-color: #f8f9fa;
}
</style> 