<?php
/**
 * @package pxlcore
*/
/*
Plugin Name: Pixel Core
Plugin URI: http://markwilkinson.me/plugins/pixelcore
Description: This is a backone plugin that adds a bunch of functions to transform(!) your WordPress blog ready to start theming. After building a number of WordPress sites I found myself adding the same code to every site and therefore I have bundled this into a plugin.
Version: 1.0
Author: Mark Wilkinson
Author URI: http://markwilkinson.me
License: GPLv2 or later
*/

/* lets start by making this plugin updatable - this uses the wp-updates.com site */
require_once('wp-updates-plugin.php');
new WPUpdatesPluginUpdater( 'http://wp-updates.com/api/1/plugin', 51, plugin_basename(__FILE__) );

/* load plugin dashboard functions */
require_once dirname( __FILE__ ) . '/pxlcore-dashboard.php';

/* load plugin counter functions for loops */
require_once dirname( __FILE__ ) . '/pxlcore-counters.php';

/* adds content of a custom field with meta key 'pxlcore_postclass' into the post_class function */
add_filter( 'post_class', 'pxlcore_post_class' );

/* create our post class function */
if ( ! function_exists( 'pxlcore_post_class' ) ) { // check it doesn't exist in child theme
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
}

/* pxjn comments function */
if ( ! function_exists( 'pxlcore_comments' ) ) { // check it doesn't exist in child theme
	function pxlcore_comments( $comment, $args, $depth ) {
		$GLOBALS['comment'] = $comment;
		switch ( $comment->comment_type ) :
			case '' :
		?>
		<li <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>">
			<div id="comment-<?php comment_ID(); ?>">
                <div class="comment-author vcard">
                    <?php echo get_avatar( $comment, 40 ); ?>
                    <?php printf( __( '%s <span class="says">says:</span>', 'pj' ), sprintf( '<cite class="fn">%s</cite>', get_comment_author_link() ) ); ?>
                </div><!-- .comment-author .vcard -->
                <?php if ( $comment->comment_approved == '0' ) : ?>
                    <em><?php _e( 'Your comment is awaiting moderation.', 'pj' ); ?></em>
                    <br />
                <?php endif; ?>
                <div class="comment-meta commentmetadata"><a href="<?php echo esc_url( get_comment_link( $comment->comment_ID ) ); ?>">
                    <?php
                        /* translators: 1: date, 2: time */
                        printf( __( '%1$s at %2$s', 'pj' ), get_comment_date(),  get_comment_time() ); ?></a><?php edit_comment_link( __( '(Edit)', 'pj' ), ' ' );
                    ?>
                </div><!-- .comment-meta .commentmetadata -->
                <div class="comment-body"><?php comment_text(); ?></div>
                <div class="reply">
                    <?php comment_reply_link( array_merge( $args, array( 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) ); ?>
                </div><!-- .reply -->
            </div><!-- #comment-##  -->
		<?php
				break;
			case 'pingback'  :
			case 'trackback' :
		?>
		<li class="post pingback">
			<p><?php _e( 'Pingback:', 'pj' ); ?> <?php comment_author_link(); ?><?php edit_comment_link( __('(Edit)', 'pj'), ' ' ); ?></p>
		<?php
				break;
		endswitch;
	}
}

/* multiple page post navigation function (taken from the twentyeleven theme) */
if ( ! function_exists( 'pxlcore_content_nav' ) ) { // check it doesn't exist in child theme
	function pxlcore_content_nav() {
		global $wp_query;
		
		/* if the maximum pages we have in our query is more than 1 */
		if ( $wp_query->max_num_pages > 1 ) {
		
			/* setup our content nav output stored as variable */
			$pxlcore_content_nav_output = '<div class="navigation">';
				$pxlcore_content_nav_output .= '<div class="nav-alignleft">' . next_posts_link('&laquo; Older Entries') . '</div><div class="nav-alignright">' . previous_posts_link('Newer Entries &raquo;') . '</div>';
			$pxlcore_content_nav_output .= '</div>';
			
			/* return our output, first running it through a filter so it can be changed in a plugin or child theme */
			return apply_filters( 'pxlcore_content_nav_output', $pxlcore_content_nav_output );
			
		}
	}
}

/* get featured image url function */
if ( ! function_exists( 'pxlcore_featured_img_url' ) ) { // check it doesn't exist in child theme
	function pxlcore_featured_img_url($pxlcore_featured_img_size) {
		$pxlcore_image_id = get_post_thumbnail_id();
		$pxlcore_image_url = wp_get_attachment_image_src($pxlcore_image_id,$pxlcore_featured_img_size);
		$pxlcore_image_url = $pxlcore_image_url[0];
		return $pxlcore_image_url;
	}
}

/* get featured image caption */
if ( ! function_exists( 'pxlcore_featured_img_caption' ) ) { // check it doesn't exist in child theme
	function pxlcore_featured_img_caption() {
		global $post;
		$pxlcore_thumbnail_id = get_post_thumbnail_id($post->ID);
		$pxlcore_thumbnail_image = get_posts(array('p' => $pxlcore_thumbnail_id, 'post_type' => 'attachment', 'post_status' => 'any'));
		if ($pxlcore_thumbnail_image && isset($pxlcore_thumbnail_image[0])) {
			return '<p>'.$pxlcore_thumbnail_image[0]->post_excerpt.'</p>';
		}
	}
}

/* get featured image title */
if ( ! function_exists( 'pxlcore_featured_img_title' ) ) { // check it doesn't exist in child theme
	function pxlcore_featured_img_title() {
		global $post;
		$pxlcore_thumbnail_id = get_post_thumbnail_id($post->ID);
		$pxlcore_thumbnail_image = get_posts(array('p' => $pxlcore_thumbnail_id, 'post_type' => 'attachment', 'post_status' => 'any'));
		if ($pxlcore_thumbnail_image && isset($pxlcore_thumbnail_image[0])) {
			return '<span class="featured-image-title">'.$pxlcore_thumbnail_image[0]->post_title.'</h2>';
		}
	}
}