<?php
if (!defined('ABSPATH')) exit;

// Obtener datos de eventos
$events_data = [
    'total_events' => get_option('koa_analytics_total_events', 0),
    'unique_events' => get_option('koa_analytics_unique_events', 0),
    'events_per_user' => get_option('koa_analytics_events_per_user', 0.0),
    'conversion_rate' => get_option('koa_analytics_conversion_rate', 0.0),
];

// Obtener eventos principales
$main_events = get_option('koa_analytics_main_events', []);

// Obtener eventos personalizados
$custom_events = get_option('koa_analytics_custom_events', []);

// Procesar el formulario de nuevo evento
if (isset($_POST['add_event'])) {
    check_admin_referer('koa_analytics_add_event_nonce');
    
    $new_event = [
        'name' => sanitize_text_field($_POST['event_name']),
        'type' => sanitize_text_field($_POST['event_type']),
        'description' => sanitize_textarea_field($_POST['event_description']),
        'icon' => sanitize_text_field($_POST['event_icon']),
        'active' => true,
        'created_at' => current_time('mysql'),
    ];

    $custom_events[] = $new_event;
    update_option('koa_analytics_custom_events', $custom_events);
    echo '<div class="notice notice-success"><p>Evento agregado exitosamente.</p></div>';
}
?>

