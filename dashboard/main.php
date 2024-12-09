<?php
if (!defined('ABSPATH')) exit;

// Obtener actividad reciente
function get_recent_activity() {
    $activity = get_option('koa_recent_activity', []);
    if (empty($activity)) {
        $activity = [
            [
                'type' => 'info',
                'icon' => 'rocket',
                'title' => 'Bienvenido a King of App',
                'description' => 'Comienza a configurar tu aplicación',
                'time' => current_time('mysql')
            ]
        ];
    }
    return array_slice($activity, 0, 5); // Mostrar solo las últimas 5 actividades
}

// Función para registrar nueva actividad
function register_koa_activity($title, $type = 'info', $icon = 'info-circle', $description = '') {
    $activity = get_option('koa_recent_activity', []);
    array_unshift($activity, [
        'type' => $type,
        'icon' => $icon,
        'title' => $title,
        'description' => $description,
        'time' => current_time('mysql')
    ]);
    
    // Mantener solo las últimas 20 actividades
    $activity = array_slice($activity, 0, 20);
    update_option('koa_recent_activity', $activity);
}

$recent_activity = get_recent_activity();
?>

<!-- Modal para Videos -->
<div class="modal fade" id="videoModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content bg-dark">
            <div class="modal-header border-0">
                <h5 class="modal-title text-white" id="videoModalTitle"></h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-0">
                <div class="ratio ratio-16x9">
                    <iframe id="videoFrame" src="" allowfullscreen></iframe>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal para Pasos y Marketing -->
<div class="modal fade" id="stepModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="stepModalTitle"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="ratio ratio-16x9 mb-3">
                            <iframe id="stepVideoFrame" src="" allowfullscreen allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"></iframe>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div id="stepModalContent" class="p-3"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Banner Principal -->
<div class="banner-container mb-4">
    <div class="row align-items-center justify-content-between">
        <!-- Banner -->
        <div class="col-banner" style="width: 64%;">
            <img src="https://kingofapp.com/wp-content/uploads/2024/12/banner.webp" 
                 alt="King of App Banner" 
                 class="img-fluid rounded shadow-sm">
        </div>
        
        <!-- Botones -->
        <div class="col-buttons" style="width: 34%;">
            <div class="d-flex flex-column align-items-start gap-3">
                <a href="https://www.kingofapp.com" 
                   target="_blank" 
                   class="btn btn-lg w-100 text-start" style="background-color: #0d6efd; color: white;">
                    King of App
                </a>
                <a href="https://builder.kingofapp.com" 
                   target="_blank" 
                   class="btn btn-lg w-100 text-start" style="background-color: #0d6efd; color: white;">
                    Constructor de Apps
                </a>
                <a href="https://academy.kingofapp.com" 
                   target="_blank" 
                   class="btn btn-lg w-100 text-start" style="background-color: #0d6efd; color: white;">
                    Academia de Apps
                </a>
            </div>
        </div>
    </div>
</div>

