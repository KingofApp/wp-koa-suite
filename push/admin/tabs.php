<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<?php include(WP_PLUGIN_DIR.'/koa-suite/push/admin/styles.php'); ?>   
<?php
	function enqueue_push_styles() {
		// Enqueue the Google Fonts stylesheet (Roboto)
		wp_enqueue_style(
			'google-font-roboto', // Handle name for the Google Fonts stylesheet
			'https://fonts.googleapis.com/css2?family=Roboto:wght@300&display=swap', // URL to the Google Fonts stylesheet
			array(), // Dependencies (none in this case)
			null // Version number (null to avoid versioning)
		);

		// Enqueue the Font Awesome 4.7 stylesheet
		wp_enqueue_style(
			'font-awesome-4.7', // Handle name for the Font Awesome stylesheet
			'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css', // URL to the Font Awesome stylesheet
			array(), // Dependencies (none in this case)
			null // Version number (null to avoid versioning)
		);
	}

	add_action('wp_enqueue_scripts', 'enqueue_push_styles');
?>

<?php
// Get active tab from URL, default to 'pushUsers'
$active_tab = isset($_GET['push-tab']) ? sanitize_text_field($_GET['push-tab']) : 'pushUsers';

// Map tab names to tab numbers
$tab_map = [
    'pushUsers'   => 1,
    'pushSender'  => 2,
    'pushSettings'=> 3,
    'pushHistory' => 4,
];
$active_tab_num = isset($tab_map[$active_tab]) ? $tab_map[$active_tab] : 1;
?>

<div id="koapush">
    <div class="tabs">
      <a href="?page=koa-suite&tab=push&push-tab=pushUsers" class="tab <?php echo $active_tab_num === 1 ? 'active' : ''; ?>">
        <img src="https://s3.eu-west-1.amazonaws.com/images.kingofapp.com/wp_koa_suite/icons/user.png" alt="">
        <span>User list</span>
        <div class="alerta"></div>
      </a>
      <a href="?page=koa-suite&tab=push&push-tab=pushSender" class="tab <?php echo $active_tab_num === 2 ? 'active' : ''; ?>">
        <img src="https://s3.eu-west-1.amazonaws.com/images.kingofapp.com/wp_koa_suite/icons/notification.png" alt="">
        <span>Push sender</span>
        <div class="alerta"></div>
      </a>
      <a href="?page=koa-suite&tab=push&push-tab=pushSettings" class="tab <?php echo $active_tab_num === 3 ? 'active' : ''; ?>">
        <img src="https://s3.eu-west-1.amazonaws.com/images.kingofapp.com/wp_koa_suite/icons/settings.png" alt="">
        <span>Setings</span>
        <div class="alerta"></div>
      </a>
      <a href="?page=koa-suite&tab=push&push-tab=pushHistory" class="tab <?php echo $active_tab_num === 4 ? 'active' : ''; ?>">
        <img src="https://s3.eu-west-1.amazonaws.com/images.kingofapp.com/wp_koa_suite/icons/settings.png" alt="">
        <span>Push History</span>
        <div class="alerta"></div>
      </a>
    </div>

  <!-- users list -->
  <div class="tabContent" id="tab1" style="<?php echo $active_tab_num === 1 ? 'display:flex;' : 'display:none;'; ?>">
    <?php include(WP_PLUGIN_DIR.'/koa-suite/push/admin/pushUsers.php'); ?>   
  </div>
    
  <!-- send push -->
  <div class="tabContent" id="tab2" style="<?php echo $active_tab_num === 2 ? 'display:flex;' : 'display:none;'; ?>">
    <?php include(WP_PLUGIN_DIR.'/koa-suite/push/admin/pushSender.php'); ?>   
  </div>
    
  <!-- config panel -->
  <div class="tabContent" id="tab3" style="<?php echo $active_tab_num === 3 ? 'display:flex;' : 'display:none;'; ?>">
    <?php include(WP_PLUGIN_DIR.'/koa-suite/push/admin/pushSettings.php'); ?>   
  </div>

  <!-- push history -->
  <div class="tabContent" id="tab4" style="<?php echo $active_tab_num === 4 ? 'display:flex;' : 'display:none;'; ?>">
    <?php include(WP_PLUGIN_DIR.'/koa-suite/push/admin/pushHistory.php'); ?>   
  </div>
</div>

<!-- tabs manager -->
<script>	
	//let auth =  '<?php echo get_option('koa_push_auth'); ?>';
	
	function openPushSender(user_ID, device_code){
		//save data
		window.koa_push_single_user_id = user_ID;
		window.koa_push_single_user_code = device_code;
		goTab(2, false, true);
	}
	
	let tabs = Array.from(document.querySelectorAll("#koapush .tab"));
	let tabContent = Array.from(document.querySelectorAll("#koapush .tabContent"));
	function hideTabs(){
	tabContent.map(function(tab) {
		return tab.style.display = "none";
	});
	tabs.map(function(tab) {
		tab.querySelector(".alerta").style.display= "none";
		return tab.classList.remove("active");
	});
	
	}

	function goTab(tabid, tabSelector, activeTab){
		let selector = `.tabContent#tab${tabid}`;
		let tab = document.querySelector(selector);
		
		if(tab.classList.contains("disabled") && !activeTab) return;
			
		hideTabs();
		console.log(tabSelector)
		tabSelector = !tabSelector?tabs[tabid -1]: tabSelector;
		tabSelector.classList.add("active");
		
		
		tab.style.display = "flex";
		//document.querySelector(selector).style.display = "flex";
	}
		
	function setAlert(tabId){
		tabs[tabId - 1].querySelector(".alerta").style.display = "block";
	}

	//if(!auth || auth === '')setAlert(3);

</script>