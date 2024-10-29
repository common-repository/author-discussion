<?php

function author_discuss_uninstall(){

	// Drop Messages Table
	if(get_option('author_discussion_drop_table')==1){
		global $wpdb;
		$table_name = $wpdb->prefix . "authordiscuss"; 
		$sql = "DROP TABLE $table_name";
		
		$wpdb->query($sql);	
	}
	
	delete_option('author_discussion_version');
}

?>