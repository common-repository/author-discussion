<?php
//call register settings function	
add_action( 'admin_init', 'register_author_discussion_settings' );

function register_author_discussion_settings() {
	//register our settings
	register_setting( 'author-discuss-settings-group', 'author_discussion_drop_table' );
	register_setting( 'author-discuss-settings-group', 'author_discussion_capability' );
	register_setting( 'author-discuss-settings-group', 'author_discussion_msg_limit' );
	register_setting( 'author-discuss-settings-group', 'author_discussion_dashboard_limit' );
}

function author_discuss_settings_page() {

auth_discuss_query(3);
$author_discussion_capability = get_option( 'author_discussion_capability' );
$page_message_limit = get_option( 'author_discussion_msg_limit' );
$dashboard_message_limit = get_option( 'author_discussion_dashboard_limit' );
$admin = '';
$editor = '';
$author = '';

if($author_discussion_capability == 'manage_options'){
	$admin = 'selected="selected"';
}
if($author_discussion_capability == 'moderate_comments'){
	$editor = 'selected="selected"';
}
if($author_discussion_capability == 'publish_posts'){
	$author = 'selected="selected"';
}

?>
<div class="wrap">
<h2>Author Discussion Plugin Settings</h2>

<?php if(isset( $_GET['settings-updated'] ) && $_GET['settings-updated'] == 'true' ){ ?>
		   <div id="message" class="updated auth_discuss_fade"><p><strong>The settings have been updated. Click to hide.</strong></p></div><br />
		<?php } 
	if(isset( $_GET['purge_views'] ) && $_GET['purge_views'] == true ){ 
		auth_discuss_purge_views();
		?>
		<div id="message" class="updated auth_discuss_fade"><p><strong>All user views have been purged. Click to hide.</strong></p></div><br />
		<?php }	?>

<form method="post" action="options.php">
    <?php settings_fields( 'author-discuss-settings-group' ); ?>
    <?php do_settings_sections( 'author-discuss-settings-group' ); ?>
    <table class="form-table">
		<tr valign="top">
			<th scope="row">Clear Database on Deactivate</th>
			<td>
				<fieldset>
					<label title="Yes">
						<input type="radio" name="author_discussion_drop_table" value="1" <?php if(get_option('author_discussion_drop_table')==1){
						echo 'checked="checked"';} ?> /> 
						<span>Yes</span>
					</label><br />
					<label title="No">
						<input type="radio" name="author_discussion_drop_table" value="0" <?php if(get_option('author_discussion_drop_table')==0){
						echo 'checked="checked"';} ?> /> 
						<span>No</span>
					</label><br />
					<p class="description">This will delete <strong>all</strong> of the messages stored in your database. Options will be sustained.</p>
				</fieldset>
			</td>
		</tr>
		
		<tr valign="top">
			<th scope="row">Roles Granted Access</th>
			<td>
				<select name="author_discussion_capability" id="author_discussion_capability">
					<option value="manage_options" <?php echo $admin;?> >Administrator</option>
					<option value="moderate_comments" <?php echo $editor;?> >Editors & Higher</option>
					<option value="publish_posts" <?php echo $author;?> >Authors & Higher</option>
				</select><br />
				<p class="description">
					The users that will have access to the <code>discussion</code> tab on the admin dashboard.
				</p>
			</td>
		</tr>
		
		<tr valign="top">
			<th scope="row">Message Limit Per Page</th>
			<td>
				<input type="text" name="author_discussion_msg_limit" value="<?php echo $page_message_limit; ?>"><br />
				<p class="description">
					This will limit the number of posts that will be shown on the <code>discussion</code> page. (Default 5)
				</p>
			</td>
		</tr>
		
		<tr valign="top">
			<th scope="row">Dashboard Widget Message Limit</th>
			<td>
				<input type="text" name="author_discussion_dashboard_limit" value="<?php echo $dashboard_message_limit; ?>"><br />
				<p class="description">
					This will limit the number of posts that will be shown on the <code>dashboard</code> widget. (Default 3)
				</p>
			</td>
		</tr>
		
		<tr valign="top">
			<th scope="row">Purge <em>ALL</em> User View Count</th>
			<td>
				<a href="<?php echo admin_url('admin.php?page=author_discuss_settings&purge_views=true'); ?>">Reset all of the unread view count</a><br />
				<p class="description">
					This will set every user's unread message count to 0.
				</p>
			</td>
		</tr>
		
		
    </table>
    
    <?php submit_button(); ?>

</form>
<?php auth_discuss_footer(); ?>
</div>
<?php } ?>