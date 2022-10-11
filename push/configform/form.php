<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

<form method="post" action="options.php" >
<?php settings_fields( 'koa-push-settings-group' ); ?>
<?php do_settings_sections( 'koa-push-settings-group' ); ?>
	
<div id="koapush">
	<div class="tabs">
	  <div class="tab active" onclick="goTab(1,this)">
		<img src="https://s3.eu-west-1.amazonaws.com/images.kingofapp.com/wp_koa_suite/icons/user.png" alt="">
		<span>User list</span>
		<div class="alerta"></div>
	  </div>
	  <div class="tab disabled" onclick="goTab(2, this)" >
		<img src="https://s3.eu-west-1.amazonaws.com/images.kingofapp.com/wp_koa_suite/icons/notification.png" alt="">
		<span>Push sender</span>
		<div class="alerta"></div>
	  </div>
	  <div class="tab" onclick="goTab(3, this)">
		<img src="https://s3.eu-west-1.amazonaws.com/images.kingofapp.com/wp_koa_suite/icons/settings.png" alt="">
		<span>Setings</span>
		<div class="alerta"></div>
	  </div>
	</div>
  
  <!-- users list -->
  <div class="tabContent" id="tab1">
		<div class="searchBar">
			<button type="submit"><i class="fa fa-search"></i></button>
      		<input type="text" placeholder="Search.."  name="koa_push_search" value="<?php echo get_option('koa_push_search'); ?>">
	  </div>
	<table>
	  <tr>
		<th><input type="checkbox"/></th>
		<th>Name</th>
		<th>Email</th>
		<th>Send push</th>
		  <th>code</th>
	  </tr>
	  <?php 
	  $users = get_users();
		foreach($users as $user){
			/*echo get_option('koa_push_search');*/
			if(get_option('koa_push_search') || get_option('koa_push_search') !== ""){
				if( strpos($user->display_name, get_option('koa_push_search') ) !== false || strpos($user->user_email, get_option('koa_push_search') ) !== false ){
					$showThis = "flex";
				}else{
					$showThis = "none";
				}
			}
			
	  ?>
		
	  <tr style="display: <?php echo $showThis; ?> ">
		<td> <input type="checkbox"/> </td>
		<td><?php echo '<span>' . esc_html( $user->display_name  ) . '</span>'; ?></td>
		<td><?php echo '<span>' . esc_html( $user->user_email ) . '</span>'; ?></td>
		<td><?php if (get_user_meta( $user->ID, 'koa_push_code', true  )){ ?>
		  			 <button class="btn"  type="button"  onclick="openPushSender(' <?php echo get_user_meta( $user->ID, 'koa_push_code', true )?> ')">Send</button>
					<?php
	  			  }else{
		  			echo "<button  type='button' class='btn' disabled>Send</button>";
	  			  }
			?></td>
		  <td><?php echo get_user_meta( $user->ID, 'koa_push_code', true  ); ?></td>
	  </tr>
	 <?php } ?>
	</table>
  </div>
	
  <!-- send push -->
  <div class="tabContent disabled" id="tab2" >
	<div id="pushStatus">
		<span class="success">Push send success</span>
		<span class="error">Push send Error: </span>
	</div>
   <div id="pushSender">
	  <label for="koa-push">Notification title:</label>
	  <input type="text" id="title">
	  <label for="koa-push">Notification text:</label>
	  <input type="text" id="body">
	  slect
	  <button class="btn" type="button" onclick="sendPush()">Send</button>
	</div>
  </div>
	
  <!-- config panel -->
  <div class="tabContent" id="tab3">
   <div id="setingsPage">
	   	<span>Firebase Server Key</span>
		<textarea type="text" name="koa_push_auth" required value="<?php echo get_option('koa_push_auth'); ?>" ><?php echo get_option('koa_push_auth'); ?></textarea>
		<button type="submit" class="btn">Save</button>
   </div>
  </div>
</div>
</form>

