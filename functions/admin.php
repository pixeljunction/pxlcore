<?php
/***************************************************************
* Function pxlcore_howdy()
* Change Howdy? in the admin bar
***************************************************************/
function pxlcore_howdy() {
	
	global $wp_admin_bar;
	
	/* get the current logged in users gravatar */
	$pxlcore_avatar = get_avatar( get_current_user_id(), 16 );
	       
    /* there is a howdy node, lets alter it */
    $wp_admin_bar->add_node(
    	array(
	        'id' => 'my-account',
	        'title' => sprintf( 'Logged in as, %s', wp_get_current_user()->display_name ) . $pxlcore_avatar,
	    )
	);

}

add_filter( 'admin_bar_menu', 'pxlcore_howdy', 10, 2 );

/***************************************************************
* Function pxlcore_admin_footer_text()
* Change the display text in the wordpress dashboard footer
***************************************************************/
function pxlcore_admin_footer_text () {
	
	/* the text we want to display in the footer */
	$pxlcore_admin_footer_text = 'Site created by <a href="http://pixeljunction.co.uk">Pixel Junction</a> using <a href="http://wordpress.org">WordPress</a>';
	
	/* output this text, running through a filter first */
	echo apply_filters( 'pxlcore_admin_footer_text', $pxlcore_admin_footer_text );
	
}

add_filter('admin_footer_text', 'pxlcore_admin_footer_text');


/***************************************************************
* Function pxlcore_change_login_landing()
* Changing the page users are redirected to after logging in.
***************************************************************/
function pxlcore_change_login_landing( $redirect_to, $request_redirect_to, $user ) {
	
	/* if the current user is a pixel team member */
	if( get_user_meta( $user->ID, 'pxlcore_core_user', true ) != '1' ) {
	
		/* return the url of our new dashboard page */
		return apply_filters( 'pxlcore_login_redirect', admin_url( 'admin.php?page=pxlcore_dashboard' ) );
	
	/* if the current user is a pixel member */
	} else {
		
		/* return the normal admin url */
		return apply_filters( 'pxlcore_pixelmember_login_redirect', admin_url() );
		
	} // end if type of user
	
}

add_filter( 'login_redirect', 'pxlcore_change_login_landing', 100, 3 );

/***************************************************************
* Function pxlcore_login_logo()
* Adds a login logo from the theme folder if present, otherwise
* falls back to the default
***************************************************************/
function pxlcore_login_logo() {

	/* check whether a login logo exists in the child theme */
	if( file_exists( STYLESHEETPATH . '/images/login-logo.png' ) ) {
	
		$pxlcore_login_logo_sizes = apply_filters( 'pxlcore_login_logo_sizes',
            array(
                'width' => '300',
                'height' => '100'
            )
        );
		
		echo '
			<style>
			.login h1 a {
				background-image: url('.get_stylesheet_directory_uri() . '/images/login-logo.png);
				background-size: ' . $pxlcore_login_logo_sizes[ 'width' ] . 'px' . ' ' . $pxlcore_login_logo_sizes[ 'height' ] . 'px;
				height: ' . $pxlcore_login_logo_sizes[ 'height' ] . 'px;
				width: ' . $pxlcore_login_logo_sizes[ 'width' ] . 'px;
			}
			</style>
		';
	
	} // end if login logo present in theme
	
}
	

add_action( 'login_head', 'pxlcore_login_logo' );

/***************************************************************
* Function pxjn_alter_admin_bar()
* Changes the admin bar for non pixel users.
***************************************************************/
function pxjn_alter_admin_bar() {

	/* if the current user is not a pixel team member */
	if( get_user_meta( get_current_user_id(), 'pxlcore_core_user', true ) != '1' ) {
		
		/* load the admin bar global variable */
		global $wp_admin_bar;
		
		/* remove the updates admin bar item */
		$wp_admin_bar->remove_menu( 'updates' );
	
	}

}
 
add_action( 'wp_before_admin_bar_render', 'pxjn_alter_admin_bar', 0 );

