<?php
if (!defined('ABSPATH')) exit;

// Obtener datos de retención
$retention_data = [
    'd1' => get_option('koa_analytics_retention_d1', 0),
    'd7' => get_option('koa_analytics_retention_d7', 0),
    'd30' => get_option('koa_analytics_retention_d30', 0),
    'churn_rate' => get_option('koa_analytics_churn_rate', 0),
];

// Obtener datos de cohortes
$cohort_data = get_option('koa_analytics_cohort_data', []);
?>

<div class="retention-container p-4">
    <!-- Métricas Principales -->
    <div class="row g-4 mb-4">
        <!-- Retención D1 -->
        <div class="col-12 col-md-4">
            <div class="card h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <span class="badge bg-primary p-3 rounded-circle">
                                <i class="fas fa-user-check fa-lg"></i>
                            </span>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="card-title mb-1">Retención D1</h6>
                            <h2 class="mb-0"><?php echo number_format($retention_data['d1'], 1); ?>%</h2>
                            <small class="text-muted">Usuarios que regresan al día siguiente</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Retención D7 -->
        <div class="col-12 col-md-4">
            <div class="card h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <span class="badge bg-success p-3 rounded-circle">
                                <i class="fas fa-users fa-lg"></i>
                            </span>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="card-title mb-1">Retención D7</h6>
                            <h2 class="mb-0"><?php echo number_format($retention_data['d7'], 1); ?>%</h2>
                            <small class="text-muted">Usuarios que regresan en 7 días</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Churn Rate -->
        <div class="col-12 col-md-4">
            <div class="card h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <span class="badge bg-danger p-3 rounded-circle">
                                <i class="fas fa-user-times fa-lg"></i>
                            </span>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="card-title mb-1">Churn Rate</h6>
                            <h2 class="mb-0"><?php echo number_format($retention_data['churn_rate'], 1); ?>%</h2>
                            <small class="text-muted">Tasa de abandono de usuarios</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Análisis Detallado -->
    <div class="row g-4">
        <!-- Curva de Retención -->
        <div class="col-12 col-lg-8">
            <div class="card h-100">
                <div class="card-header">
                    <h5 class="card-title mb-0">Curva de Retención</h5>
                </div>
                <div class="card-body">
                    <div class="chart-container" style="position: relative; height:300px;">
                        <canvas id="retentionChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Segmentación de Usuarios -->
        <div class="col-12 col-lg-4">
            <div class="card h-100">
                <div class="card-header">
                    <h5 class="card-title mb-0">Segmentación de Usuarios</h5>
                </div>
                <div class="card-body">
                    <div class="list-group list-group-flush">
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <i class="fas fa-user-plus text-success me-2"></i>
                                Usuarios Nuevos
                            </div>
                            <span class="badge bg-success rounded-pill">
                                <?php echo number_format(get_option('koa_analytics_new_users', 0)); ?>
                            </span>
                        </div>
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <i class="fas fa-sync text-primary me-2"></i>
                                Usuarios Recurrentes
                            </div>
                            <span class="badge bg-primary rounded-pill">
                                <?php echo number_format(get_option('koa_analytics_returning_users', 0)); ?>
                            </span>
                        </div>
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <i class="fas fa-user-slash text-danger me-2"></i>
                                Usuarios Perdidos
                            </div>
                            <span class="badge bg-danger rounded-pill">
                                <?php echo number_format(get_option('koa_analytics_churned_users', 0)); ?>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabla de Cohortes -->
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Análisis de Cohortes</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>Cohorte</th>
                                    <th>Usuarios</th>
                                    <th>D1</th>
                                    <th>D7</th>
                                    <th>D14</th>
                                    <th>D30</th>
                                    <th>D60</th>
                                    <th>D90</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($cohort_data as $cohort): ?>
                                <tr>
                                    <td><?php echo esc_html($cohort['date']); ?></td>
                                    <td><?php echo number_format($cohort['users']); ?></td>
                                    <td><?php echo number_format($cohort['d1'], 1); ?>%</td>
                                    <td><?php echo number_format($cohort['d7'], 1); ?>%</td>
                                    <td><?php echo number_format($cohort['d14'], 1); ?>%</td>
                                    <td><?php echo number_format($cohort['d30'], 1); ?>%</td>
                                    <td><?php echo number_format($cohort['d60'], 1); ?>%</td>
                                    <td><?php echo number_format($cohort['d90'], 1); ?>%</td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Gráfica de Retención
    new Chart(document.getElementById('retentionChart'), {
        type: 'line',
        data: {
            labels: ['D1', 'D7', 'D14', 'D30', 'D60', 'D90'],
            datasets: [{
                label: 'Retención',
                data: [
                    <?php echo $retention_data['d1']; ?>,
                    <?php echo $retention_data['d7']; ?>,
                    <?php echo get_option('koa_analytics_retention_d14', 0); ?>,
                    <?php echo $retention_data['d30']; ?>,
                    <?php echo get_option('koa_analytics_retention_d60', 0); ?>,
                    <?php echo get_option('koa_analytics_retention_d90', 0); ?>
                ],
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
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    max: 100,
                    ticks: {
                        callback: function(value) {
                            return value + '%';
                        }
                    }
                }
            }
        }
    });
});
</script> 