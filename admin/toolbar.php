<?php

// /////////////////////////////////////
// ////////  ADD TOOLBAR LINK  /////////
// /////////////////////////////////////

add_action( 'admin_bar_menu', 'toolbar_link_author_discussion', 999 );

function toolbar_link_author_discussion( $wp_admin_bar ) {

	global $user_posts_unread;
	$user_posts_unread = get_user_meta(get_current_user_id(), 'author_discussion_unread_posts', true);
	
	global $user_posts_unread;
	
	if($user_posts_unread == 1){ 
			$author_discuss_node_title = 'Message';
		}else{
			$author_discuss_node_title = 'Messages';
		}
	
	$author_discuss_node_title = $user_posts_unread . ' New ' . $author_discuss_node_title ;
	
	$args = array(
		'id'    => 'author_discussion',
		'title' => $author_discuss_node_title,
		'href'  => admin_url('admin.php?page=author_discuss')
	);
	//http://techgroove.net/blog/wp-admin/
	if($user_posts_unread > 0){
		$wp_admin_bar->add_node( $args );
	}
}