<div class="dashboard-container">
    <!-- Header -->
    <div class="dashboard-header mb-4">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h2 class="mb-0">Dashboard</h2>
            </div>
        </div>
    </div>

    <!-- Featured Videos (Hot Bids Style) -->
    <div class="row g-4 mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="mb-0">Videos Destacados</h5>
                <a href="https://www.youtube.com/@Kingofapp" 
                   class="btn btn-primary" 
                   target="_blank">
                    Ver todos los videos
                </a>
            </div>
        </div>
        <div class="col-md-4">
            <div class="video-card" data-video-url="https://www.youtube.com/watch?v=fuPKDX2q4do&list=PLKIhgvPNjLT_J3d4atEUOFQrDKQkdUg7q">
                <div class="video-thumbnail">
                    <img src="https://img.youtube.com/vi/fuPKDX2q4do/maxresdefault.jpg" class="img-fluid rounded-4">
                    <div class="video-overlay">
                        <div class="video-info">
                            <span class="badge bg-primary mb-2">Tutorial</span>
                            <h5>Introducción a King of App</h5>
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="text-muted">5:30 min</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="video-card" data-video-url="https://www.youtube.com/watch?v=YhCbGuVgLy4&list=PLKIhgvPNjLT9jAvUfqM52kaDxWdR9V_Y6">
                <div class="video-thumbnail">
                    <img src="https://img.youtube.com/vi/YhCbGuVgLy4/maxresdefault.jpg" class="img-fluid rounded-4">
                    <div class="video-overlay">
                        <div class="video-info">
                            <span class="badge bg-success mb-2">Avanzado</span>
                            <h5>Personalización Avanzada</h5>
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="text-muted">7:15 min</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="video-card" data-video-url="https://www.youtube.com/watch?v=X1yVyIcdGhc&list=PLKIhgvPNjLT-v_JGzuqihIHZH8XHbX2SY">
                <div class="video-thumbnail">
                    <img src="https://img.youtube.com/vi/X1yVyIcdGhc/maxresdefault.jpg" class="img-fluid rounded-4">
                    <div class="video-overlay">
                        <div class="video-info">
                            <span class="badge bg-warning mb-2">Guía</span>
                            <h5>Configuración Inicial</h5>
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="text-muted">6:45 min</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Access Banners -->
    <div class="row g-4 mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="mb-0">Accesos Rápidos</h5>
            </div>
        </div>
        <div class="col-md-3">
            <a href="/wp-admin/admin.php?page=koa-suite#analytics" class="banner-card analytics-banner" onclick="document.querySelector('[href=\'#analytics\']').click()">
                <div class="banner-content">
                    <h4>Analíticas</h4>
                    <span class="banner-arrow">→</span>
                </div>
            </a>
        </div>
        <div class="col-md-3">
            <a href="/wp-admin/admin.php?page=koa-suite#push" class="banner-card push-banner" onclick="document.querySelector('[href=\'#push\']').click()">
                <div class="banner-content">
                    <h4>Push</h4>
                    <span class="banner-arrow">→</span>
                </div>
            </a>
        </div>
        <div class="col-md-3">
            <a href="/wp-admin/admin.php?page=koa-suite#qr" class="banner-card qr-banner" onclick="document.querySelector('[href=\'#qr\']').click()">
                <div class="banner-content">
                    <h4>Distribution</h4>
                    <span class="banner-arrow">→</span>
                </div>
            </a>
        </div>
        <div class="col-md-3">
            <a href="/wp-admin/admin.php?page=koa-suite#koa-embed" class="banner-card menus-banner" onclick="document.querySelector('[href=\'#koa-embed\']').click()">
                <div class="banner-content">
                    <h4>KOA Embed</h4>
                    <span class="banner-arrow">→</span>
                </div>
            </a>
        </div>
    </div>

    <!-- Pasos a Seguir -->
    <div class="row g-4 mb-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-transparent d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-list-check me-2"></i>
                        Pasos a Seguir
                    </h5>
                </div>
                <div class="card-body p-0">
                    <div class="activity-feed">
                        <div class="activity-item p-3 border-bottom step-item" 
                             data-video="STEP1_CONFIG" 
                             data-title="Configura tu App" 
                             data-content="Aprende a configurar los ajustes básicos de tu aplicación paso a paso. Personaliza el nombre, icono, splash screen y más.">
                            <div class="d-flex align-items-center">
                                <div class="activity-icon bg-primary-soft me-3">
                                    <i class="fas fa-1 text-primary"></i>
                                </div>
                                <div class="activity-content">
                                    <h6 class="mb-1">Configura tu App</h6>
                                    <p class="mb-1 small text-muted">Personaliza los ajustes básicos de tu aplicación</p>
                                </div>
                            </div>
                        </div>
                        <div class="activity-item p-3 border-bottom step-item"
                             data-video="STEP2_DESIGN"
                             data-title="Personaliza el Diseño"
                             data-content="Descubre cómo adaptar el aspecto visual de tu app a tu marca. Modifica colores, fuentes y estilos.">
                            <div class="d-flex align-items-center">
                                <div class="activity-icon bg-success-soft me-3">
                                    <i class="fas fa-2 text-success"></i>
                                </div>
                                <div class="activity-content">
                                    <h6 class="mb-1">Personaliza el Diseño</h6>
                                    <p class="mb-1 small text-muted">Adapta el aspecto visual a tu marca</p>
                                </div>
                            </div>
                        </div>
                        <div class="activity-item p-3 border-bottom step-item"
                             data-video="STEP3_PUSH"
                             data-title="Configura las Notificaciones Push"
                             data-content="Aprende a configurar y enviar notificaciones push para mantener a tus usuarios informados y comprometidos.">
                            <div class="d-flex align-items-center">
                                <div class="activity-icon bg-info-soft me-3">
                                    <i class="fas fa-3 text-info"></i>
                                </div>
                                <div class="activity-content">
                                    <h6 class="mb-1">Configura las Notificaciones Push</h6>
                                    <p class="mb-1 small text-muted">Mantén a tus usuarios informados</p>
                                </div>
                            </div>
                        </div>
                        <div class="activity-item p-3 step-item"
                             data-video="STEP4_PUBLISH"
                             data-title="Publica tu App"
                             data-content="Guía paso a paso para publicar tu aplicación en las tiendas de Apple y Google.">
                            <div class="d-flex align-items-center">
                                <div class="activity-icon bg-warning-soft me-3">
                                    <i class="fas fa-4 text-warning"></i>
                                </div>
                                <div class="activity-content">
                                    <h6 class="mb-1">Publica tu App</h6>
                                    <p class="mb-1 small text-muted">Distribuye tu aplicación en las tiendas</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Marketing Mobile -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-transparent d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-bullhorn me-2"></i>
                        Marketing Mobile
                    </h5>
                </div>
                <div class="card-body p-0">
                    <div class="activity-feed">
                        <div class="activity-item p-3 border-bottom step-item"
                             data-video="MARKETING1_FUNNEL"
                             data-title="Crea tu Funnel"
                             data-content="Aprende a crear un funnel efectivo para aumentar las descargas de tu app.">
                            <div class="d-flex align-items-center">
                                <div class="activity-icon bg-primary-soft me-3">
                                    <i class="fas fa-1 text-primary"></i>
                                </div>
                                <div class="activity-content">
                                    <h6 class="mb-1">Crea tu Funnel</h6>
                                    <p class="mb-1 small text-muted">Crea en tu funnel nuevos pasos para que se descarguen la app</p>
                                </div>
                            </div>
                        </div>
                        <div class="activity-item p-3 border-bottom step-item"
                             data-video="MARKETING2_ACTIONS"
                             data-title="Acciones de Usuario"
                             data-content="Estrategias para incentivar a los usuarios a usar tu app regularmente.">
                            <div class="d-flex align-items-center">
                                <div class="activity-icon bg-success-soft me-3">
                                    <i class="fas fa-2 text-success"></i>
                                </div>
                                <div class="activity-content">
                                    <h6 class="mb-1">Acciones de Usuario</h6>
                                    <p class="mb-1 small text-muted">Crea acciones para que tu usuario tome acción y empiece a usar la app</p>
                                </div>
                            </div>
                        </div>
                        <div class="activity-item p-3 border-bottom step-item"
                             data-video="MARKETING3_CRM"
                             data-title="Campañas CRM"
                             data-content="Configura campañas automatizadas con tu CRM.">
                            <div class="d-flex align-items-center">
                                <div class="activity-icon bg-info-soft me-3">
                                    <i class="fas fa-3 text-info"></i>
                                </div>
                                <div class="activity-content">
                                    <h6 class="mb-1">Campañas CRM</h6>
                                    <p class="mb-1 small text-muted">Configura campañas automatizadas con tu CRM</p>
                                </div>
                            </div>
                        </div>
                        <div class="activity-item p-3 step-item"
                             data-video="MARKETING4_RETENTION"
                             data-title="Retención de Usuarios"
                             data-content="Configura campañas para recuperar usuarios que no han usado la app.">
                            <div class="d-flex align-items-center">
                                <div class="activity-icon bg-warning-soft me-3">
                                    <i class="fas fa-4 text-warning"></i>
                                </div>
                                <div class="activity-content">
                                    <h6 class="mb-1">Retención de Usuarios</h6>
                                    <p class="mb-1 small text-muted">Configura campañas para recuperar usuarios que no han usado la app</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Actualizar estilos de video */
