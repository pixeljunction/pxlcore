<?php
/***************************************************************
* Function pxlcore_comments()
* Comments function for display comments. This function is passed
* to the comments_template.
***************************************************************/
function pxlcore_comments( $comment, $args, $depth ) {
	$GLOBALS['comment'] = $comment;
	switch ( $comment->comment_type ) :
		case '' :
	?>
	<li <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>">
		<div id="comment-<?php comment_ID(); ?>">
            <div class="comment-author vcard">
                <?php echo get_avatar( $comment, 40 ); ?>
                <?php printf( __( '%s <span class="says">says:</span>', 'pxlcore' ), sprintf( '<cite class="fn">%s</cite>', get_comment_author_link() ) ); ?>
            </div><!-- .comment-author .vcard -->
            <?php if ( $comment->comment_approved == '0' ) : ?>
                <em><?php _e( 'Your comment is awaiting moderation.', 'pxlcore' ); ?></em>
                <br />
            <?php endif; ?>
            <div class="comment-meta commentmetadata"><a href="<?php echo esc_url( get_comment_link( $comment->comment_ID ) ); ?>">
                <?php
                    /* translators: 1: date, 2: time */
                    printf( __( '%1$s at %2$s', 'pxlcore' ), get_comment_date(),  get_comment_time() ); ?></a><?php edit_comment_link( __( '(Edit)', 'pxlcore' ), ' ' );
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
		<p><?php _e( 'Pingback:', 'pxlcore' ); ?> <?php comment_author_link(); ?><?php edit_comment_link( __('(Edit)', 'pxlcore'), ' ' ); ?></p>
	<?php
			break;
	endswitch;
}

/***************************************************************
* Function pxlcore_content_nav()
* Function to displaying multiple posts per page navigation.
* Display as a numbered list, needs styling.
***************************************************************/
function pxlcore_content_nav() {
	
	/* initiate global variable for database and wp_query */
	global $wpdb, $wp_query;

	$request = $wp_query->request;
	$posts_per_page = intval(get_query_var('posts_per_page'));
	$paged = intval(get_query_var('paged'));
	$numposts = $wp_query->found_posts;
	$max_page = $wp_query->max_num_pages;

	if(empty($paged) || $paged == 0) {
		$paged = 1;
	}
	
	$pages_to_show = apply_filters('pxjn_filter_pages_to_show', 8);
	$pages_to_show_minus_1 = $pages_to_show-1;
	$half_page_start = floor($pages_to_show_minus_1/2);
	$half_page_end = ceil($pages_to_show_minus_1/2);
	$start_page = $paged - $half_page_start;
	
	if($start_page <= 0) {
		$start_page = 1;
	}
	
	$end_page = $paged + $half_page_end;
	
	if(($end_page - $start_page) != $pages_to_show_minus_1) {
		$end_page = $start_page + $pages_to_show_minus_1;
	}
	
	if($end_page > $max_page) {
		$start_page = $max_page - $pages_to_show_minus_1;
		$end_page = $max_page;
	}
	
	if($start_page <= 0) {
		$start_page = 1;
	}

	if ($max_page > 1) {
		echo $before.'<div class="pagenav clearfix">';
		if ($start_page >= 2 && $pages_to_show < $max_page) {
			$first_page_text = "&laquo;";
			echo '<a href="'.get_pagenum_link().'" title="'.$first_page_text.'" class="number">'.$first_page_text.'</a>';
		}
		//previous_posts_link('&lt;');
		for($i = $start_page; $i  <= $end_page; $i++) {
			if($i == $paged) {
				echo ' <span class="number current">'.$i.'</span> ';
			} else {
				echo ' <a href="'.get_pagenum_link($i).'" class="number">'.$i.'</a> ';
			}
		}
		//next_posts_link('&gt;');
		if ($end_page < $max_page) {
			$last_page_text = "&raquo;";
			echo '<a href="'.get_pagenum_link($max_page).'" title="'.$last_page_text.'" class="number">'.$last_page_text.'</a>';
		}
		echo '</div>'.$after;
	}
	
}

/***************************************************************
* Function pxlcore_featured_img_url()
* Function to output the featured image url.
***************************************************************/
function pxlcore_featured_img_url( $pxlcore_featured_img_size ) {
	
	/* get the id of the featured image */
	$pxlcore_image_id = get_post_thumbnail_id();
	
	/* get the image src date for this featuredimage id */
	$pxlcore_image_url = wp_get_attachment_image_src( $pxlcore_image_id, $pxlcore_featured_img_size );
	
	/* get the first part of the array which is the url */
	$pxlcore_image_url = $pxlcore_image_url[0];
	
	/* output the url */
	return $pxlcore_image_url;

}

