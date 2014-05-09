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
		
			/* output filterable intro text */
			echo apply_filters( 'pxlcore_site_option_intro', '<p>Below you can set some basic options for your site. Some of these options are used to display content on the front end, for example your telephone number may appear depending on your design.</p>' );

			/* do before settings page action */
			do_action( 'pxlcore_after_site_options_form' );

		?>
		
		<form method="post" action="options.php">
		
			<?php
			
				settings_fields( 'general' );
				settings_fields( 'pxlcore_site_options' );
			
			?>
			
			<table class="form-table">
			
				<tbody>
					
					<?php
					
						/* create empty filterable array for plugins to add own settings */
						$pxlcore_site_option_settings = apply_filters(
							'pxlcore_site_option_settings',
							array(
								'pxlcore_twitter_url' => array(
									'setting_name' => 'pxlcore_twitter_url',
									'setting_label' => 'Twitter URL',
									'setting_description' => 'Enter the URL for your Twitter page.',
									'setting_type' => 'text',
									'setting_class' => 'twitter',
								),
								'pxlcore_facebook_url' => array(
									'setting_name' => 'pxlcore_facebook_url',
									'setting_label' => 'Facebook URL',
									'setting_description' => 'Enter the URL for your Facebook page.',
									'setting_type' => 'text',
									'setting_class' => 'facebook',
								),
								'pxlcore_linkedin_url' => array(
									'setting_name' => 'pxlcore_linkedin_url',
									'setting_label' => 'LinkedIn URL',
									'setting_description' => 'Enter the URL for your LinkedIn page.',
									'setting_type' => 'text',
									'setting_class' => 'linkedin',
								),
								'pxlcore_content_email' => array(
									'setting_name' => 'pxlcore_contact_email',
									'setting_label' => 'Contact Email',
									'setting_description' => 'Enter a contact email here, this may be used on the site for people to get in touch with you.',
									'setting_type' => 'text',
									'setting_class' => 'email',
								),
								'pxlcore_tel_no' => array(
									'setting_name' => 'pxlcore_tel_no',
									'setting_label' => 'Telephone Number',
									'setting_description' => 'Please enter your contact telephone number here, which may be displayed on the site depending on your design.',
									'setting_type' => 'text',
									'setting_class' => 'telno',
								),
								'pxlcore_footer_text' => array(
									'setting_name' => 'pxlcore_footer_text',
									'setting_label' => 'Footer Text',
									'setting_description' => 'Enter text here to display in the footer of your site. You could include a Copyright notice for example.',
									'setting_type' => 'wysiwyg',
									'setting_class' => 'footer_text',,
									'media_buttons' => false,
								),
							)
						);

						/* check we have settings items to output */
						if( ! empty( $pxlcore_site_option_settings ) ) {
							
							/* loop through each feature control output */
							foreach( $pxlcore_site_option_settings as $pxlcore_site_option_setting ) {
							
								?>
						
								<tr valign="top" class="pxlcore-setting pxlcore-setting-<?php echo esc_attr( $pxlcore_site_option_setting[ 'setting_class' ] ); ?>">
									<th scope="row">
										<label for="<?php echo esc_attr( $pxlcore_site_option_setting[ 'setting_name' ] ); ?>"><?php echo $pxlcore_site_option_setting[ 'setting_label' ]; ?></label>
									</th>
									<td>
										
										<?php
											
											/* setup a swith statement to output based on setting type */
											switch( $pxlcore_site_option_setting[ 'setting_type' ] ) {
												
												/* if the type is set to select input */
											    case 'select':
											    	
											    	?>
											    	<select name="<?php echo $pxlcore_site_option_setting[ 'setting_name' ]; ?>" id="<?php echo $pxlcore_site_option_setting[ 'setting_name' ]; ?>">
											    	
											    	<?php
											    	
											    	/* get the setting options */
											    	$pxlcore_setting_options = $pxlcore_site_option_setting[ 'setting_options' ];
											    	
											        /* loop through each option */
											        foreach( $pxlcore_setting_options as $pxlcore_setting_option ) {
											        												        
												        ?>
												        <option value="<?php echo esc_attr( $pxlcore_setting_option[ 'value' ] ); ?>" <?php selected( get_option( $pxlcore_site_option_setting[ 'setting_name' ] ), $pxlcore_setting_option[ 'value' ] ); ?>><?php echo $pxlcore_setting_option[ 'name' ]; ?></option>
														<?php
												        
											        }
											        
											        ?>
											    	</select>
											        <?php
											        
											        /* break out of the switch statement */
											        break;
											    											    
											    /* if the type is set to a textarea input */  
											    case 'textarea':
											    	
											    	?>
											        <textarea name="<?php echo $pxlcore_site_option_setting[ 'setting_name' ]; ?>" rows="4" cols="50" id="<?php echo $pxlcore_site_option_setting[ 'setting_name' ]; ?>" class="regular-text"><?php echo get_option( $pxlcore_site_option_setting[ 'setting_name' ] ) ?></textarea>
											        <?php
											        
											        /* break out of the switch statement */
											        break;
											       
											    case 'wysiwyg':
											    											    	
											    	/* set some settings args for the editor */
											    	$pxlcore_editor_settings = array(
											    		'textarea_rows' => 5,
											    		'media_buttons' => $pxlcore_site_option_setting[ 'media_buttons' ],
											    	);
											    	
											    	/* get current content for the wysiwyg */
											    	$pxlcore_wysiwyg_content = get_option( $pxlcore_site_option_setting[ 'setting_name' ] );
											    	
											    	/* display the wysiwyg editor */
											    	wp_editor( $pxlcore_wysiwyg_content, $pxlcore_site_option_setting[ 'setting_name' ], $pxlcore_editor_settings );
											    	
											    	break;
											    
											    /* any other type of input - treat as text input */ 
											    default:
													?>
													<input type="text" name="<?php echo $pxlcore_site_option_setting[ 'setting_name' ]; ?>" id="<?php echo $pxlcore_site_option_setting[ 'setting_name' ]; ?>" class="regular-text" value="<?php echo get_option( $pxlcore_site_option_setting[ 'setting_name' ] ) ?>" />
													<?php
											        
											}
										
										?>
										
										<p class="description"><?php echo $pxlcore_site_option_setting[ 'setting_description' ]; ?></p>
									</td>
								</tr>
								
								<?php
							
							}
						
						}
					
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