.video-card {
    position: relative;
    transition: transform 0.3s ease;
    margin-bottom: 1rem;
}

.video-card:hover {
    transform: translateY(-10px);
}

.video-thumbnail {
    position: relative;
    border-radius: 1rem;
    overflow: hidden;
}

.video-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(to top, rgba(0,0,0,0.9), rgba(0,0,0,0.3));
    opacity: 0;
    transition: opacity 0.3s ease;
    display: flex;
    flex-direction: column;
    justify-content: flex-end;
    align-items: center;
    padding: 1.5rem;
}

.video-card:hover .video-overlay {
    opacity: 1;
}

.video-info {
    width: 100%;
    text-align: center;
    color: white;
    margin-top: auto;
    z-index: 2;
}

.video-info h5 {
    font-size: 1.1rem;
    margin-bottom: 0.5rem;
    font-weight: 600;
}

.badge {
    padding: 0.5rem 1rem;
    font-size: 0.8rem;
}

/* Actualizar estilos de los banners */
.banner-card {
    display: block;
    height: 160px;
    border-radius: 1rem;
    padding: 1.5rem;
    color: white;
    text-decoration: none;
    position: relative;
    overflow: hidden;
    transition: all 0.3s ease;
}

.banner-card:hover {
    transform: translateY(-5px);
    color: white;
    text-decoration: none;
}

