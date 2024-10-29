<?php
/*
    Plugin Name: Author Discussion
    Plugin URI: http://wordpress.org/plugins/author-discussion/
    Description: This plugin will create a page where authors, editors and administrators can communicate with their team.
    Version: 0.2.2
    Author: Brandon White
    Author URI: http://profiles.wordpress.org/aschx/
    License: GPL2
 
    Copyright 2013  BRANDON WHITE  (email : bwhite@techgroove.net)
    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.
    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.
    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/
global $author_discussion_version;
$author_discussion_version = '0.2.2';
global $author_discussion_query_count;
$author_discussion_query_count = 0;

include 'admin/options.php';
include 'admin/auth-post.php';
include 'admin/toolbar.php';
include 'admin/install.php';
include 'widgets/dashboard.php';
include 'admin/admin.php';
wp_register_style( 'auth_discuss_style', plugins_url( 'author-discussion/inc/style.css' ) );
wp_enqueue_style( 'auth_discuss_style' );
wp_register_script( 'auth_discuss_scripts', plugins_url( 'author-discussion/inc/scripts.js' ) );
wp_enqueue_script( 'auth_discuss_scripts' );

add_action( 'admin_post_author_discuss_message_post','author_discuss_process_message' );
add_action( 'admin_post_author_discuss_message_delete','author_discuss_delete_message' );

function auth_discuss_query( $increment ){
	global $author_discussion_query_count;
	$author_discussion_query_count += $increment;
}

// /////////////////////////////////////
// ////////  UNINSTALL PLUGIN  /////////
// /////////////////////////////////////
include 'uninstall.php';
register_deactivation_hook(__FILE__, 'author_discuss_uninstall');

// USER DELETE - REMOVE MESSAGES
add_action( 'deleted_user', 'auth_discuss_del_user' );
function auth_discuss_del_user( $userid ){
	global $wpdb;
	$table_name = $wpdb->prefix . "authordiscuss";
	
	$wpdb->delete( 
		$table_name,
		array( 
			'userid' => $userid
		)
	);
	auth_discuss_query(1);
}

// ////////////////////////////////////
// ////////  CHECK IF UPDATE  /////////
// ////////////////////////////////////
function authordiscussion_update_db_check() {
    global $author_discussion_version;
	auth_discuss_query(1);
	
    if (get_option( 'author_discussion_version' ) != $author_discussion_version) {
        authordiscussion_install();
    }
}

add_action( 'plugins_loaded', 'authordiscussion_update_db_check' );


// ////////////////////////////////////
// ////////  DASHBOARD MENUS  /////////
// ////////////////////////////////////
add_action( 'admin_menu', 'register_author_discussion_menu' );

function register_author_discussion_menu(){
	auth_discuss_query(1);
	$menu_capability = get_option('author_discussion_capability');
    add_menu_page( 'Author Discussion', 'Discussion', $menu_capability, 'author_discuss', 'author_discuss_build_page', plugins_url( 'author-discussion/images/icon.png' ), 999 );
	
	//create submenu items
	add_submenu_page( 'author_discuss', 'Author Discussion', 'Author Discussion', $menu_capability, 'author_discuss' );
	add_submenu_page( 'author_discuss', 'Author Discussion Settings', 'Settings', 'manage_options', 'author_discuss_settings', 'author_discuss_settings_page' );
}


// Add settings link on plugin page
$plugin = plugin_basename(__FILE__); 
add_filter("plugin_action_links_$plugin", 'author_discussion_settings_link' );
function author_discussion_settings_link($links) { 
  $settings_link = '<a href="options-general.php?page=author-discussion/admin/options.php">Settings</a>'; 
  array_unshift($links, $settings_link); 
  return $links; 
}

// /////////////////////////////////////
// ////////  DEFINE FUNCTIONS  /////////
// /////////////////////////////////////

// build messages
function auth_discuss_get_messages(){
	auth_discuss_query(3);
	$authdiscuss_cap = get_option('author_discussion_capability');
	$page_message_limit = get_option('author_discussion_msg_limit');
	global $wpdb;
	$table_name = $wpdb->prefix . "authordiscuss";
	$messages = "SELECT * FROM $table_name ORDER BY id DESC LIMIT $page_message_limit";
	$messages = $wpdb->get_results($messages);
	
	foreach($messages as $row){
		
		//get author info here
		$auth_name = get_userdata($row->userid);
		$auth_name = $auth_name->display_name;
		
		$time = strtotime($row->time);
		$time = date('M j, Y g:i A', $time);
		
		$message = stripslashes($row->text);
		$message = apply_filters('the_content', $message);
		
		echo '<div class="auth_message" id="' . $row->id . '_cont">';
		// show delete link
		echo '<div class="author">' . 
			$auth_name . '<br />' . 
			$time;
		if($row->userid == get_current_user_id()){
			echo ' <a href="" class="del_message" id="' . 
			$row->id . '">[X]</a>';
		}
		echo '</div>';
		echo $message;		
		echo '</div>';
		
	}
}

// retrieve capability titles
function auth_discuss_capability(){
	auth_discuss_query(1);
	$authdiscuss_cap = get_option('author_discussion_capability');
	
	if($authdiscuss_cap == 'manage_options'){
		$authdiscuss_cap = 'Administrator';
	}elseif($authdiscuss_cap == 'moderate_comments'){
		$authdiscuss_cap = 'Editor and Higher';
	}elseif($authdiscuss_cap == 'publish_posts'){
		$authdiscuss_cap = 'Author and Higher';
	}
	
	return $authdiscuss_cap;
}

// build rightcol
function auth_discuss_rightcol(){
	echo '
	<div class="right">
		<p>Welcome to the Author Discussion page. This is a public page
		for anyone granted access. Right now <b><i>' . auth_discuss_capability() . '</i></b>
		is granted access to this system.</p>
		
		<h4>What\'s new in 0.2.0?</h4>
			<ul class="plans_list">
				<li>More modular back-end - Better Performance</li>
				<li>Dashboard widget added for users with required capability</li>
				<li>Updated settings page location for administrators</li>
			</ul>
		
		<h4>There are future plans to do the following: </h4>
			<ul class="plans_list">
				<li>Edit Link for message authors</li>
				<li>Paginate the messages</li>
			</ul>
		<p>These will come as soon as possible. Thanks for your patience!</p>
	</div>';
}

// build footer
function auth_discuss_footer(){
	global $author_discussion_version;
	global $author_discussion_query_count;
	if($author_discussion_query_count > 1)
		$query = 'queries have';
	else
		$query = 'query has';
	echo '<div class="footer">' .
		'You are currently running plugin version: ' . $author_discussion_version . '<br />' .
		'A total of <strong>' . $author_discussion_query_count . '</strong> database '. $query . ' been run.<br />' .
		'Developed by <a href="http://profiles.wordpress.org/aschx">Brandon White</a><br />' .
		'[Sponsored by <a href="http://techgroove.net">TechGroove.Net</a>]' .
		'</div>';
}

// build input form
function auth_discuss_form(){
	auth_discuss_query(1);
	// settings
	$editor_settings = array(
	'media_buttons' => false,
	'textarea_rows' => 6,
	'tinymce' => array(
		'theme_advanced_buttons1' => 'bold,italic,underline,|,' .
			'bullist,blockquote,|,justifyleft,justifycenter' .
			',justifyright,justifyfull,|,link,unlink',
		'theme_advanced_buttons2' => ''),
	'quicktags' => false
	);
	
	// build form
	echo '
	<form method="post" action="admin-post.php">
		<input type="hidden" name="action" value="author_discuss_message_post" />';
		wp_editor('','author_message', $editor_settings);
		submit_button('Send Message');
	echo '</form>';
}

function auth_discuss_purge_views(){
	auth_discuss_query(1);
	global $wpdb;
	$query = $wpdb->prepare("UPDATE `wp_mainblogusermeta`
		SET `meta_value`=%d
		WHERE `meta_key`= %s", 0, 'author_discussion_unread_posts'
	);
	$wpdb->query($query);
}
