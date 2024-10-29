<?php

function auth_discuss_widget_posts(){
	global $wpdb;
	$table_name = $wpdb->prefix . "authordiscuss";
	$msg_limit = get_option( 'author_discussion_dashboard_limit' );
	$messages = "SELECT * FROM $table_name ORDER BY id DESC LIMIT $msg_limit";
	$messages = $wpdb->get_results($messages);
	
	foreach($messages as $row){
		
		//get author info here
		$auth_name = get_userdata($row->userid);
		$auth_name = $auth_name->display_name;
		$id = $row->id . '_cont';
		
		$time = strtotime($row->time);
		$time = date('M j, Y g:i A', $time);
		
		$message = stripslashes($row->text);
		$message = apply_filters('the_content', $message);
		
		echo '<div class="activity-block">' .
				'<h4 style="font-size:13px;color:#666;">' . $auth_name . ' (' . $time . ') ' .
				'<a href="' . admin_url("admin.php?page=author_discuss#$id") . '">#</a></h4>' .
			$message . 
			'</div>';
		
	}
}
	
// Create Dashboard Widget Using Hook
function auth_discuss_add_dashboard_widgets() {

	auth_discuss_query(1);
	$authdiscuss_cap = get_option('author_discussion_capability');

	if(current_user_can ( $authdiscuss_cap ) ){
		wp_add_dashboard_widget(
			'auth_discuss_dashboard_widget',         // Widget slug.
			'Author Discussion',         // Title.
			'auth_discuss_dashboard_widget_function' // Display function.
		);	
	}	
}
add_action( 'wp_dashboard_setup', 'auth_discuss_add_dashboard_widgets' );

// Build Quick Message Form
function auth_discuss_quick_form() {
	echo '<div id="auth_discuss_form" style="display:none;">';
	echo '<form method="POST" action="admin-post.php">' .
	'<input type="hidden" name="action" value="author_discuss_message_post" />' .
	'<input type="hidden" name="dashboard" value="1" />' .
	'<textarea name="author_message" id="author_message" class="mceEditor" style="resize:vertical; width:90%;" rows="5" placeholder="What do you want to say?"></textarea>';
	submit_button('Send Message');
	echo '</form></div>';
}

// Create Dashboard Widget Content
function auth_discuss_dashboard_widget_function() {

	// Display latest messages
	echo '<div class="activity-block"><small style="float:right;"><a href="' . admin_url('admin.php?page=author_discuss') . '">Read More...</a></small>' .
	'<h4>Recent Messages</h4></div>';
	auth_discuss_widget_posts();
	echo '<div class="activity-block">';
	echo '<small style="float:right;"><a href="" id="auth_discuss_quick_msg">Show</a></small><h4>Quick Message</h4>';
	auth_discuss_quick_form();
	echo '</div>';
}