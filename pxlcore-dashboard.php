<?php
/***************************************************************
* Function pxlcore_admin_css()
* Adds css stylesheet to head in all admin pages
***************************************************************/
function pxlcore_admin_css() {
	
	/* register the stylesheet */
    wp_register_style( 'pxlcore_admin_css', plugins_url( 'css/admin-style.css', __FILE__ ) );
    
    /* enqueue the stylsheet */
    wp_enqueue_style( 'pxlcore_admin_css' );
    
}

add_action( 'admin_enqueue_scripts', 'pxlcore_admin_css' );

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
	echo "Site created by <a href='http://pixeljunction.co.uk'>Pixel Junction</a> using <a href='http://wordpress.org'>WordPress</a>";
	
}

add_filter('admin_footer_text', 'pxlcore_admin_footer_text');

/***************************************************************
* Function pxlcore_plugin_mce_css()
* Adds editor stylesheet from the theme folder
***************************************************************/
function pxlcore_plugin_mce_css( $mce_css ) {
	
	if ( ! empty( $mce_css ) )
		$mce_css .= ',';
	
	/* set the path for the stylesheet in the theme folder */
	$mce_css .= trailingslashit( get_stylesheet_directory_uri() . '/editor-style.css' );
	
	/* return the path to the stylesheet */
	return $mce_css;
	
}

add_filter( 'mce_css', 'pxlcore_plugin_mce_css' );

/***************************************************************
* Function pxlcore_login_logo()
* Adds a login logo from the theme folder if present, otherwise
* falls back to the default
***************************************************************/
function pxlcore_login_logo() {

	/* check whether a login logo exists in the child theme */
	if( file_exists( STYLESHEETPATH . '/images/login-logo.png' ) ) {
		
		echo '
			<style>
			.login h1 a {
				background-image: url('.get_stylesheet_directory_uri() . '/images/login-logo.png);
				background-size: 300px 100px;
				height: 100px;
			}
			</style>
		';
	
	} // end if login logo present in theme
	
}
	

add_action( 'login_head', 'pxlcore_login_logo' );

/***************************************************************
* Function pxlcore_remove_admin_menus()
* Removes admin menus for no pixel junction team members
***************************************************************/
function pxlcore_remove_admin_menus() {

	/* get the current user information */
	global $current_user;
	
	/* get the current users ID and assign to variable */
	$current_user = wp_get_current_user(); $current_user_id = $current_user->ID;
	
	/* if the current user ID is greater than 2 */
	if( $current_user_id > 2 ) {
	
		/* remove menus that are not required */
		remove_menu_page( 'tools.php');
		remove_menu_page( 'plugins.php');
		remove_menu_page( 'link-manager.php');
		remove_submenu_page( 'themes.php', 'themes.php' );
		remove_submenu_page( 'index.php', 'update-core.php' );
		remove_submenu_page( 'options-general.php', 'options-media.php' );
		remove_submenu_page( 'options-general.php', 'options-permalink.php' );
		remove_submenu_page( 'options-general.php', 'options-privacy.php' );
		remove_submenu_page( 'options-general.php', 'options-reading.php' );
		remove_submenu_page( 'options-general.php', 'options-discussion.php' );
	
	} // end if user if is more than 2
	
}

add_action( 'admin_menu', 'pxlcore_remove_admin_menus', 999 );

/***************************************************************
* Function pxlcore_remove_update_nag()
* Removes the wordpress update nag for plugins and core for non
* pixel junction members
***************************************************************/
function pxlcore_remove_update_nag() {

	/* get the current user information */
	global $current_user;
	
	/* get the current users ID and assign to variable */
	$current_user = wp_get_current_user(); $current_user_id = $current_user->ID;
	
	/* if the current user ID is greater than 2 */
	if( $current_user_id > 2 ) {
	
		add_filter( 'pre_site_transient_update_core', create_function( '$a', "return null;" ) );
	
	} // end if user if is more than 2
	
}

/***************************************************************
* Function pxlcore_remove_dashboard_widgets()
* Removes wordpress metaboxes from the dashboard home screen
***************************************************************/
function pxlcore_remove_dashboard_widgets() {
	
	/* initiate the global metaboxes variable */
	global $wp_meta_boxes;
	
	/* remove the widgets by unsetting them from the array */
	unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_quick_press']); // quick press widget
	unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_incoming_links']); // incoming links widget
	unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_plugins']); // plugins widget
	unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_recent_drafts']); // recent drafts widget
	unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_primary']); // primary rss box
	unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_secondary']); // secondary rss box
	unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_recent_comments']); // remove recent comments

}

