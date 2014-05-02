<?php
/***************************************************************
* Function pxlcore_dashboard_content()
* Pulls in the new dashboard page content from plugin file
***************************************************************/
function pxlcore_dashboard() {

	/* check for a dashboard content file in the theme folder */
	if( file_exists( STYLESHEETPATH . '/pxlcore/dashboard.php' ) ) {
		
		/* load the dashboard content file from the theme folder */
		require_once STYLESHEETPATH . '/pxlcore/dashboard.php';
	
	} else {
		
		/* load plugin dashboard content file */
		require_once PXLCORE_LOCATION . '/inc/dashboard-content.php';
		
	}
	
}

/***************************************************************
* Function pxlcore_add_dashboard_home()
* Adds a new page to use for the home page in admin
***************************************************************/
function pxlcore_add_dashboard_home() {
	
	/* if the current user is a pixel team member */
	if( get_user_meta( get_current_user_id(), 'pixel_member', true ) != 'yes' ) {
	
		/* add a new menu item linking to our new dashboard page */
		add_menu_page(
			'Dashboard',
			'Dashboard',
			'edit_posts',
			'pxlcore_dashboard',
			'pxlcore_dashboard',
			'div',
			1
		);
	
	}
	
}

add_action( 'admin_menu', 'pxlcore_add_dashboard_home' );

/***************************************************************
* Function pxlcore_add_site_options()
* Adds a new menu item for the site options.
***************************************************************/
function pxlcore_add_site_options() {

	/* if the current user is not a pixel team member */
	if( get_user_meta( get_current_user_id(), 'pixel_member', true ) != 'yes' ) {
	
		/* add a new menu item linking to our new dashboard page */
		add_menu_page(
			'Site Options',
			'Site Options',
			'edit_pages',
			'pxlcore_site_options',
			'pxlcore_site_options_content',
			'div',
			99
		);
	
	/* current user is a pixel team member */
	} else {
		
		/* a site options as sub page of settings */
		add_submenu_page(
			'options-general.php',
			'Site Options',
			'Site Options',
			'edit_pages',
			'pxlcore_site_options',
			'pxlcore_site_options_content'
		);
		
	}
	
}

add_action( 'admin_menu', 'pxlcore_add_site_options' );

/***************************************************************
* Function pxlcore_remove_admin_menus()
* Removes admin menus for no pixel junction team members
***************************************************************/
function pxlcore_remove_admin_menus() {

	/* if the current user is not a pixel team member */
	if( get_user_meta( get_current_user_id(), 'pixel_member', true ) != 'yes' ) {
	
		$pxlcore_remove_menu_items = apply_filters( 'pxlcore_remove_admin_menus', array(
			'index.php',
			'seperator1',
			'tools.php',
			'plugins.php',
			'link-manager.php',
			'edit-comments.php',
			'options-general.php',
		) );
		
		/* loop through each of the items from our array */
		foreach( $pxlcore_remove_menu_items as $pxlcore_remove_menu_item ) {
			
			/* reomve the menu item */
			remove_menu_page( $pxlcore_remove_menu_item );
			
		}

	}
	
}

add_action( 'admin_menu', 'pxlcore_remove_admin_menus', 999 );

/***************************************************************
* Function pxlcore_remove_admin_sub_menus()
* Removes sub admin menus for no pixel junction team members
***************************************************************/
function pxlcore_remove_admin_sub_menus() {
	
	/* if the current user is not a pixel team member */
	if( get_user_meta( get_current_user_id(), 'pixel_member', true ) != 'yes' ) {
	
		$pxlcore_remove_sub_menu_items = apply_filters( 'pxlcore_remove_admin_sub_menus',
			array(
				array(
					'parent' => 'themes.php',
					'child' => 'themes.php'
				),
				array(
					'parent' => 'themes.php',
					'child' => 'customize.php'
				),
				array(
					'parent' => 'themes.php',
					'child' => 'theme-editor.php'
				),
				array(
					'parent' => 'themes.php',
					'child' => 'update-core.php'
				),
			)
		);
		
		/* loop through each of the items in our array to remove */
		foreach( $pxlcore_remove_sub_menu_items as $pxlcore_remove_sub_menu_item ) {
				
			/* remove the sub menu item */
			remove_submenu_page( $pxlcore_remove_sub_menu_item[ 'parent'], $pxlcore_remove_sub_menu_item[ 'child' ] );	
			
		} // end foreach item
		
	} // end if pixel member
	
}

add_action( 'admin_menu', 'pxlcore_remove_admin_sub_menus', 999 );