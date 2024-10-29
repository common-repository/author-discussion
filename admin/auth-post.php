<?php

function get_capable_roles(){
	$author_discussion_capability = get_option('author_discussion_capability');
	
	if($author_discussion_capability == 'publish_posts'){
		// author and higher
		$users = get_users('who=authors');
		return $users;
		
	}elseif($author_discussion_capability == 'moderate_comments'){
		// editor and higher
		$argsa = array( 'role' => 'administrator' );
		$a = get_users( $argsa );
		
		$argsb = array ('role' => 'editor' );
		$b = get_users( $argsb );
		
		$users = array_merge( $a, $b);
		return $users;
		
	}elseif($author_discussion_capability == 'manage_options'){
		// admins
		$users = get_users('role=administrator');
		return $users;
	}
}

function author_discuss_process_message(){
	global $wpdb;
	$table_name = $wpdb->prefix . "authordiscuss";
	
	if(isset($_POST['author_message'])){
		// Grab Contents & Post
		//$message = sanitize_text_field($_POST['author_message']);
		$message = $_POST['author_message'];

		$userid = get_current_user_id();
		$time = date("Y-m-d H:i:s", strtotime('-5 hours'));
		
		//POST
		$wpdb->insert(
			$table_name,
			array( 
				'userid' => $userid,
				'time' => $time,
				'text' => $message
			),
			array( '%d', '%s', '%s')
		);
		
		// after message post, notify users
		// FUTURE UPDATE: ALTER TABLE INSTEAD OF THIS? 
		// ADD HOOKS TO INSERT OPEN STRING FOR NEW USERS/PROMOTION
		$users = get_capable_roles();
		foreach($users as $user){
			 
			if($user->ID != get_current_user_id()){
			
				$value = get_user_meta($user->ID, 'author_discussion_unread_posts', true);
				
				if($value)
					$value = ++$value;
				else
					$value = 1;
				update_user_meta($user->ID, 'author_discussion_unread_posts', $value);
			}
		}
	}
	if(isset($_POST['dashboard']) AND ($_POST['dashboard'] == 1)){
		wp_redirect( admin_url( 'index.php' ) );
	}else{
		wp_redirect( admin_url( 'admin.php?page=author_discuss&m=1' ) );
	}
	exit;
}




// Prime the script to send messageid to be deleted
add_action( 'admin_footer', 'ajax_auth_discuss_delete_message' );
function ajax_auth_discuss_delete_message() {
	?>
	<script type="text/javascript" >
	jQuery('.auth_message').hover(function($) {
		// show delete link
		var messageid = event.target.id.replace('_cont','');
		jQuery('#'+messageid).show(0);
	},
	// hide del link on exit
	function($) {
		var messageid = event.target.id.replace('_cont','');
		jQuery('#'+messageid).hide(0);
	});
	
	jQuery('.del_message').click(function($) {

		event.preventDefault();
		var messageid = event.target.id;
		var data = {
			action: 'del_message',
			messageid: messageid
		};

		jQuery.post(ajaxurl, data, function(response) {
			if(response != ''){
				alert(response);
			}else{
				jQuery('#'+messageid+'_cont').hide();
			}
		});
	});
	</script>
	<?php
}

// Actually delete the post
add_action( 'wp_ajax_del_message', 'del_message_callback' );
function del_message_callback(){
	global $wpdb;
	$table_name = $wpdb->prefix . "authordiscuss";
	
	$messageid = $_POST['messageid'];
	$query = $wpdb->get_row("SELECT * FROM $table_name WHERE id=$messageid", ARRAY_A);
	
	if($query['userid'] == get_current_user_id()){
		$wpdb->delete(
			$table_name,
			array(
				'id' => $messageid
			));
	}else{
		echo 'Sorry, something went wrong!';
	}
	
	die();
}

?>