<style>
	#koapush{
		width: calc(100% + 20px);
    	margin-left: -20px;
		background: #e9e9e9;
		height: 100vh;
	}
	
	
	/*user list*/
	.searchBar{
		width: 97%;
    	display: flex;
    	margin-top: 30px;
    	margin-bottom: 67px;
	}


	#koapush input[type=text] {
		padding: 6px;
		margin-top: 8px;
		font-size: 17px;
		border: none;
		border-left: none;
		width: 100%;
		margin-left: 0px;
		border-radius: 0px;
	}

	.searchBar button {
	    padding: 6px 10px;
		margin-top: 8px;
		background: #fff;
		color: gray;
		font-size: 17px;
		border: none;
		cursor: pointer;
	}
	
	
	table{
		width: 100%;
	}
	
	tr {
    	display: flex;
    	padding: 10px;
    	background: white;
    	width: 95%;
    	margin-bottom: 4px;
	}
	
	table tbody{
		width: 100%;
		display: flex;
		flex-direction: column;
		align-items: center;
		justify-content: space-around;
		flex-wrap: wrap;
	}
	
	th, td {
		width: calc(100% / 5);
		display: flex;
		flex-direction: row;
		justify-content: flex-start;
		align-items: center;
		align-content: center;
		color: #99a6b6;
	}
	
	#koapush .btn{
		border: 0px;
		background: #6ae7be;
		color: white;
		font-size: medium;
		font-weight: 500;
		border-radius: 9px;
		padding: 5px 13px;
		text-align: center;
	}
	
	#koapush .btn:disabled {
		background: gainsboro;
	}
	
	
	tr th:first-child {
    	width: 50px;
	}
	
	tr td:first-child {
    	width: 50px;
	}
	
	table input[type=checkbox], input[type=radio] {
		border: 2px solid #99aed0;
	}
	
	.tabs{
	  	width: 100%;
	  	height: 60px;
	  	background: white;

	  	border-bottom: 2px solid #00000042;

	  	display: flex;
	  	flex-direction: row;
	  	justify-content: center;
	  	align-items: center;
	}

	.tab{
		color: #395272;
		font-size: 18px;
		font-family: 'Roboto', sans-serif;
		font-weight: bold;
		display: flex;
		flex-direction: row;
		flex-wrap: nowrap;
		height: 100%;
		align-content: center;
		justify-content: center;
		align-items: center;
		padding: 0 10px;
		position: relative;	
	}

	.tab img{
		max-height: 100%;
		height: 36%;
		margin: 0 10px;
		filter: grayscale(100%) contrast(0);
	}

	.tab.active{
	  	color: #62e9bd;
	}

	.tab.active img{
		max-height: 100%;
		height: 36%;
		margin: 0 10px;
		filter: none;
	}
	
	.tab .alerta{
		display:none;
		width: 8px;
		height: 8px;
		background: red;
		border-radius: 50%;
		position: absolute;
		top: calc(50% - 14px);
		right: 0;
	}

	.tabContent{
		background: #f5f6f8;
		width: 100%;
		height: 100%;
		display: none;
		flex-direction: column;
		flex-wrap: nowrap;
		align-content: center;
		justify-content: flex-start;
		align-items: center;
	}
	
	#tab1{
	  	display:flex;
	}
	
	#pushSender{
		margin-top: 100px;
	}
	
	#pushSender input{
		margin-bottom: 20px;
	}
	#pushSender .btn{	
		font-size: 23px;
    	font-weight: 700;
	}
	
	#pushSender *{
		font-family: 'Roboto', sans-serif;
		font-size: medium;
		font-weight: 600;
		color: #99a6bd;
	}
	
	#pushStatus{
		width: 100%;
	}
	
	#pushStatus span{
		display: none;
	}
	
	#pushStatus span.error {
		background: #ed5c5c;
		padding: 20px 10px;
		text-align: center;
		justify-content: center;
		width: 100%;
		color: white;
		font-size: x-large;
		font-family: 'Roboto', sans-serif;
		font-weight: 800;
	}
	
	#pushStatus span.success {
		background: #6ae7be;
		padding: 20px 10px;
		text-align: center;
		justify-content: center;
		width: 100%;
		color: white;
		font-size: x-large;
		font-family: 'Roboto', sans-serif;
		font-weight: 800;
	}
	
	#setingsPage{
		display: flex;
		flex-direction: column;
		width: 100%;
		justify-content: center;
		align-items: center;
	}
	
	#setingsPage span{
		color: #395272;
		font-size: 18px;
		font-family: 'Roboto', sans-serif;
		font-weight: bold;
		margin: 50px;
	}
	
	#setingsPage textarea{
		height: 100px;
		padding: 6px;
		margin-top: 8px;
		font-size: 17px;
		border: none;
		border-left: none;
		width: 95%;
		margin-left: 0px;
		border-radius: 0px;
		text-align: center;
	}
	
	#setingsPage .btn{
		margin-top: 20px;
	}

</style>
<script>
	let to = "";
	let auth =  '<?php echo get_option('koa_push_auth'); ?>';
	
	function openPushSender(push){
		//save data
		to = push;
		goTab(2, false, true);
	}
	
	function sendPush(){
		var myHeaders = new Headers();
			myHeaders.append("Authorization", "key=" + auth);
			myHeaders.append("Content-Type", "application/json");

			var raw = JSON.stringify({
			  "to": to.trim(),
			  "notification": {
				"body": document.getElementById("body").value,
				"title": document.getElementById("title").value
			  }
			});

			var requestOptions = {
			  method: 'POST',
			  headers: myHeaders,
			  body: raw,
			  redirect: 'follow'
			};
			console.log("myHeaders", myHeaders);
			console.log("requestOptions", requestOptions);
		
			fetch("https://fcm.googleapis.com/fcm/send", requestOptions)
			  .then(response => response.text())
			  .then(result => {
								result = JSON.parse(result);
								console.log(result);
							   if(result.success > 0){
								   document.querySelector("#pushStatus .success").style.display="flex";
							   }else{
								 document.querySelector("#pushStatus .error").innerHTML += result.results[0].error;
							  	 document.querySelector("#pushStatus .error").style.display="flex";
							   }
				
								setTimeout(function(){ goTab(1); }, 3000);
							  
							  })
			  .catch(error => { console.log('error', error);
							   document.querySelector("#pushStatus .error").innerHTML += error;
							   document.querySelector("#pushStatus .error").style.display="flex";
							  });
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

if(!auth || auth === '')setAlert(3);

</script>
