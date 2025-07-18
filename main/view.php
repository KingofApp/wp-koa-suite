<?php
function koa_suite_view() {
  $active_tab = isset($_GET['tab']) ? $_GET['tab'] : 'dashboard';
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
                  <a class="nav-link <?php echo ($active_tab == 'dashboard') ? 'active' : ''; ?>" href="<?php echo admin_url('admin.php?page=koa-suite&tab=dashboard'); ?>">Dashboard</a>
              </li>
              <li class="nav-item">
                  <a class="nav-link <?php echo ($active_tab == 'koa-embed') ? 'active' : ''; ?>" href="<?php echo admin_url('admin.php?page=koa-suite&tab=koa-embed'); ?>">KOA Embed</a>
              </li>
              <li class="nav-item">
                  <a class="nav-link <?php echo ($active_tab == 'analytics') ? 'active' : ''; ?>" href="<?php echo admin_url('admin.php?page=koa-suite&tab=analytics'); ?>">Analytics</a>
              </li>
              <li class="nav-item">
                  <a class="nav-link <?php echo ($active_tab == 'push') ? 'active' : ''; ?>" href="<?php echo admin_url('admin.php?page=koa-suite&tab=push'); ?>">Push Notifications</a>
              </li>
              <li class="nav-item">
                  <a class="nav-link <?php echo ($active_tab == 'qr') ? 'active' : ''; ?>" href="<?php echo admin_url('admin.php?page=koa-suite&tab=qr'); ?>">Distribution</a>
              </li>
          </ul>

          <!-- Contenido -->
          <div class="tab-content">
              <?php
              switch ($active_tab) {
                  case 'dashboard':
                      if (file_exists($dashboard_file)) {
                          include($dashboard_file);
                      } else {
                          echo '<div class="alert alert-warning">Dashboard file not found at: ' . esc_html($dashboard_file) . '</div>';
                      }
                      break;
                  case 'koa-embed':
                      if (file_exists($embed_file)) {
                          include($embed_file);
                      } else {
                          echo '<div class="alert alert-warning">Embed file not found at: ' . esc_html($embed_file) . '</div>';
                      }
                      break;
                  case 'analytics':
                      if (file_exists($analytics_file)) {
                          include($analytics_file);
                      } else {
                          echo '<div class="alert alert-warning">Analytics file not found at: ' . esc_html($analytics_file) . '</div>';
                      }
                      break;
                  case 'push':
                      if (file_exists($push_file)) {
                          include($push_file);
                      } else {
                          echo '<div class="alert alert-warning">Push Notifications file not found at: ' . esc_html($push_file) . '</div>';
                      }
                      break;
                  case 'qr':
                      if (file_exists($qr_file)) {
                          include($qr_file);
                      } else {
                          echo '<div class="alert alert-warning">QR Generator file not found at: ' . esc_html($qr_file) . '</div>';
                      }
                      break;
                  default:
                      echo '<div class="alert alert-warning">Invalid tab selected.</div>';
              }
              ?>
          </div>
      </div>
  </div>
  <?php
}