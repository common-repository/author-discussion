<?php

// ////////////////////////////////////
// ////////  INSTALL PLUG-IN  /////////
// ////////////////////////////////////

register_activation_hook( __FILE__, 'authordiscussion_install' );

function authordiscussion_install(){
   global $wpdb;
   global $author_discussion_version;

   $table_name = $wpdb->prefix . "authordiscuss"; 
   $sql = "CREATE TABLE $table_name (
		  id mediumint(9) NOT NULL AUTO_INCREMENT,
		  time datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
		  userid mediumint(9) NOT NULL,
		  text text NOT NULL,
		  UNIQUE KEY id (id)
		  );";

	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
	dbDelta( $sql );
	
	add_option( 'author_discussion_version', $author_discussion_version);
	add_option( 'author_discussion_drop_table', '1');
	add_option( 'author_discussion_capability', 'publish_posts' );
	add_option( 'author_discussion_msg_limit', '5');
	add_option( 'author_discussion_dashboard_limit', '3' );
	
}