.banner-content {
    position: relative;
    z-index: 2;
    height: 100%;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
}

.banner-arrow {
    position: absolute;
    bottom: 0;
    right: 0;
    font-size: 1.5rem;
    color: rgba(255, 255, 255, 0.7);
    transition: transform 0.3s ease;
}

.banner-card:hover .banner-arrow {
    transform: translateX(5px);
    color: white;
}

/* Mantener los gradientes existentes */
.analytics-banner {
    background: linear-gradient(45deg, #4158D0, #C850C0);
}

.push-banner {
    background: linear-gradient(45deg, #00B4DB, #0083B0);
}

.qr-banner {
    background: linear-gradient(45deg, #FF416C, #FF4B2B);
}

.menus-banner {
    background: linear-gradient(45deg, #11998e, #38ef7d);
}

/* Estilo adicional para el botón */
.btn-primary {
    padding: 0.5rem 1rem;
    border-radius: 50rem;
    font-weight: 500;
    transition: all 0.3s ease;
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
}

/* Otros estilos existentes... */

/* Estilos para la actividad reciente */
.activity-feed {
    max-height: 300px;
    overflow-y: auto;
}

.activity-icon {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
}

.bg-info-soft { background-color: rgba(13, 202, 240, 0.1); }
.bg-success-soft { background-color: rgba(25, 135, 84, 0.1); }
.bg-warning-soft { background-color: rgba(255, 193, 7, 0.1); }
.bg-danger-soft { background-color: rgba(220, 53, 69, 0.1); }

.activity-item {
    transition: all 0.3s ease;
    cursor: pointer;
}

.activity-item:hover {
    background-color: rgba(0,0,0,0.05);
    transform: translateX(5px);
}

/* Personalización del scrollbar */
.activity-feed::-webkit-scrollbar {
    width: 6px;
}

.activity-feed::-webkit-scrollbar-track {
    background: #f1f1f1;
}

.activity-feed::-webkit-scrollbar-thumb {
    background: #888;
    border-radius: 3px;
}

.activity-feed::-webkit-scrollbar-thumb:hover {
    background: #555;
}

/* Estilos para el modal de video */
.modal-content.bg-dark {
    background-color: #0a0a0a !important;
}

.modal-header .btn-close-white {
    filter: invert(1) grayscale(100%) brightness(200%);
}

.modal-body .ratio {
    background-color: #000;
}

.modal-dialog-centered {
    display: flex;
    align-items: center;
    min-height: calc(100% - 1rem);
}

@media (min-width: 992px) {
    .modal-lg {
        max-width: 800px;
    }
}

.step-modal-content {
    background-color: #f8f9fa;
    border-radius: 0.5rem;
    padding: 1rem;
}

/* Estilos para los botones principales */
.col-buttons .btn {
    border-radius: 8px;
    transition: all 0.3s ease;
    border: none;
}

.col-buttons .btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(13, 110, 253, 0.2);
    opacity: 0.9;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Asegurarnos de que Bootstrap está disponible
    if (typeof bootstrap === 'undefined') {
        console.error('Bootstrap no está cargado');
        return;
    }

    // Función para extraer el ID del video de YouTube de la URL
    function getYoutubeId(url) {
        const regExp = /^.*(youtu.be\/|v\/|u\/\w\/|embed\/|watch\?v=|&v=)([^#&?]*).*/;
        const match = url.match(regExp);
        return (match && match[2].length === 11) ? match[2] : null;
    }

    // Inicializar el modal de video
    const modalElement = document.getElementById('videoModal');
    const videoModal = new bootstrap.Modal(modalElement);

    // Manejar clicks en las tarjetas de video
    document.querySelectorAll('.video-card').forEach(card => {
        card.addEventListener('click', function() {
            const videoUrl = this.dataset.videoUrl;
            const videoTitle = this.querySelector('.video-info h5').textContent;
            if (videoUrl) {
                const videoId = getYoutubeId(videoUrl);
                if (videoId) {
                    // Actualizar el título y la URL del iframe
                    document.getElementById('videoModalTitle').textContent = videoTitle;
                    document.getElementById('videoFrame').src = `https://www.youtube.com/embed/${videoId}?autoplay=1`;
                    
                    // Mostrar el modal
                    videoModal.show();
                }
            }
        });
    });

    // Limpiar el iframe cuando se cierra el modal
    modalElement.addEventListener('hidden.bs.modal', function () {
        document.getElementById('videoFrame').src = '';
    });

    // Función para activar las pestañas
    const activateTab = (tabId) => {
        const tab = document.querySelector(`[data-bs-target="${tabId}"]`);
        if (tab) {
            const bsTab = new bootstrap.Tab(tab);
            bsTab.show();
        }
    };

    // Manejar los clicks en los banners
    document.querySelectorAll('.banner-card').forEach(banner => {
        banner.addEventListener('click', function(e) {
            e.preventDefault();
            const tabTarget = this.getAttribute('data-tab-target');
            if (tabTarget) {
                activateTab(tabTarget);
            }
        });
    });

    // Inicializar el modal para pasos
    const stepModal = new bootstrap.Modal(document.getElementById('stepModal'));

    // Datos de los pasos y marketing
    const stepsData = {
        'STEP1_CONFIG': {
            videoId: '1vivN1yGtmg',
            title: 'Configura tu App',
            content: 'Aprende a configurar los ajustes básicos de tu aplicación paso a paso.'
        },
        'STEP2_DESIGN': {
            videoId: '1vivN1yGtmg',
            title: 'Personaliza el Diseño',
            content: 'Descubre cómo adaptar el aspecto visual de tu app a tu marca.'
        },
        'STEP3_PUSH': {
            videoId: '1vivN1yGtmg',
            title: 'Configura las Notificaciones Push',
            content: 'Aprende a configurar y enviar notificaciones push para mantener a tus usuarios informados y comprometidos.'
        },
        'STEP4_PUBLISH': {
            videoId: '1vivN1yGtmg',
            title: 'Publica tu App',
            content: 'Guía paso a paso para publicar tu aplicación en las tiendas de Apple y Google.'
        },
        'MARKETING1_FUNNEL': {
            videoId: '1vivN1yGtmg',
            title: 'Crea tu Funnel',
            content: 'Aprende a crear un funnel efectivo para aumentar las descargas de tu app.'
        },
        'MARKETING2_ACTIONS': {
            videoId: '1vivN1yGtmg',
            title: 'Acciones de Usuario',
            content: 'Estrategias para incentivar a los usuarios a usar tu app regularmente.'
        },
        'MARKETING3_CRM': {
            videoId: '1vivN1yGtmg',
            title: 'Campañas CRM',
            content: 'Configura campañas automatizadas con tu CRM.'
        },
        'MARKETING4_RETENTION': {
            videoId: '1vivN1yGtmg',
            title: 'Retención de Usuarios',
            content: 'Configura campañas para recuperar usuarios que no han usado la app.'
        }
    };

    // Manejar clicks en los pasos
    document.querySelectorAll('.step-item').forEach(item => {
        item.style.cursor = 'pointer';
        item.addEventListener('click', function() {
            const videoId = '1vivN1yGtmg';
            const title = this.dataset.title;
            const content = this.dataset.content;

            document.getElementById('stepModalTitle').textContent = title;
            document.getElementById('stepVideoFrame').src = `https://www.youtube.com/embed/1vivN1yGtmg`;
            document.getElementById('stepModalContent').innerHTML = `
                <h4>Descripción</h4>
                <p>${content}</p>
            `;

            stepModal.show();
        });
    });

    // Limpiar el iframe cuando se cierra el modal
    document.getElementById('stepModal').addEventListener('hidden.bs.modal', function () {
        document.getElementById('stepVideoFrame').src = '';
    });
});
</script>