/***************************************************************
* Function pxlcore_remove_meta_boxes()
* Removes unwanted metabox from the write post/page screens.
***************************************************************/
function pxlcore_remove_meta_boxes() {

	/* if the current user is not a pixel team member */
	if( get_user_meta( get_current_user_id(), 'pxlcore_core_user', true ) != '1' ) {
	
		$remove_metaboxes = apply_filters( 'pxlcore_remove_metaboxes',
			array(
				array(
					'id' => 'postcustom',
					'page' => 'post',
					'context' => 'normal'
				),
				array(
					'id' => 'commentsdiv',
					'page' => 'post',
					'context' => 'normal'
				),
				array(
					'id' => 'commentstatusdiv',
					'page' => 'post',
					'context' => 'normal'
				),
				array(
					'id' => 'slugdiv',
					'page' => 'post',
					'context' => 'normal'
				),
				array(
					'id' => 'trackbacksdiv',
					'page' => 'post',
					'context' => 'normal'
				),
				array(
					'id' => 'revisionsdiv',
					'page' => 'post',
					'context' => 'normal'
				),
				array(
					'id' => 'tagsdiv-post_tag',
					'page' => 'post',
					'context' => 'side'
				),
				array(
					'id' => 'authordiv',
					'page' => 'post',
					'context' => 'normal'
				),
				array(
					'id' => 'postcustom',
					'page' => 'page',
					'context' => 'normal'
				),
				array(
					'id' => 'commentsdiv',
					'page' => 'page',
					'context' => 'normal'
				),
				array(
					'id' => 'trackbacksdiv',
					'page' => 'page',
					'context' => 'normal'
				),
				array(
					'id' => 'revisionsdiv',
					'page' => 'page',
					'context' => 'normal'
				),
				array(
					'id' => 'commentstatusdiv',
					'page' => 'page',
					'context' => 'normal'
				),
				array(
					'id' => 'authordiv',
					'page' => 'page',
					'context' => 'normal'
				),
				array(
					'id' => 'slugdiv',
					'page' => 'page',
					'context' => 'normal'
				),
			)
		);
		
		/* loop through each meta box item to remove */
		foreach( $remove_metaboxes as $remove_metabox ) {
			
			/* remove each metabox from the array */
			remove_meta_box( $remove_metabox[ 'id' ], $remove_metabox[ 'page' ] , $remove_metabox[ 'context' ] );
							
		}
		
	}
	
}

add_action( 'do_meta_boxes', 'pxlcore_remove_meta_boxes');

/***************************************************************
* Function pxlcore_pixel_profile_field()
* Adds additional field on the users profile page which makes
* the user a member of team pixel!
***************************************************************/
function pxlcore_pixel_profile_field( $user ) {
	
	/* bail out early if user is not an admin */
	if ( !current_user_can( 'manage_options' ) )
		return false;
	
	?>

	<table class="form-table">

		<tr>
			<th scope="row">Core User?</th>

			<td>
				
				<fieldset>
				
					<legend class="screen-reader-text">
						<span>Core User?</span>
					</legend>
					
					<label>
						<input name="pxlcore_core_user" type="checkbox" id="pxlcore_core_user" value="1"<?php checked( get_user_meta( $user->ID, 'pxlcore_core_user', true ) ) ?> />
						Choose whether this user is a core user.
					</label>
				
				</fieldset>
				
			</td>
		</tr>
	
	</table>
	
	<?php
	
}

add_action( 'personal_options', 'pxlcore_pixel_profile_field' );

/***************************************************************
* Function pxlcore_save_pixel_profile_field()
* Saves the information from the additional profile fields
***************************************************************/
function pxlcore_save_pixel_profile_field( $user_id ) {
	
	/* check the current user is a super admin */
	if ( !current_user_can( 'manage_options', $user_id ) )
		return false;
		
	/* get the current user information */
	$pxlcore_current_user = wp_get_current_user();
	
	/* get the current users email address */
	$pxlcore_current_user_email = $pxlcore_current_user->user_email;
	
	/* split email at the @ sign */
	$pxlcore_email_parts = explode( '@', $pxlcore_current_user_email );
	
	/* set the pxlcore email domain */
	$pxlcore_email_domain = apply_filters( 'pxlcore_email_domain', 'pixeljunction.co.uk' );
	
	/* get the email domain is a pixel one */
	if( $pxlcore_email_domain == $pxlcore_email_parts[1] ) {
		
		/* update the user meta with the additional fields on the profile page */
		update_usermeta( $user_id, 'pxlcore_core_user', $_POST[ 'pxlcore_core_user' ] );
		
	}
	
}

add_action( 'personal_options_update', 'pxlcore_save_pixel_profile_field' );
add_action( 'edit_user_profile_update', 'pxlcore_save_pixel_profile_field' );

