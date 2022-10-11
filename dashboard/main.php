<style>
	#koa_suite_dashboard{
		display: flex;
    	flex-direction: column;
    	align-content: center;
    	justify-content: center;
    	align-items: center;
	}
	
	#koa_suite_dashboard .top{
		padding: 20px;
    	background: #aae9d5;
    	margin: 20px;
    	font-size: 28px;
    	font-weight: bold;
		border-radius: 10px;
   		box-shadow: 0px 0px 20px 0px rgb(0 0 0 / 20%);
	}
	
	#koa_suite_dashboard .top h4 {
    	font-weight: bold !important;
	}
	
	#koa_suite_dashboard .top h1{
		display: block; 
		font-size: 2em; 
		margin-top: 0.67em; 
		margin-bottom: 0.67em; 
		margin-left: 0; 
		margin-right: 0; 
		font-weight: bold;
	}
	
	#koa_suite_dashboard .link{
		width: 90%;
    	display: flex;
    	flex-wrap: nowrap;
    	flex-direction: column;
    	align-content: center;
    	justify-content: center;
    	align-items: center;
    	background: white;
    	padding: 10px;
    	border-radius: 10px;
    	box-shadow: 0px 0px 20px 0px rgb(0 0 0 / 20%);
	}
	
	#koa_suite_dashboard .link a{
		display: flex;
    	flex-direction: column;
    	flex-wrap: nowrap;
    	align-content: center;
    	justify-content: center;
    	align-items: center;
		text-decoration: none;
	}
	
	#koa_suite_dashboard .link img{
		max-width: 100%;
	}
	
	#koa_suite_dashboard .link button{
		padding: 20px;
    	color: #f0f0f1;
    	background: #4bc59d;
    	border-radius: 10px;
    	border: none;
   		font-size: x-large;
    	text-decoration: none;
		box-shadow: 0px 0px 20px 0px rgb(0 0 0 / 20%);
	}
	
</style>
<div id="koa_suite_dashboard">
	<div class="top">
		<h4>¡Bienvenido!</h4>

		<h1>KOA SUITE es un plugin para gestionar </br> tu app desde tu panel de WordPress</h1>

		<p>Para gestionar este plugin es necesario reguir los pasos de configuración y tener tu app creada con el plugin 
			www.kingofapp.com
		</p>

	</div>

	<div class="link">
		<a href="https://kingofapp.com/es/constructor-de-apps/" target="_blank">
			<img src="https://s3.eu-west-1.amazonaws.com/images.kingofapp.com/wp_koa_suite/dashboard/dashboard.png" alt="">
			<button><span>Acceder a King of App el constructor de apps</span></button>
		</a>
	</div>
</div>