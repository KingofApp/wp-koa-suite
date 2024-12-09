<?php
if (!defined('ABSPATH')) exit;

// Obtener datos específicos de Android
$androidData = $data['android'] ?? [];
?>

<div class="row g-4">
    <!-- Métricas de Android -->
    <div class="col-md-3">
        <div class="card h-100">
            <div class="card-body">
                <h6 class="card-subtitle mb-2 text-muted">Instalaciones Android</h6>
                <div class="d-flex align-items-center">
                    <h3 class="mb-0"><?php echo number_format($androidData['installs'] ?? 0); ?></h3>
                    <span class="ms-2 <?php echo ($androidData['installs_trend'] ?? 0) > 0 ? 'text-success' : 'text-danger'; ?>">
                        <?php echo number_format($androidData['installs_trend'] ?? 0, 1); ?>%
                    </span>
                </div>
                <canvas id="androidInstallsChart" height="60"></canvas>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card h-100">
            <div class="card-body">
                <h6 class="card-subtitle mb-2 text-muted">Valoración Media</h6>
                <div class="d-flex align-items-center">
                    <h3 class="mb-0"><?php echo number_format($androidData['rating'] ?? 0, 1); ?></h3>
                    <small class="ms-2 text-muted">(<?php echo number_format($androidData['total_ratings'] ?? 0); ?> valoraciones)</small>
                </div>
                <div class="ratings-breakdown mt-3">
                    <?php for($i = 5; $i >= 1; $i--): ?>
                    <div class="d-flex align-items-center small">
                        <span class="me-2"><?php echo $i; ?>★</span>
                        <div class="progress flex-grow-1" style="height: 4px;">
                            <div class="progress-bar" style="width: <?php echo $androidData['ratings_breakdown'][$i] ?? 0; ?>%"></div>
                        </div>
                        <span class="ms-2"><?php echo $androidData['ratings_breakdown'][$i] ?? 0; ?>%</span>
                    </div>
                    <?php endfor; ?>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card h-100">
            <div class="card-body">
                <h6 class="card-title">Distribución de Versiones de Android</h6>
                <canvas id="androidVersionsChart" height="200"></canvas>
            </div>
        </div>
    </div>

    <!-- Rendimiento de la App -->
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
                                <td>ANR Rate</td>
                                <td><?php echo number_format($androidData['anr_rate'] ?? 0, 2); ?>%</td>
                                <td>
                                    <span class="<?php echo ($androidData['anr_trend'] ?? 0) < 0 ? 'text-success' : 'text-danger'; ?>">
                                        <?php echo number_format($androidData['anr_trend'] ?? 0, 1); ?>%
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <td>Crash Rate</td>
                                <td><?php echo number_format($androidData['crash_rate'] ?? 0, 2); ?>%</td>
                                <td>
                                    <span class="<?php echo ($androidData['crash_trend'] ?? 0) < 0 ? 'text-success' : 'text-danger'; ?>">
                                        <?php echo number_format($androidData['crash_trend'] ?? 0, 1); ?>%
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
                <h6 class="card-title">Adquisición de Usuarios</h6>
                <canvas id="androidAcquisitionChart" height="200"></canvas>
                <div class="mt-3">
                    <div class="row text-center">
                        <div class="col">
                            <div class="small text-muted">Orgánico</div>
                            <h5><?php echo number_format($androidData['organic_installs'] ?? 0); ?></h5>
                        </div>
                        <div class="col">
                            <div class="small text-muted">Google Ads</div>
                            <h5><?php echo number_format($androidData['google_ads_installs'] ?? 0); ?></h5>
                        </div>
                        <div class="col">
                            <div class="small text-muted">Otros</div>
                            <h5><?php echo number_format($androidData['other_installs'] ?? 0); ?></h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Inicializar gráficos específicos de Android
    const androidData = <?php echo json_encode($androidData); ?>;
    
    // Gráfico de versiones de Android
    new Chart(document.getElementById('androidVersionsChart'), {
        type: 'doughnut',
        data: {
            labels: Object.keys(androidData.versions || {}),
            datasets: [{
                data: Object.values(androidData.versions || {}),
                backgroundColor: [
                    '#4285F4', '#34A853', '#FBBC05', '#EA4335',
                    '#5E97F6', '#3CBA54', '#FDD835', '#E53935'
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
    new Chart(document.getElementById('androidAcquisitionChart'), {
        type: 'bar',
        data: {
            labels: ['Orgánico', 'Google Ads', 'Otros'],
            datasets: [{
                data: [
                    androidData.organic_installs || 0,
                    androidData.google_ads_installs || 0,
                    androidData.other_installs || 0
                ],
                backgroundColor: ['#34A853', '#4285F4', '#FBBC05']
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