/***************************************************************
* Function pxlcore_blog_public_warning()
* Adds an admin notice to warn that the blog is hidden from
* search engines when the reading option is chosen
***************************************************************/
function pxlcore_blog_public_warning() {
	
	/* get the options for blog being public */
	$pxlcore_blog_public = get_option( 'blog_public' );
	
	/* check wether the blog is preventing search engines */
	if( $pxlcore_blog_public == 0 ) {
	
		?>
	    
	    <div class="error">
	        
	        <?php
	        	
	        	/* set the warning message */
	        	$pxlcore_blog_public_warning = '<p>Search engines are currently being prevented to indexing this site. Please correct this once the site goes live.</p>';
	        	
	        	/* output the warning message, after passing through a filter to allow devs to change */
	        	echo apply_filters( 'pxlcore_blog_public_warning', $pxlcore_blog_public_warning );
	        
	        ?>
	        	
	    </div>
	    
	    <?php
	
	}
	
}

add_action( 'admin_notices', 'pxlcore_blog_public_warning' );

/***************************************************************
* Function pxlcore_update_scripts()
* Adds scripts to the update page (update-core.php)
***************************************************************/
function pxlcore_update_scripts() {

	/* load the global variable to see which admin page we are on */
	global $pagenow;
	
	/* check whether the current admin page is the upate-core.php page */
	if( $pagenow == 'update-core.php' ) {
	
		wp_enqueue_script( 'jquery' );
		wp_register_script( 'pxlcore_sliding_div', plugins_url( 'js/slidingdiv-hook.js', dirname( __FILE__ ) ), 'jquery' );
		wp_enqueue_script( 'pxlcore_sliding_div' );
	
	} // end if we are on update-core page
	
	/* check whether the current admin page is pxlcore dashboard page */
	if( $pagenow == 'admin.php' ) {
	
		wp_enqueue_script( 'pxlcore_tabs', plugins_url( 'js/pxlcore-tabs.js', dirname( __FILE__ ) ), 'jquery' );
	
	}
	
}

add_action( 'admin_enqueue_scripts', 'pxlcore_update_scripts' );

/***************************************************************
* Function pxlcore_admin_css()
* Adds css stylesheet to head in all admin pages
***************************************************************/
function pxlcore_admin_css() {
	
	/* register the stylesheet */
    wp_register_style( 'pxlcore_admin_css', plugins_url( 'css/admin-style.css', dirname( __FILE__ ) ) );
    
    /* enqueue the stylsheet */
    wp_enqueue_style( 'pxlcore_admin_css' );
    
}

add_action( 'admin_enqueue_scripts', 'pxlcore_admin_css' );

/***************************************************************
* Function pxlcore_update_message()
* Adds an admin notice the update-core.php page in the admin
***************************************************************/
function pxlcore_update_start() {
	
	/* load the global variable to see which admin page we are on */
	global $pagenow;
	
	/* check whether the current admin page is the upate-core.php page */
	if( $pagenow == 'update-core.php' ) {
		
		/* if the current user is not a pixel team member */
		if( get_user_meta( get_current_user_id(), 'pxlcore_core_user', true ) != '1' ) {
		
			/* echo our message */
			echo '<div id="pxlcore-updates" class="wrap">';
			
				/* setup a template file to use for the dashboard widget */
				$pxlcore_update_warning_templatename = 'pxlcore-update-warning.php';
				
				/* locate the template from above in the theme */
				$pxlcore_update_warning_path = locate_template( $pxlcore_update_warning_templatename );
				
				/* check whether the theme has this template or not */
				if( empty( $pxlcore_update_warning_path ) ) {
					
					/* if the path is empty - lets load some default content from the plugin */
					$pxlcore_update_warning_path = PXLCORE_LOCATION . '/inc/update-warning.php';
					
				}
				
				/* include the template file containig the widget content */
				include_once( $pxlcore_update_warning_path );
				
				echo '<p><span class="message button pxlcore-button"><a class="show_hide" href="#">Proceed with Upgrade</a></span></p>';
				
			echo '</div>';
			
			echo '<div id="pxlcore-updates-wrap">';
		
		} // end if user is not a pixel team member
		
	} // end if we are on update-core page
	
}

add_action( 'admin_notices', 'pxlcore_update_start' );

/***************************************************************
* Function pxlcore_update_core_preamble()
* Adds a closing div to our wrapper div added with the admin
* notice on the update-core.php page.
***************************************************************/
function pxlcore_update_end() {
	
	echo '</div>';
	
}

add_action( 'core_upgrade_preamble', 'pxlcore_update_end' );