/***************************************************************
* Function pxlcore_featured_img_caption()
* Function to output the featured image caption. Pass to it before
* and after tags, such as '<p>' and '</p>'
***************************************************************/
function pxlcore_featured_img_caption( $pxlcore_before, $pxlcore_after ) {
	
	/* load the global post variable */
	global $post;
	
	/* get the id of the featured image */
	$pxlcore_thumbnail_id = get_post_thumbnail_id( $post->ID );
	
	/* get any attachment posts with the above attachment id i.e. get the post data for the featured image */
	$pxlcore_thumbnail_image = get_posts( array( 'p' => $pxlcore_thumbnail_id, 'post_type' => 'attachment', 'post_status' => 'any' ) );
	
	/* if we have a post returend */
	if( $pxlcore_thumbnail_image && isset( $pxlcore_thumbnail_image[0] ) ) {
		
		/* return the caption in a paragraph tag */
		return $pxlcore_before . $pxlcore_thumbnail_image[0]->post_excerpt . $pxlcore_after;
	
	} // end if we have a post
	
}

/***************************************************************
* Function pxlcore_featured_img_title()
* Function to output the featured image title. Pass to it before
* and after tags, such as '<h2>' and '</h2>'
***************************************************************/
function pxlcore_featured_img_title( $pxlcore_before, $pxlcore_after ) {
	
	/* load the global post variable */
	global $post;
	
	/* get the id of the featured image */
	$pxlcore_thumbnail_id = get_post_thumbnail_id( $post->ID );
	
	/* get any attachment posts with the above attachment id i.e. get the post data for the featured image */
	$pxlcore_thumbnail_image = get_posts( array( 'p' => $pxlcore_thumbnail_id, 'post_type' => 'attachment', 'post_status' => 'any' ) );
	
	/* if we have a post returend */
	if( $pxlcore_thumbnail_image && isset( $pxlcore_thumbnail_image[0] ) ) {
		
		/* return the caption in a span tag */
		return $pxlcore_before . $pxlcore_thumbnail_image[0]->post_title . $pxlcore_after;
	
	} // end if we have a post

}

/***************************************************************
* Function pxlcore_var_dump()
* Creates prettier version of the var_dump() php function.
***************************************************************/
function pxlcore_var_dump( $data, $label = '' ) {

	/* check whether we have been provided with a label */
	if( ! empty( $label ) ) {
		
		/* output our label as a heading */
		echo '<h2>' . $label . '</h2>';
		
	}
	
	/* output the normal var_dump wrapped in <pre> for formatting */
	echo '<pre>'; var_dump( $data ); echo '</pre><hr>';
	
	return;

}

/***************************************************************
* Function pxlcore_get_blog_permalink()
* Find and returns the permalink of the page used for blog posts.
***************************************************************/
function pxlcore_get_blog_permalink() {
	
	/* get the post used for pages */
	$pxlcore_blog_page_id = get_option( 'page_for_posts' );
	
	/* check the page id does not equal zero */
	if( $pxlcore_blog_page_id != 0 ) {
		
		/* build permalink of the blog page */
		$pxlcore_blog_permalink = get_permalink( $pxlcore_blog_page_id );
		
		return $pxlcore_blog_permalink;
		
	/* no blog page set */	
	} else {
		
		return false;
		
	}
	
}

/***************************************************************
* Function pxlcore_get_core_users()
* Returns an array of user IDs for all core users.
***************************************************************/
function pxlcore_get_core_users() {
	
	$pxlcore_pixel_users_args = array(
		'meta_key' => 'pxlcore_core_user',
		'meta_value' =>	'1',
		'role' => '',
	);
	
	/* create the user query */
	$pxlcore_pixel_users = new WP_User_Query( $pxlcore_pixel_users_args );
	
	/* check results of user query are not empty */
	if ( ! empty( $pxlcore_pixel_users->results ) ) {

		/* setup an array to store user ids in */
		$pxlcore_user_ids = array();
		
		/* loop through each user returned */
		foreach ( $pxlcore_pixel_users->results as $pxlcore_pixel_user ) {
			
			$pxlcore_user_ids[] = $pxlcore_pixel_user->ID;
			
		}
	
	}
	
	/* reset query */
	wp_reset_query();
		
	/* return array of pixel user ids */
	return $pxlcore_user_ids;
	
}

/***************************************************************
* Function pxlcore_get_current_url()
* Returns the current url.
***************************************************************/
function pxlcore_get_current_url() {
	
	global $wp;
	$current_url = add_query_arg( $wp->query_string, '', home_url( $wp->request ) );
	
	return $current_url;
	
}