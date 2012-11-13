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

/* changes the admin dashboard footer text */
if ( ! function_exists( 'pxlcore_admin_footer_text' ) ) { // check it doesn't exist in child theme 
	function pxlcore_admin_footer_text () {
	
		/* get the plugin options array ready to use later */
		$pxlcore_options = get_option( 'pxlcore_options' );
		
		/* get the website by text from plugin options */
		$pxlcore_websiteby = $pxlcore_options[ 'website_by' ];
		
		/* check if website by text is added */
		if( $pxlcore_websiteby ) {
			
			$pxlcore_websiteby_text = $pxlcore_websiteby;
			
		/* no website by text is added */	
		} else {
			
			/* set default website by text */
			$pxlcore_websiteby = 'Mark Wilkinson';
			
		}
		
		/* get the website by link url from plugin options */
		$pxlcore_websiteby_link = $pxlcore_options[ 'website_by_link' ];
		
		/* check if website by link url is added */
		if( $pxlcore_websiteby_link ) {
			
			$pxlcore_websiteby_link_url = $pxlcore_websiteby_link;
			
		/* no website by link url is added */	
		} else {
			
			/* set default website by link url */
			$pxlcore_websiteby_link_url = 'http://markwilkinson.me';
			
		}
		
		/* the text we want to display in the footer */
		echo 'Site created by <a href="' . esc_url( $pxlcore_websiteby_link_url ) . '">' . $pxlcore_websiteby . '</a> using <a href="http://wordpress.org">WordPress</a>';
	}
}
add_filter('admin_footer_text', 'pxlcore_admin_footer_text');

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

/* get the plugin options array ready to use later */
$pxlcore_options = get_option( 'pxlcore_options' );

/* check whether main menu location is switched off in plugin options */
if( $pxlcore_options[ 'remove_rename_posts_to_news' ] != '1' ) {
	
	/* changes the posts item in the dashboard to read news */
	if ( ! function_exists( 'pxjn_change_post_menu_label' ) ) { // check it doesn't exist in child theme
		function pxjn_change_post_menu_label() {
		    global $menu;
		    global $submenu;
		    
		    /* change the menu labels for posts */
		    $menu[5][0] = 'News';
		    $submenu['edit.php'][5][0] = 'All News';
		    $submenu['edit.php'][10][0] = 'Add News Article';
		    $submenu['edit.php'][15][0] = 'Categories'; // Change name for categories
		    $submenu['edit.php'][16][0] = 'Tags'; // Change name for tags
		    echo '';
		}
	}
	
	/* hook post menu label changes into wordpress */
	add_action( 'admin_menu', 'pxjn_change_post_object_label' );
	
	if ( ! function_exists( 'pxjn_change_post_object_label' ) ) { // check it doesn't exist in child theme
		function pxjn_change_post_object_label() {
	        global $wp_post_types;
	        
	        /* change the post object labels such as on the write post screen */
	        $labels = &$wp_post_types['post']->labels;
	        $labels->name = 'News';
	        $labels->singular_name = 'News Article';
	        $labels->add_new = 'Add News Article';
	        $labels->add_new_item = 'Add New';
	        $labels->edit_item = 'Edit News Items';
	        $labels->new_item = 'News';
	        $labels->view_item = 'View News Article';
	        $labels->search_items = 'Search News';
	        $labels->not_found = 'No News Articles found';
	        $labels->not_found_in_trash = 'No News Articles found in Trash';
	    }
	}
	
	/* hook post object label changes into wordpress */
	add_action( 'admin_menu', 'pxjn_change_post_menu_label' );

}