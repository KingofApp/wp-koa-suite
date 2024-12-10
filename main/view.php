<?php
function koa_suite_view() {
  // Definir las rutas de los archivos
  $plugin_dir = plugin_dir_path(dirname(__FILE__));
  $dashboard_file = $plugin_dir . 'dashboard/main.php';
  $embed_file = $plugin_dir . 'embed/view.php';
  $analytics_file = $plugin_dir . 'dashboard/analytics/dashboard.php';
  $qr_file = $plugin_dir . 'qr/koa-qr.php';
  $push_file = $plugin_dir . 'push/admin/tabs.php';
  ?>
  <div class="wrap">
      <div class="container-fluid py-4">
          <h2>Koa Suite</h2>
          <!-- Pestañas superiores -->
          <ul class="nav nav-tabs mb-4">
              <li class="nav-item">
                  <a class="nav-link active" href="#dashboard" data-bs-toggle="tab">Dashboard</a>
              </li>
              <li class="nav-item">
                  <a class="nav-link" href="#koa-embed" data-bs-toggle="tab">KOA Embed</a>
              </li>
              <li class="nav-item">
                  <a class="nav-link" href="#analytics" data-bs-toggle="tab">Analytics</a>
              </li>
              <li class="nav-item">
                  <a class="nav-link" href="#push" data-bs-toggle="tab">Push Notifications</a>
              </li>
              <li class="nav-item">
                  <a class="nav-link" href="#qr" data-bs-toggle="tab">Distribution</a>
              </li>
          </ul>

          <!-- Contenido -->
          <div class="tab-content">
              <div class="tab-pane fade show active" id="dashboard">
                  <?php 
                  if (file_exists($dashboard_file)) {
                      include($dashboard_file);
                  } else {
                      echo '<div class="alert alert-warning">
                          Dashboard file not found at: ' . esc_html($dashboard_file) . '
                      </div>';
                  }
                  ?>
              </div>
              <div class="tab-pane fade" id="koa-embed">
                  <?php 
                  if (file_exists($embed_file)) {
                      include($embed_file);
                  } else {
                      echo '<div class="alert alert-warning">
                          Embed file not found at: ' . esc_html($embed_file) . '
                      </div>';
                  }
                  ?>
              </div>
              <div class="tab-pane fade" id="analytics">
                  <?php 
                  if (file_exists($analytics_file)) {
                      include($analytics_file);
                  } else {
                      echo '<div class="alert alert-warning">
                          Analytics file not found at: ' . esc_html($analytics_file) . '
                      </div>';
                  }
                  ?>
              </div>
              <div class="tab-pane fade" id="push">
                  <?php 
                  if (file_exists($push_file)) {
                      include($push_file);
                  } else {
                      echo '<div class="alert alert-warning">
                          Push Notifications file not found at: ' . esc_html($push_file) . '
                      </div>';
                  }
                  ?>
              </div>
              <div class="tab-pane fade" id="qr">
                  <?php 
                  if (file_exists($qr_file)) {
                      include($qr_file);
                  } else {
                      echo '<div class="alert alert-warning">
                          QR Generator file not found at: ' . esc_html($qr_file) . '
                      </div>';
                  }
                  ?>
              </div>
          </div>
      </div>
  </div>
  <?php
}?>