<div class="events-container p-4">
    <!-- Métricas Principales -->
    <div class="row g-4 mb-4">
        <!-- Total Eventos -->
        <div class="col-12 col-md-4">
            <div class="card h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <span class="badge bg-primary p-3 rounded-circle">
                                <i class="fas fa-chart-bar fa-lg"></i>
                            </span>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="card-title mb-1">Total Eventos</h6>
                            <h2 class="mb-0"><?php echo number_format($events_data['total_events']); ?></h2>
                            <small class="text-muted">Eventos registrados</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Eventos por Usuario -->
        <div class="col-12 col-md-4">
            <div class="card h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <span class="badge bg-success p-3 rounded-circle">
                                <i class="fas fa-user-tag fa-lg"></i>
                            </span>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="card-title mb-1">Eventos por Usuario</h6>
                            <h2 class="mb-0"><?php echo number_format($events_data['events_per_user'], 1); ?></h2>
                            <small class="text-muted">Promedio de eventos por usuario</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tasa de Conversión -->
        <div class="col-12 col-md-4">
            <div class="card h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <span class="badge bg-info p-3 rounded-circle">
                                <i class="fas fa-percentage fa-lg"></i>
                            </span>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="card-title mb-1">Tasa de Conversión</h6>
                            <h2 class="mb-0"><?php echo number_format($events_data['conversion_rate'], 1); ?>%</h2>
                            <small class="text-muted">Porcentaje de conversión</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Análisis Detallado -->
    <div class="row g-4">
        <!-- Eventos Principales -->
        <div class="col-12 col-lg-8">
            <div class="card h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Eventos Principales</h5>
                    <div class="btn-group">
                        <button class="btn btn-sm btn-outline-secondary active">7 días</button>
                        <button class="btn btn-sm btn-outline-secondary">30 días</button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Evento</th>
                                    <th>Total</th>
                                    <th>Usuarios Únicos</th>
                                    <th>Tendencia</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($main_events as $event): ?>
                                <tr>
                                    <td>
                                        <i class="<?php echo esc_attr($event['icon']); ?> me-2"></i>
                                        <?php echo esc_html($event['name']); ?>
                                    </td>
                                    <td><?php echo number_format($event['total']); ?></td>
                                    <td><?php echo number_format($event['unique_users']); ?></td>
                                    <td>
                                        <span class="badge <?php echo $event['trend'] > 0 ? 'bg-success' : 'bg-danger'; ?>">
                                            <?php echo $event['trend'] > 0 ? '+' : ''; ?><?php echo number_format($event['trend'], 1); ?>%
                                        </span>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Eventos Personalizados -->
        <div class="col-12 col-lg-4">
            <div class="card h-100">
                <div class="card-header">
                    <h5 class="card-title mb-0">Eventos Personalizados</h5>
                </div>
                <div class="card-body">
                    <div class="list-group list-group-flush">
                        <?php foreach ($custom_events as $event): ?>
                        <div class="list-group-item">
                            <div class="d-flex w-100 justify-content-between">
                                <h6 class="mb-1"><?php echo esc_html($event['name']); ?></h6>
                                <small class="text-muted"><?php echo esc_html($event['last_24h']); ?></small>
                            </div>
                            <div class="d-flex justify-content-between align-items-center">
                                <small class="text-muted">Total: <?php echo number_format($event['total']); ?></small>
                                <span class="badge <?php echo $event['trend'] > 0 ? 'bg-success' : 'bg-danger'; ?>">
                                    <?php echo $event['trend'] > 0 ? '+' : ''; ?><?php echo number_format($event['trend'], 1); ?>%
                                </span>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Flujo de Eventos -->
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Flujo de Eventos</h5>
                </div>
                <div class="card-body">
                    <div class="chart-container" style="position: relative; height:300px;">
                        <canvas id="eventFlowChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Nueva sección para gestión de eventos -->
    <div class="row g-4 mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Gestión de Eventos</h5>
                    <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addEventModal">
                        <i class="fas fa-plus me-2"></i>Nuevo Evento
                    </button>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Evento</th>
                                    <th>Tipo</th>
                                    <th>Descripción</th>
                                    <th>Estado</th>
                                    <th>Código de Tracking</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($custom_events as $index => $event): ?>
                                <tr>
                                    <td>
                                        <i class="<?php echo esc_attr($event['icon']); ?> me-2"></i>
                                        <?php echo esc_html($event['name']); ?>
                                    </td>
                                    <td><?php echo esc_html($event['type']); ?></td>
                                    <td><?php echo esc_html($event['description']); ?></td>
                                    <td>
                                        <span class="badge <?php echo $event['active'] ? 'bg-success' : 'bg-secondary'; ?>">
                                            <?php echo $event['active'] ? 'Activo' : 'Inactivo'; ?>
                                        </span>
                                    </td>
                                    <td>
                                        <code class="bg-light p-2">
                                            KoaAnalytics.trackEvent('<?php echo esc_js($event['name']); ?>')
                                        </code>
                                        <button class="btn btn-sm btn-outline-secondary ms-2" onclick="copyToClipboard(this)">
                                            <i class="fas fa-copy"></i>
                                        </button>
                                    </td>
                                    <td>
                                        <div class="btn-group">
                                            <button class="btn btn-sm btn-outline-primary" onclick="editEvent(<?php echo $index; ?>)">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button class="btn btn-sm btn-outline-danger" onclick="deleteEvent(<?php echo $index; ?>)">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para agregar evento -->
    <div class="modal fade" id="addEventModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="post" action="">
                    <?php wp_nonce_field('koa_analytics_add_event_nonce'); ?>
                    <div class="modal-header">
                        <h5 class="modal-title">Agregar Nuevo Evento</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Nombre del Evento</label>
                            <input type="text" class="form-control" name="event_name" required>
                            <small class="form-text text-muted">Usa un nombre descriptivo y único</small>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Tipo de Evento</label>
                            <select class="form-select" name="event_type" required>
                                <option value="interaction">Interacción</option>
                                <option value="conversion">Conversión</option>
                                <option value="engagement">Engagement</option>
                                <option value="custom">Personalizado</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Descripción</label>
                            <textarea class="form-control" name="event_description" rows="3"></textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Icono</label>
                            <input type="text" class="form-control" name="event_icon" 
                                   placeholder="fas fa-star" value="fas fa-star">
                            <small class="form-text text-muted">
                                Clase de Font Awesome (ej: fas fa-star). 
                                <a href="https://fontawesome.com/icons" target="_blank">Ver iconos disponibles</a>
                            </small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" name="add_event" class="btn btn-primary">Agregar Evento</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Gráfica de Flujo de Eventos
    new Chart(document.getElementById('eventFlowChart'), {
        type: 'line',
        data: {
            labels: ['Lun', 'Mar', 'Mié', 'Jue', 'Vie', 'Sáb', 'Dom'],
            datasets: [{
                label: 'Eventos',
                data: <?php echo json_encode(array_column($main_events, 'daily_data')); ?>,
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
                    beginAtZero: true
                }
            }
        }
    });
});

// Función para copiar al portapapeles
function copyToClipboard(button) {
    const code = button.previousElementSibling.textContent.trim();
    navigator.clipboard.writeText(code).then(() => {
        const originalHTML = button.innerHTML;
        button.innerHTML = '<i class="fas fa-check"></i>';
        setTimeout(() => {
            button.innerHTML = originalHTML;
        }, 2000);
    });
}

// Función para editar evento
function editEvent(index) {
    // Implementar edición de evento
    console.log('Editar evento:', index);
}

// Función para eliminar evento
function deleteEvent(index) {
    if (confirm('¿Estás seguro de que deseas eliminar este evento?')) {
        // Implementar eliminación de evento
        console.log('Eliminar evento:', index);
    }
}
</script> 