<?php
/* adds a lastpost class to thefinal post in the loop */
add_filter('post_class', 'pxlcore_lastpost_class', 99);
function pxlcore_lastpost_class($classes){
	global $wp_query;
	if(($wp_query->current_post+1) == $wp_query->post_count) $classes[] = 'lastpost';
	return $classes;
}

/* adds a post counter class to the post_class function */
add_filter('post_class', 'pxlcore_post_counter_class', 90);
function pxlcore_post_counter_class($classes){
	global $wp_query;
	$pxlcore_post_count_number = $wp_query->current_post +1;
	$classes[] = 'post-count-'. $pxlcore_post_count_number;
	return $classes;
}

/* add "first" and "last" CSS classes to widgets. Also adds numeric index class for each widget (widget-1, widget-2, etc.) */
function pxlcore_widget_first_last_classes($params) {
	global $my_widget_num; // Global a counter array
	$this_id = $params[0]['id']; // Get the id for the current sidebar we're processing
	$arr_registered_widgets = wp_get_sidebars_widgets(); // Get an array of ALL registered widgets	
	if(!$my_widget_num) {// If the counter array doesn't exist, create it
		$my_widget_num = array();
	}
	if(!isset($arr_registered_widgets[$this_id]) || !is_array($arr_registered_widgets[$this_id])) { // Check if the current sidebar has no widgets
		return $params; // No widgets in this sidebar... bail early.
	}
	if(isset($my_widget_num[$this_id])) { // See if the counter array has an entry for this sidebar
		$my_widget_num[$this_id] ++;
	} else { // If not, create it starting with 1
		$my_widget_num[$this_id] = 1;
	}
	$class = 'class="widget-' . $my_widget_num[$this_id] . ' '; // Add a widget number class for additional styling options
	if($my_widget_num[$this_id] == 1) { // If this is the first widget
		$class .= 'widget-first ';
	} elseif($my_widget_num[$this_id] == count($arr_registered_widgets[$this_id])) { // If this is the last widget
		$class .= 'widget-last ';
	}
	$params[0]['before_widget'] = str_replace('class="', $class, $params[0]['before_widget']); // Insert our new classes into "before widget"
	return $params;
}
add_filter('dynamic_sidebar_params','pxlcore_widget_first_last_classes');