/***************************************************************
* Function pxlcore_remove_widgets()
* Removes some of the standard widgets that are hardly used.
***************************************************************/
function pxlcore_remove_widgets() {
	
	/* build a nested array of widgets to remove, filterable by plugins and themes */
	$pxlcore_widgets = apply_filters( 'pxlcore_remove_widgets',
		array(
			array(
				'widget' => 'WP_Widget_Links'
			),
			array(
				'widget' => 'WP_Widget_Calendar',
			),
			array(
				'widget' => 'WP_Widget_RSS',
			),
			array(
				'widget' => 'WP_Widget_Meta',
			),
			array(
				'widget' => 'WP_Widget_Tag_Cloud',
			),
			array(
				'widget' => 'WP_Widget_Recent_Comments',
			),
			array(
				'widget' => 'WP_Widget_Search',
			),
		)
	);
	
	/* loop through each widget in the array */
	foreach( $pxlcore_widgets as $pxlcore_widget ) {
		
		/* remove the widget */
		unregister_widget( $pxlcore_widget[ 'widget' ] );
		
	}
	
}

add_action( 'widgets_init', 'pxlcore_remove_widgets' );

/***************************************************************
* Function pxlcore_register_settings()
* Register the settings for this plugin. Just a username and a
* password for authenticating.
***************************************************************/
function pxlcore_register_settings() {
	
	/* create an array of the default settings, making it filterable */
	$pxlcore_registered_settings = apply_filters(
		'pxlcore_register_site_option_settings',
		array(
			'pxlcore_twitter_url',
			'pxlcore_facebook_url',
			'pxlcore_linkedin_url',
			'pxlcore_contact_email',
			'pxlcore_tel_no',
			'pxlcore_footer_text',
		)
	);
	
	/* loop through each setting to register */
	foreach( $pxlcore_registered_settings as $pxlcore_registered_setting ) {
		
		/* register the setting */
		register_setting( 'pxlcore_site_options', $pxlcore_registered_setting );
		
	}

}

add_action( 'admin_init', 'pxlcore_register_settings' );

/***************************************************************
* Function pxlcore_dates_settings()
* Adds settings for hosting and aftercare dates to general settings.
***************************************************************/
class pxlcore_dates_settings {
    
    function pxlcore_dates_settings( ) {
        add_filter( 'admin_init' , array( &$this , 'register_fields' ) );
    }
    
    function register_fields() {
    	
    	/* if the current user is not a pixel team member */
		if( get_user_meta( get_current_user_id(), 'pxlcore_core_user', true ) != 'yes' )
			return;
		
		/* register the settings with WordPress */
    	register_setting(
    		'general',
			'pxlcore_hosting_renewal_date',
			'esc_attr'
		);
		
		register_setting(
    		'general',
			'pxlcore_aftercare_end_date',
			'esc_attr'
		);
		
		/* add new settings field on the general settings page */
        add_settings_field(
        	'pxlcore_hosting_renewal_date',
        	'<label for="pxlcore_hosting_renewal_date">Hosting Renewal Date</label>',
        	array( &$this, 'hosting_fields_html' ),
        	'general'
        );
        
        add_settings_field(
        	'pxlcore_aftercare_end_date',
        	'<label for="pxlcore_aftercare_end_date">Aftercare End Date</label>',
        	array( &$this, 'aftercare_fields_html' ),
        	'general'
        );
        
    }
    
    function hosting_fields_html() {
    	    	
        $value = get_option( 'pxlcore_hosting_renewal_date' );
        echo '<input type="text" id="pxlcore_hosting_renewal_date" name="pxlcore_hosting_renewal_date" value="' . $value . '" />';
        
    }
    
    function aftercare_fields_html() {
    	    	
        $value = get_option( 'pxlcore_aftercare_end_date' );
        echo '<input type="text" id="pxlcore_aftercare_end_date" name="pxlcore_aftercare_end_date" value="' . $value . '" />';
        
    }
    
}

$new_general_setting = new pxlcore_dates_settings();

/***************************************************************
* Function pxlcore_give_edit_theme_options()
* Adds widgets and menus to editors.
***************************************************************/
function pxlcore_give_edit_theme_options( $caps ) {
	
	/* check if the user has the edit_pages capability */
	if( ! empty( $caps[ 'edit_pages' ] ) ) {
		
		/* give the user the edit theme options capability */
		$caps[ 'edit_theme_options' ] = true;
		
	}
	
	/* return the modified capabilities */
	return $caps;
	
}

add_filter( 'user_has_cap', 'pxlcore_give_edit_theme_options' );