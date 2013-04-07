<?php
/***************************************************************
* Function pxlcore_lastpost_class()
* Adds a class of lastpost to the post_class in any loop query
***************************************************************/
function pxlcore_lastpost_class( $classes ) {
	
	/* loads the global $wp_query variable for the current query */
	global $wp_query;
	
	/* check if 1 higher than the current post count is the same as the post count */
	if( ( $wp_query->current_post+1 ) == $wp_query->post_count )
		
		/* add lastpost to the classes array */
		$classes[] = 'lastpost';
	
	/* return the classes array */
	return $classes;
	
}

add_filter( 'post_class', 'pxlcore_lastpost_class', 99 );

/***************************************************************
* Function pxlcore_post_counter_class()
* Adds a class of lastpost to the post_class in any loop query
***************************************************************/
function pxlcore_post_counter_class( $classes ) {
	
	/* loads the global $wp_query variable for the current query */
	global $wp_query;
	
	/* store the current posts number plus one in variable */
	$pxlcore_post_count_number = $wp_query->current_post +1;
	
	/* add the new class to the post classes array */
	$classes[] = 'post-count-'. $pxlcore_post_count_number;
	
	/* return the modified post class array */
	return $classes;
	
}

add_filter( 'post_class', 'pxlcore_post_counter_class', 90 );

/***************************************************************
* Function pxlcore_widget_counter_classes()
* Adds "first" and "last" CSS classes to widgets. Also adds
* numeric index class for each widget (widget-1, widget-2, etc.)
***************************************************************/
function pxlcore_widget_counter_classes( $params ) {
	
	/* global a counter array */
	global $my_widget_num;
	
	/* get the id for the current sidebar we're processing */
	$this_id = $params[0]['id'];
	
	/* get an array of ALL registered widgets */
	$arr_registered_widgets = wp_get_sidebars_widgets();
	
	/* if the counter array doesn't exist */
	if(!$my_widget_num) {
		
		/* create it */
		$my_widget_num = array();
		
	}
	
	/* check if the current sidebar has no widgets */
	if( ! isset( $arr_registered_widgets[ $this_id ] ) || ! is_array( $arr_registered_widgets[ $this_id ] ) ) {
		
		/* no widgets in this sidebar... bail early */
		return $params;
		
	}
	
	/* see if the counter array has an entry for this sidebar */
	if( isset( $my_widget_num[ $this_id ] ) ) {
		
		$my_widget_num[$this_id] ++;
	
	/* if not, create it starting with 1 */
	} else {
	
		$my_widget_num[$this_id] = 1;
		
	}
	
	/* add a widget number class for additional styling options */
	$class = 'class="widget-' . $my_widget_num[$this_id] . ' ';
	
	/* if this is the first widget */
	if( $my_widget_num[ $this_id ] == 1 ) {
	
		$class .= 'widget-first ';
	
	/* if this is the last widget */
	} elseif( $my_widget_num[ $this_id ] == count( $arr_registered_widgets[ $this_id ] ) ) {
	
		$class .= 'widget-last ';
		
	}
	
	/* insert our new classes into "before widget" */
	$params[0]['before_widget'] = str_replace( 'class="', $class, $params[0]['before_widget'] ); 
	
	return $params;
	
}

add_filter( 'dynamic_sidebar_params', 'pxlcore_widget_counter_classes' );