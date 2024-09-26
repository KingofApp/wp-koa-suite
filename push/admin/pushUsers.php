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
		  			 <button class="btn"  type="button"  onclick="openPushSender(' <?php echo $user->ID; ?> ')">Send</button>
					<?php
	  			  }else{
		  			echo "<button  type='button' class='btn' disabled>Send</button>";
	  			  }
			?></td>
		  <td><?php echo get_user_meta( $user->ID, 'koa_push_code', true  ); ?></td>
	  </tr>
	 <?php } ?>
</table>

