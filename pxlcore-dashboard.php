<?php
/***************************************************************
* Function pxjn_howdy()
* Change Howdy? in the admin bar
***************************************************************/
function pxjn_howdy() {
	
	global $wp_admin_bar;
	
	/* get the current logged in users gravatar */
	$pxjn_avatar = get_avatar( get_current_user_id(), 16 );
	       
    /* there is a howdy node, lets alter it */
    $wp_admin_bar->add_node(
    	array(
	        'id' => 'my-account',
	        'title' => sprintf( 'Logged in as, %s', wp_get_current_user()->display_name ) . $pxjn_avatar,
	    )
	);

}

add_filter( 'admin_bar_menu', 'pxjn_howdy', 10, 2 );

/***************************************************************
* Function pxjn_admin_footer_text()
* Change the display text in the wordpress dashboard footer
***************************************************************/
function pxjn_admin_footer_text () {
			
	/* the text we want to display in the footer */
	echo "Site created by <a href='http://pixeljunction.co.uk'>Pixel Junction</a> using <a href='http://wordpress.org'>WordPress</a>";
	
}

add_filter('admin_footer_text', 'pxjn_admin_footer_text');

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
* Function pxlcore_login_logo_height()
* Function to set the height of the login logo
***************************************************************/
if ( ! function_exists( 'pxlcore_login_logo_height' ) ) {
	function pxlcore_login_logo_height() {
	
		$pxlcore_logo_height = ' 65px';
		return $pxlcore_logo_height;
	
	}
}

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
				background-size: 274px '. pxlcore_login_logo_height() .';
				height: '. pxlcore_login_logo_height() .';
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
		remove_submenu_page( 'options-general.php', 'options-media.php' );
		remove_submenu_page( 'options-general.php', 'options-permalink.php' );
		remove_submenu_page( 'options-general.php', 'options-privacy.php' );
		remove_submenu_page( 'options-general.php', 'options-reading.php' );
		remove_submenu_page( 'options-general.php', 'options-discussion.php' );
	
	} // end if user if is more than 2
	
}

add_action( 'admin_menu', 'pxlcore_remove_admin_menus', 999 );

/***************************************************************
* Function pxjn_remove_update_nag()
* Removes the wordpress update nag for plugins and core for non
* pixel junction members
***************************************************************/
function pxjn_remove_update_nag() {

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