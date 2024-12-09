<?php
if (!defined('ABSPATH')) exit;

// Obtener datos de resumen
$analytics_data = [
    'total_users' => get_option('koa_analytics_total_users', 0),
    'active_users' => get_option('koa_analytics_active_users', 0),
    'android_users' => get_option('koa_analytics_android_users', 0),
    'ios_users' => get_option('koa_analytics_ios_users', 0),
    'total_events' => get_option('koa_analytics_total_events', 0),
    'retention_rate' => get_option('koa_analytics_retention_rate', 0),
];

// Obtener datos de los últimos 30 días para las gráficas
$daily_data = get_option('koa_analytics_daily_data', []);
?>

<div class="overview-container">
    <!-- Tarjetas de Resumen -->
    <div class="row g-4 mb-4">
        <!-- Usuarios Totales -->
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <span class="badge bg-primary p-3 rounded-circle">
                                <i class="fas fa-users fa-lg"></i>
                            </span>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="card-title mb-1">Usuarios Totales</h6>
                            <h2 class="mb-0"><?php echo number_format($analytics_data['total_users']); ?></h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Usuarios Activos -->
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <span class="badge bg-success p-3 rounded-circle">
                                <i class="fas fa-user-check fa-lg"></i>
                            </span>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="card-title mb-1">Usuarios Activos</h6>
                            <h2 class="mb-0"><?php echo number_format($analytics_data['active_users']); ?></h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Eventos -->
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <span class="badge bg-warning p-3 rounded-circle">
                                <i class="fas fa-flag fa-lg"></i>
                            </span>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="card-title mb-1">Total Eventos</h6>
                            <h2 class="mb-0"><?php echo number_format($analytics_data['total_events']); ?></h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tasa de Retención -->
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <span class="badge bg-info p-3 rounded-circle">
                                <i class="fas fa-chart-line fa-lg"></i>
                            </span>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="card-title mb-1">Tasa de Retención</h6>
                            <h2 class="mb-0"><?php echo number_format($analytics_data['retention_rate'], 1); ?>%</h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Gráficas -->
    <div class="row g-4">
        <!-- Distribución por Plataforma -->
        <div class="col-12 col-lg-6">
            <div class="card h-100">
                <div class="card-header">
                    <h5 class="card-title mb-0">Distribución por Plataforma</h5>
                </div>
                <div class="card-body">
                    <div class="chart-container" style="position: relative; height:300px;">
                        <canvas id="platformChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tendencia de Usuarios -->
        <div class="col-12 col-lg-6">
            <div class="card h-100">
                <div class="card-header">
                    <h5 class="card-title mb-0">Tendencia de Usuarios</h5>
                </div>
                <div class="card-body">
                    <div class="chart-container" style="position: relative; height:300px;">
                        <canvas id="usersChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Gráfica de Distribución por Plataforma
    new Chart(document.getElementById('platformChart'), {
        type: 'doughnut',
        data: {
            labels: ['Android', 'iOS'],
            datasets: [{
                data: [
                    <?php echo $analytics_data['android_users']; ?>,
                    <?php echo $analytics_data['ios_users']; ?>
                ],
                backgroundColor: ['#3ddc84', '#000000']
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });

    // Gráfica de Tendencia de Usuarios
    new Chart(document.getElementById('usersChart'), {
        type: 'line',
        data: {
            labels: <?php echo json_encode(array_keys($daily_data)); ?>,
            datasets: [{
                label: 'Usuarios Activos',
                data: <?php echo json_encode(array_values($daily_data)); ?>,
                borderColor: '#0d6efd',
                tension: 0.4,
                fill: true,
                backgroundColor: 'rgba(13, 110, 253, 0.1)'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            },
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
});
</script> 