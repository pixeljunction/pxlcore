<?php
/* options saved as pxlcore_options in wp_options table */
add_action('admin_init', 'pxlcore_options_init' );
add_action('admin_menu', 'pxlcore_options_add_page');

// Init plugin options to white list our options
function pxlcore_options_init(){
	register_setting( 'pxlcore_options', 'pxlcore_options', 'pxlcore_options_validate' );
}

// Add menu page
function pxlcore_options_add_page() {
		
	/* get the current user information */
	global $current_user;
	
	/* get the current users ID and assign to variable */
	$current_user = wp_get_current_user(); $current_user_id = $current_user->ID;
	
	/* only if the current user ID is lesson than 2 */
	if( $current_user_id < 2 ) {
		
		/* add the settings page for this plugin to the wordpress settings menu */
		add_options_page('Pixel Core', 'Pixel Core Settings', 'manage_options', 'pxlcore_options', 'pxlcore_options_do_page');
		
	}
	
}

// Draw the menu page itself
function pxlcore_options_do_page() {
	?>
	<div class="wrap">
		<?php screen_icon(); echo "<h2>Pixel Core Plugin Options</h2>"; ?>
		<form method="post" action="options.php">
			<?php settings_fields('pxlcore_options'); ?>
			<?php $options = get_option('pxlcore_options'); ?>
			<table class="form-table">
				<tr valign="top"><th scope="row">Remove Post Thumbnails?</th>
					<td><input name="pxlcore_options[remove_post_thumbnails]" type="checkbox" value="1" <?php checked('1', $options['remove_post_thumbnails']); ?> /></td>
				</tr>
				<tr valign="top"><th scope="row">Remove Custom Background?</th>
					<td><input name="pxlcore_options[remove_custom_background]" type="checkbox" value="1" <?php checked('1', $options['remove_custom_background']); ?> /></td>
				</tr>
				<tr valign="top"><th scope="row">Remove Custom Header?</th>
					<td><input name="pxlcore_options[remove_custom_header]" type="checkbox" value="1" <?php checked('1', $options['remove_custom_header']); ?> /></td>
				</tr>
				<tr valign="top"><th scope="row">Remove Pixel Core Widget Areas?</th>
					<td><input name="pxlcore_options[remove_widget_areas]" type="checkbox" value="1" <?php checked('1', $options['remove_widget_areas']); ?> /></td>
				</tr>
				<tr valign="top"><th scope="row">Remove Pixel Core Main Menu Location?</th>
					<td><input name="pxlcore_options[remove_main_menu]" type="checkbox" value="1" <?php checked('1', $options['remove_main_menu']); ?> /></td>
				</tr>
				<tr valign="top"><th scope="row">Remove Renaming Posts to News?</th>
					<td><input name="pxlcore_options[remove_rename_posts_to_news]" type="checkbox" value="1" <?php checked('1', $options['remove_rename_posts_to_news']); ?> /></td>
				</tr>
				<tr valign="top"><th scope="row">Website by Text</th>
                    <td><input type="text" name="pxlcore_options[website_by]" value="<?php echo $options['website_by']; ?>" /></td>
                </tr>
                <tr valign="top"><th scope="row">Website by Link</th>
                    <td><input type="text" name="pxlcore_options[website_by_link]" value="<?php echo $options['website_by_link']; ?>" /></td>
                </tr>
			</table>
			<p class="submit">
			<input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
			</p>
		</form>
	</div>
	<?php	
}

// Sanitize and validate input. Accepts an array, return a sanitized array.
function pxlcore_options_validate($input) {
	// Our first value is either 0 or 1
	$input['remove_post_thumbnails'] = ( $input['remove_post_thumbnails'] == 1 ? 1 : 0 );
	$input['remove_custom_background'] = ( $input['remove_custom_background'] == 1 ? 1 : 0 );
	$input['remove_custom_header'] = ( $input['remove_custom_header'] == 1 ? 1 : 0 );
	$input['remove_widget_areas'] = ( $input['remove_widget_areas'] == 1 ? 1 : 0 );
	$input['remove_main_menu'] = ( $input['remove_main_menu'] == 1 ? 1 : 0 );
	$input['remove_rename_posts_to_news'] = ( $input['remove_rename_posts_to_news'] == 1 ? 1 : 0 );

	$input['website_by'] =  wp_filter_nohtml_kses( $input['website_by'] );
	$input['website_by_link'] =  esc_url( $input['website_by_link'] );
	
	return $input;
}
