<?php
if (!defined('ABSPATH')) exit;

// Obtener datos de analíticas
$androidData = $data['android'] ?? [];
$iosData = $data['ios'] ?? [];
$eventsData = $data['events'] ?? [];
$totalInstalls = $data['total_installs'] ?? 0;
$activeUsers = $data['active_users'] ?? 0;
$revenue = $data['revenue'] ?? 0;
?>

<!-- Resumen General -->
<div class="row g-4 mb-4">
    <div class="col-md-4">
        <div class="card h-100">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="rounded-circle bg-primary bg-opacity-10 p-3">
                        <i class="fas fa-download text-primary fa-2x"></i>
                    </div>
                    <div class="ms-3">
                        <h6 class="text-muted mb-1">Instalaciones Totales</h6>
                        <h3 class="mb-0"><?php echo number_format($totalInstalls); ?></h3>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card h-100">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="rounded-circle bg-success bg-opacity-10 p-3">
                        <i class="fas fa-users text-success fa-2x"></i>
                    </div>
                    <div class="ms-3">
                        <h6 class="text-muted mb-1">Usuarios Activos</h6>
                        <h3 class="mb-0"><?php echo number_format($activeUsers); ?></h3>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card h-100">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="rounded-circle bg-info bg-opacity-10 p-3">
                        <i class="fas fa-dollar-sign text-info fa-2x"></i>
                    </div>
                    <div class="ms-3">
                        <h6 class="text-muted mb-1">Ingresos Totales</h6>
                        <h3 class="mb-0">$<?php echo number_format($revenue, 2); ?></h3>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Pestañas de Analíticas -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
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
                </ul>
            </div>
            <div class="card-body">
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
                </div>
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