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