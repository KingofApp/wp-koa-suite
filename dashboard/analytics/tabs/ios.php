<?php
if (!defined('ABSPATH')) exit;

// Obtener datos específicos de iOS
$iosData = $data['ios'] ?? [];
?>

<div class="row g-4">
    <!-- Métricas de iOS -->
    <div class="col-md-3">
        <div class="card h-100">
            <div class="card-body">
                <h6 class="card-subtitle mb-2 text-muted">Instalaciones iOS</h6>
                <div class="d-flex align-items-center">
                    <h3 class="mb-0"><?php echo number_format($iosData['installs'] ?? 0); ?></h3>
                    <span class="ms-2 <?php echo ($iosData['installs_trend'] ?? 0) > 0 ? 'text-success' : 'text-danger'; ?>">
                        <?php echo number_format($iosData['installs_trend'] ?? 0, 1); ?>%
                    </span>
                </div>
                <canvas id="iosInstallsChart" height="60"></canvas>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card h-100">
            <div class="card-body">
                <h6 class="card-subtitle mb-2 text-muted">App Store Rating</h6>
                <div class="d-flex align-items-center">
                    <h3 class="mb-0"><?php echo number_format($iosData['rating'] ?? 0, 1); ?></h3>
                    <small class="ms-2 text-muted">(<?php echo number_format($iosData['total_ratings'] ?? 0); ?> ratings)</small>
                </div>
                <div class="ratings-breakdown mt-3">
                    <?php for($i = 5; $i >= 1; $i--): ?>
                    <div class="d-flex align-items-center small">
                        <span class="me-2"><?php echo $i; ?>★</span>
                        <div class="progress flex-grow-1" style="height: 4px;">
                            <div class="progress-bar" style="width: <?php echo $iosData['ratings_breakdown'][$i] ?? 0; ?>%"></div>
                        </div>
                        <span class="ms-2"><?php echo $iosData['ratings_breakdown'][$i] ?? 0; ?>%</span>
                    </div>
                    <?php endfor; ?>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card h-100">
            <div class="card-body">
                <h6 class="card-title">Distribución de Versiones de iOS</h6>
                <canvas id="iosVersionsChart" height="200"></canvas>
            </div>
        </div>
    </div>

    <!-- Métricas de Rendimiento -->
    <div class="col-md-6">
        <div class="card h-100">
            <div class="card-body">
                <h6 class="card-title">Rendimiento</h6>
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Métrica</th>
                                <th>Valor</th>
                                <th>Tendencia</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Crash Rate</td>
                                <td><?php echo number_format($iosData['crash_rate'] ?? 0, 2); ?>%</td>
                                <td>
                                    <span class="<?php echo ($iosData['crash_trend'] ?? 0) < 0 ? 'text-success' : 'text-danger'; ?>">
                                        <?php echo number_format($iosData['crash_trend'] ?? 0, 1); ?>%
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <td>Battery Impact</td>
                                <td><?php echo $iosData['battery_impact'] ?? 'Low'; ?></td>
                                <td>
                                    <span class="text-muted">-</span>
                                </td>
                            </tr>
                            <tr>
                                <td>Launch Time</td>
                                <td><?php echo number_format($iosData['launch_time'] ?? 0, 2); ?>s</td>
                                <td>
                                    <span class="<?php echo ($iosData['launch_time_trend'] ?? 0) < 0 ? 'text-success' : 'text-danger'; ?>">
                                        <?php echo number_format($iosData['launch_time_trend'] ?? 0, 1); ?>%
                                    </span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Adquisición de Usuarios -->
    <div class="col-md-6">
        <div class="card h-100">
            <div class="card-body">
                <h6 class="card-title">Fuentes de Instalación</h6>
                <canvas id="iosAcquisitionChart" height="200"></canvas>
                <div class="mt-3">
                    <div class="row text-center">
                        <div class="col">
                            <div class="small text-muted">App Store Search</div>
                            <h5><?php echo number_format($iosData['app_store_search'] ?? 0); ?></h5>
                        </div>
                        <div class="col">
                            <div class="small text-muted">App Store Browse</div>
                            <h5><?php echo number_format($iosData['app_store_browse'] ?? 0); ?></h5>
                        </div>
                        <div class="col">
                            <div class="small text-muted">Web Referrer</div>
                            <h5><?php echo number_format($iosData['web_referrer'] ?? 0); ?></h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const iosData = <?php echo json_encode($iosData); ?>;
    
    // Gráfico de versiones de iOS
    new Chart(document.getElementById('iosVersionsChart'), {
        type: 'doughnut',
        data: {
            labels: Object.keys(iosData.versions || {}),
            datasets: [{
                data: Object.values(iosData.versions || {}),
                backgroundColor: [
                    '#007AFF', '#34C759', '#FF9500', '#FF2D55',
                    '#5856D6', '#AF52DE', '#FF3B30', '#5AC8FA'
                ]
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'right'
                }
            }
        }
    });

    // Gráfico de adquisición
    new Chart(document.getElementById('iosAcquisitionChart'), {
        type: 'bar',
        data: {
            labels: ['App Store Search', 'App Store Browse', 'Web Referrer'],
            datasets: [{
                data: [
                    iosData.app_store_search || 0,
                    iosData.app_store_browse || 0,
                    iosData.web_referrer || 0
                ],
                backgroundColor: ['#007AFF', '#34C759', '#FF9500']
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    display: false
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