add_action( 'wp_dashboard_setup', 'pxlcore_remove_dashboard_widgets' );

/***************************************************************
* Function pxlcore_dashboard_widget()
* Builds a dashboard widget for site information. Looks for this
* in the theme folder and if not present adds the default from
* this plugin.
***************************************************************/
function pxlcore_dashboard_widget() {
	
	/* setup a template file to use for the dashboard widget */
	$pxlcore_dashboard_widget_templatename = 'dashboard-widget.php';
	
	/* locate the template from above in the theme */
	$pxlcore_dashboard_widget_path = locate_template( $pxlcore_dashboard_widget_templatename );
	
	/* check whether the theme has this template or not */
	if( empty( $pxlcore_dashboard_widget_path ) ) {
		
		/* if the path is empty - lets load some default content from the plugin */
		$pxlcore_dashboard_widget_path = dirname( __FILE__ ) . '/dashboard-widget.php';
		
	}
	
	/* include the template file containig the widget content */
	include_once( $pxlcore_dashboard_widget_path );
}

/***************************************************************
* Function pxlcore_dashboard_setup()
* Adds the dashboard widget to wordpress including settin the
* title etc.
***************************************************************/
function pxlcore_dashboard_setup() {
	wp_add_dashboard_widget( 'pxlcore_welcome_widget', __( 'Welcome to Your New Website', 'pxjn' ), 'pxlcore_dashboard_widget' );
}

add_action( 'wp_dashboard_setup', 'pxlcore_dashboard_setup' );

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
	        <p>Search engines are currently being prevented to indexing this site. Please correct this once the site goes live.</p>
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
		wp_register_script( 'pxlcore_sliding_div', plugins_url( 'js/slidingdiv-hook.js', __FILE__ ), 'jquery' );
		wp_enqueue_script( 'pxlcore_sliding_div' );
	
	} // end if we are on update-core page
	
}

add_action( 'admin_enqueue_scripts', 'pxlcore_update_scripts' );

/***************************************************************
* Function pxlcore_update_message()
* Adds an admin notice the update-core.php page in the admin
***************************************************************/
function pxlcore_update_start() {
	
	/* load the global variable to see which admin page we are on */
	global $pagenow;
	
	/* check whether the current admin page is the upate-core.php page */
	if( $pagenow == 'update-core.php' ) {
		
		/* get the current user information */
		global $current_user;
		
		/* get the current users ID and assign to variable */
		$current_user = wp_get_current_user(); $current_user_id = $current_user->ID;
		
		/* if the current user ID is greater than 2 */
		//if( $current_user_id > 2 ) {
		
			/* echo our message */ ?>
			<div id="pxlcore-updates" class="wrap">
			
				<h2>Information About Upgrading - Proceed with Caution!</h2>
			
				<p>You have updates that can be added to WordPress. These updates will bring your site to the latest version of WordPress as well as making sure any plugins that your site runs are also up-to-date. Please be aware however that if you choose to update, there is a risk of your site not functioning correctly due to the new version of WordPress being incompatible with features of your site.</p>
				<p>Pixel Junction highly recommend that you content us to complete the upgrade for you at a cost of £40 + VAT. We will make a copy of your site, using this to perform an update. Once we are happy that the update is successful and the site still functions as it should, we will then apply this update to your live site.</p>
				<p>You can of course go ahead and apply the updates yourself, but please bare in mind the above information.</p>
				
				<p><span class="message button pxlcore-button"><a class="show_hide" href="#">Proceed with Upgrade</a></span></p>
				
			</div>
			
			<div id="pxlcore-updates-wrap">
			
			<?php
		
		//} // end if user is greater than 2
		
	} // end if we are on update-core page
	
}

add_action( 'admin_notices', 'pxlcore_update_start' );

/***************************************************************
* Function pxlcore_update_core_preamble()
* Adds output to the update-core.php page in the admin
***************************************************************/
function pxlcore_update_end() {
	
	echo '</div>';
	
}

add_action( 'core_upgrade_preamble', 'pxlcore_update_end' );