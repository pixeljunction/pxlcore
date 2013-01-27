<?php

/* adds editor stylesheet from theme folder */
function pxlcore_plugin_mce_css( $mce_css ) {
	if ( ! empty( $mce_css ) )
		$mce_css .= ',';
	$mce_css .= trailingslashit( get_stylesheet_directory_uri() . '/editor-style.css' );
	return $mce_css;
}

add_filter( 'mce_css', 'pxlcore_plugin_mce_css' );

/* create function for size of login logo */
if ( ! function_exists( 'pxlcore_login_logo_height' ) ) { // check it doesn't exist in child theme
	function pxlcore_login_logo_height() {
	
		$pxlcore_logo_height = ' 65px';
		return $pxlcore_logo_height;
	
	}
}

/* add logo to the login screen if present in child theme folder */
if ( ! function_exists( 'pxlcore_login_head' ) ) { // check it doesn't exist in child theme
	function pxlcore_login_head() {
	
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
		
		/* if there is no login logo in the child theme, use the one from the plugins images folder */
		} else {
			
			echo '
				<style>
				.login h1 a {
					background-image: url(' . plugins_url( 'images/login-logo.png' , __FILE__ ) . ');
					background-size: 274px 65px;
					height: 65px;
				}
				</style>
			';
			
		}
		
	}
}
add_action('login_head', 'pxlcore_login_head');

/* removes menus for none admins */
add_action( 'admin_menu', 'pxlcore_remove_menus', 999 );
if ( ! function_exists( 'pxlcore_remove_menus' ) ) { // check it doesn't exist in child theme
	function pxlcore_remove_menus() {
	
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
			remove_submenu_page( 'themes.php', 'theme-editor.php' );
			remove_submenu_page( 'options-general.php', 'options-media.php' );
			remove_submenu_page( 'options-general.php', 'options-permalink.php' );
			remove_submenu_page( 'options-general.php', 'options-privacy.php' );
			remove_submenu_page( 'options-general.php', 'options-reading.php' );
			remove_submenu_page( 'options-general.php', 'options-discussion.php' );
		
		}
		
	}
}

/* removes the unecessary dashboard widgets */
if ( ! function_exists( 'pxlcore_remove_dashboard_widgets' ) ) { // check it doesn't exist in child theme
	function pxlcore_remove_dashboard_widgets() {
		global $wp_meta_boxes;
		
		/* remove the widgets, one by one of each line */
		unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_quick_press']); // quick press widget
		unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_incoming_links']); // incoming links widget
		unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_plugins']); // plugins widget
		unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_recent_drafts']); // recent drafts widget
		unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_primary']); // primary rss box
		unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_secondary']); // secondary rss box
		unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_recent_comments']); // remove recent comments
	}
}

/* add our remove function to apply on dashbaord setup */
add_action('wp_dashboard_setup', 'pxlcore_remove_dashboard_widgets' );

/* unregister some widgets to clean-up the dashboard */
function pxjn_unregister_widgets() {
	unregister_widget( 'WP_Widget_Calendar' );
	unregister_widget( 'WP_Widget_Links' );
	unregister_widget( 'WP_Widget_Meta' );
	unregister_widget( 'WP_Widget_Search' );
	unregister_widget( 'WP_Widget_Recent_Comments' );
	unregister_widget( 'WP_Widget_RSS' );
}

/* add the unregister function to the widget init hook */
add_action('widgets_init', 'pxjn_unregister_widgets', 1);

/* adds our own dashboard widget as a welcome screen item */
if ( ! function_exists( 'pxlcore_wp_dashboard_widget' ) ) { // check it doesn't exist in child theme
	function pxlcore_wp_dashboard_widget() {
		
		/* setup a template file to use for the dashboard widget */
		$pxlcore_dashboard_widget_templatename = 'pxlcore-dashboard-widget.php';
		
		/* locate the template from above in the theme */
		$pxlcore_dashboard_widget_path = locate_template( $pxlcore_dashboard_widget_templatename );
		
		/* check whether the theme has this template or not */
		if( empty( $pxlcore_dashboard_widget_path ) ) {
			
			/* if the path is empty - lets load some default content from the plugin */
			$pxlcore_dashboard_widget_path = dirname( __FILE__ ) . '/pxlcore-dashboard-widget.php';
			
		}
		
		/* include the template file containig the widget content */
		include_once( $pxlcore_dashboard_widget_path );
	}
}

/* setup the dashboard widget, including our content from above */
if ( ! function_exists( 'pxlcore_wp_dashboard_setup' ) ) { // check it doesn't exist in child theme
	function pxlcore_wp_dashboard_setup() {
		wp_add_dashboard_widget( 'pxlcore_welcome_widget', __( 'Welcome to Your New Website', 'pxjn' ), 'pxlcore_wp_dashboard_widget' );
	}
}

/* add our dashboard widget setup function to the wordpress dashbaord widget setup action hook */
add_action('wp_dashboard_setup', 'pxlcore_wp_dashboard_setup');