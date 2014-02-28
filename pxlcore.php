<?php
/*
Plugin Name: Pixel Core
Plugin URI: http://markwilkinson.me/plugins/pixelcore
Description: This is a backone plugin that adds a bunch of functions to transform(!) your WordPress blog ready to start theming. After building a number of WordPress sites I found myself adding the same code to every site and therefore I have bundled this into a plugin.
Version: 1.2.4
Author: Mark Wilkinson
Author URI: http://markwilkinson.me
License: GPLv2 or later
*/

/* define variable for path to this plugin file. */
define( PXLCORE_LOCATION, dirname( __FILE__ ) );

/* load plugin admin functions */
require_once dirname( __FILE__ ) . '/functions/admin.php';

/* load plugin admin menu functions */
require_once dirname( __FILE__ ) . '/functions/admin-menus.php';

/* load plugin admin menu content functions */
require_once dirname( __FILE__ ) . '/functions/admin-menus-content.php';

/* load plugin counter functions for loops */
require_once dirname( __FILE__ ) . '/functions/counters.php';

/* load plugin template tags */
require_once dirname( __FILE__ ) . '/functions/template-tags.php';

/* load plugin counter functions for loops */
require_once dirname( __FILE__ ) . '/metaboxes/custom-meta-boxes.php';
	
/* make theme updatable using wp-updates.com */
require_once( 'wp-updates-plugin.php' );
new WPUpdatesPluginUpdater_361( 'http://wp-updates.com/api/2/plugin', plugin_basename( __FILE__ ) );

/***************************************************************
* Function pxlcore_post_class()
* Allows a custom field of pxlcore_postclass to be added in
* order to add custom classes to posts.
***************************************************************/
function pxlcore_post_class( $pxlcore_classes ) {

	/* Get the current post ID. */
	$pxlcore_post_id = get_the_ID();
	
	/* If we have a post ID, proceed. */
	if ( !empty( $pxlcore_post_id ) ) {
	
		/* Get the custom post class. */
		$pxlcore_post_class_raw = get_post_meta( $pxlcore_post_id, 'pxlcore_postclass', true );
		
		/* force the custom field to be lower case. */
		$pxlcore_post_class = strtolower($pxlcore_post_class_raw);
		
		/* If a post class was input, sanitize it and add it to the post class array. */
		if ( !empty( $pxlcore_post_class ) )
			$pxlcore_classes[] = sanitize_html_class( $pxlcore_post_class );
			
	}
	
	return $pxlcore_classes;
}

add_filter( 'post_class', 'pxlcore_post_class' );