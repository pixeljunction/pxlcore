<?php
/***************************************************************
* Function pxlcore_site_options_content()
* Creates the output markup for the added site options page
***************************************************************/
function pxlcore_site_options_content() {
	
	?>
	
	<div class="wrap">
		
		<h2>Site Options</h2>
		
		<?php

			/* do before settings page action */
			do_action( 'pxlcore_after_site_options_form' );

		?>
		
		<form method="post" action="options.php">
		
			<?php settings_fields( 'general' ); ?>
			
			<table class="form-table">
			
				<tbody>
					<tr valign="top">
						<th scope="row">
							<label for="blogname">Site Title</label>
						</th>
						<td>
							<input type="text" name="blogname" id="blogname" class="regular-text" value="<?php echo get_option( 'blogname' ) ?>">
						</td>
					</tr>
					<tr valign="top">
						<th scope="row">
							<label for="blogdescription">Tagline</label>
						</th>
						<td>
							<input type="text" name="blogdescription" id="blogdescription" class="regular-text" value="<?php echo get_option( 'blogdescription' ) ?>">
						</td>
					</tr>
					<tr valign="top">
						<th scope="row">
							<label for="admin_email">Admin Email</label>
						</th>
						<td>
							<input type="text" name="admin_email" id="admin_email" class="regular-text" value="<?php echo get_option( 'admin_email' ) ?>">
						</td>
					</tr>
					<?php

						/* do before settings page action */
						do_action( 'pxlcore_after_site_options' );
			
					?>
				</tbody>
				
			</table>
			
			<p class="submit">
				<input type="submit" name="submit" id="submit" class="button-primary" value="Save Changes">
			</p>
			
		</form>
		
		<?php

		/* do after settings page action */
		do_action( 'pxlcore_after_site_options_form' );
		
		?>
		
	</div><!- // wrap -->
	
	<?php
	
}