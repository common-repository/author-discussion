<?php
function author_discuss_build_page(){
		// flush user's unread post count
		update_user_meta(get_current_user_id(), 'author_discussion_unread_posts', 0);
	?>
	<div class="maincontent">
		<h1>Welcome to the "Author Discussion" page!</h1>
		
		<?php if(isset( $_GET['m'] ) && $_GET['m'] == '1' ){ ?>
		   <div id="message" class="updated auth_discuss_fade"><p><strong>You have successfully posted the message. Click to hide.</strong></p></div><br />
		<?php } ?>
		
		<div class="left">
		
		<?php auth_discuss_form() ?>
		
		<?php
		// build message list
		auth_discuss_get_messages();
		
		?>
		
		</div> <!-- end left col -->

		<?php 
		auth_discuss_rightcol();
		auth_discuss_footer();
		?>
		
	</div>
<?php
}
