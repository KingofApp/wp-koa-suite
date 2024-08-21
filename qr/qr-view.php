<div class="wrapQR">
		<style>
			.wrapQR{
				display: flex;
				justify-content: center;
				flex-direction: column;
				align-items: center;
			}
			.wrapQR td{
				width:720px
			}
			
			.wrapQR .submit{
    			display: flex;
    			justify-content: center;
			}
			.wrapQR .submit input{
				border: 0px;
				background: #6ae7be;
				color: white;
				font-size: medium;
				font-weight: 500;
				border-radius: 9px;
				padding: 5px 13px;
				text-align: center;
				line-height: 1;
			}
			.wrapQR button{
				border: 0px;
				background: #6ae7be;
				color: white;
				font-size: medium;
				font-weight: 500;
				border-radius: 9px;
				padding: 5px 13px;
				text-align: center;
				margin-top: 20px;
			}
			.wrapQR #qrArea{
				width: 550px;
				display: flex;
				flex-direction: column;
				align-content: space-around;
				justify-content: center;
				align-items: center;
			}

		</style>
		<h1>King Of App QR Generator</h1>
		<a href="https://kingofapp.com/" target="_blank">
  			<img src="https://s3-eu-west-1.amazonaws.com/kingofapp.es/logo.png" style="max-width: 100%" alt="king of app">
		</a>
		<form method="post" action="options.php">
			<?php settings_fields( 'koa-qr-settings-group' ); ?>
			<?php do_settings_sections( 'koa-qr-settings-group' ); ?>
			<table class="form-table">
				<tr valign="top">
					<th scope="row">Android Store URL</th>
					<td><input type="text" name="koa_qr_android" id="koa_qr_android" value="<?php echo get_option('koa_qr_android'); ?>" style="width:670px;">
					    <i style="display: none;" class="fas fa-check icon" id="correct"></i>
           	            <i style="display: none;" class="fas fa-times icon" id="wrong"></i></td>
				</tr>
				<tr valign="top">
					<th scope="row">Apple Store URL</th>
					<td><input type="text" name="koa_qr_ios" id="koa_qr_ios" value="<?php echo get_option('koa_qr_ios'); ?>" style="width:670px;">
					    <i style="display: none;" class="fas fa-check icon" id="correct"></i>
           	            <i style="display: none;" class="fas fa-times icon" id="wrong"></i></td>
				</tr>          
			</table>
			<?php submit_button(); ?>
		</form>
		<div id="qrArea"></div>
		
		<script>
			$(document).ready(function () {

				$("#koa_qr_ios").on("input", function () {
					valueControl($(this), "https://apps.apple.com/")
				})
				$("#koa_qr_android").on("input", function () {
					valueControl($(this), "https://play.google.com/store/apps/")
				})
				
				function valueControl(element, contains) {
					console.log($(element).parent().children()[1]);
					if ($(element).val() != "") {
						if ($(element).val().includes(contains)) {
                       		$(element).parent().children('#wrong').fadeOut();
                       		$(element).parent().children('#correct').fadeIn();
						} else {
							$(element).parent().children('#wrong').fadeIn();
                       		$(element).parent().children('#correct').fadeOut();
							
						}
					}
					else {
						$(element).parent().children('#wrong').fadeIn();
                       	$(element).parent().children('#correct').fadeOut();
					}
				} 
			})
			let qrcode = new QRCode(document.getElementById("qrArea"), { width: 300, height: 300 }); 
			function makeCode() {
				let elText = "<?php echo site_url().'/wp-json/koa/v1/qr'?>"; 
				if (!elText) { 
					alert("Input a text"); elText.focus(); return; 
				}
				qrcode.makeCode(elText);
				
				/*Download*/
				setTimeout(()=>{
					let a = document.createElement('a');
					let btn = document.createElement('button');
					btn.setAttribute('id', 'btndw');
					btn.innerHTML = "Download QR";
					a.setAttribute('id', 'adownload');
					a.setAttribute('href', document.getElementById('qrArea').getElementsByTagName('img')[0].getAttribute('src'));
					a.setAttribute('download', 'myQR.jpg');
					document.getElementById('qrArea').appendChild(a);
					document.getElementById('adownload').appendChild(btn);
				},50);
			}
			makeCode(); 

		</script>
    </div>
<?php
	
function enqueue_koa_qr_scripts() {
    // Enqueue jQuery with integrity, crossorigin, and referrerpolicy attributes
    wp_enqueue_script(
        'jquery-cdn', // Handle name for the script
        'https://cdnjs.cloudflare.com/ajax/libs/jquery/1.8.3/jquery.min.js', // URL to the script
        array(), // Dependencies (none in this case since jQuery is loaded from CDN)
        null, // Version number (null to avoid versioning)
        true // Load in footer (true) or header (false)
    );

    // Add integrity, crossorigin, and referrerpolicy attributes to the script tag
    wp_script_add_data('jquery-cdn', 'integrity', 'sha512-J9QfbPuFlqGD2CYVCa6zn8/7PEgZnGpM5qtFOBZgwujjDnG5w5Fjx46YzqvIh/ORstcj7luStvvIHkisQi5SKw==');
    wp_script_add_data('jquery-cdn', 'crossorigin', 'anonymous');
    wp_script_add_data('jquery-cdn', 'referrerpolicy', 'no-referrer');

    // Enqueue the QR code script
    wp_enqueue_script(
        'qrcode-script', // Handle name for the script
        'https://onelinkqr.nijat.net/js/qrcode.js', // URL to the script
        array('jquery-cdn'), // Dependencies (ensure jQuery is loaded before this script)
        null, // Version number (null to avoid versioning)
        true // Load in footer (true) or header (false)
    );
}

function enqueue_koa_qr_style() {
    // Enqueue the Font Awesome stylesheet
    wp_enqueue_style(
        'font-awesome', // Handle name for the stylesheet
        'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css', // URL to the stylesheet
        array(), // Dependencies (none in this case)
        null // Version number (null to avoid versioning)
    );

    // Add integrity, crossorigin, and referrerpolicy attributes to the stylesheet link tag
    wp_style_add_data('font-awesome', 'integrity', 'sha512-1ycn6IcaQQ40/MKBW2W4Rhis/DbILU74C1vSrLJxCq57o941Ym01SwNsOMqvEBFlcgUa6xLiPY/NS5R+E6ztJQ==');
    wp_style_add_data('font-awesome', 'crossorigin', 'anonymous');
    wp_style_add_data('font-awesome', 'referrerpolicy', 'no-referrer');
}

add_action('wp_enqueue_scripts', 'enqueue_koa_qr_style');


add_action( 'wp_enqueue_scripts', 'enqueue_koa_qr_scripts' );