<?php
function koa_suite_view(){
    // check user capabilities
  if ( !current_user_can( 'manage_options' ) ) {
    return;
  }

  //Get the active tab from the $_GET param
  $default_tab = null;
  $tab = isset($_GET['tab']) ? $_GET['tab'] : $default_tab;

  ?>
  <!-- Our admin page content should all be inside .wrap -->
  <div class="wrap">
    <!-- Print the page title -->
    <h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
    <!-- Here are our tabs -->
    <nav class="nav-tab-wrapper">
      <a href="?page=koa-suite-plugin" class="nav-tab <?php if($tab===null):?>nav-tab-active<?php endif; ?>">Dashboard</a>
      <a href="?page=koa-suite-plugin&tab=embed" class="nav-tab <?php if($tab==='settings'):?>nav-tab-active<?php endif; ?>">KOA Embed</a>
      <a href="?page=koa-suite-plugin&tab=push" class="nav-tab <?php if($tab==='tools'):?>nav-tab-active<?php endif; ?>">Push Notifications</a>
	  <!--<a href="?page=koa-suite-plugin&tab=analytics" class="nav-tab <?php if($tab==='tools'):?>nav-tab-active<?php endif; ?>">Mobile Analytics</a>
	  <a href="?page=koa-suite-plugin&tab=users" class="nav-tab <?php if($tab==='tools'):?>nav-tab-active<?php endif; ?>">User List</a>-->
	  <a href="?page=koa-suite-plugin&tab=qr" class="nav-tab <?php if($tab==='tools'):?>nav-tab-active<?php endif; ?>">Distribution</a>
    </nav>

    <div class="tab-content">
    <?php switch($tab) :
      case 'embed':
        echo include(WP_PLUGIN_DIR.'/wp-koa-suite/embed/view.php');
        break;
      case 'push':
        echo include(WP_PLUGIN_DIR.'/wp-koa-suite/push/configform/form.php');;
        break;
      case 'qr':
        echo include(WP_PLUGIN_DIR.'/wp-koa-suite/qr/qr-view.php');
        break;
      default:
        echo  include(WP_PLUGIN_DIR.'/wp-koa-suite/dashboard/main.php');
        break;
    endswitch;?>	
    </div>
  </div>
  <?php
}?>
