<?php
/***************************************************************
* Function pxlcore_add_submenu_page()
* add a submenu item labelled 'Description' to each public post
* type (filterable)
***************************************************************/
function pxlcore_add_submenu_page() {
	
	/* get a list of the post types */
	$post_types = pxlcore_get_post_types();

	/* loop through each post type */
	foreach( $post_types as $post_type => $post_type_obj) {
		
		/* add the menu item */
		$parent_slug = 'edit.php?post_type='.$post_type;
		$page_title  = $post_type_obj->labels->name . ' description';
		$menu_title  = 'Description';
		$capability  = $post_type_obj->cap->manage_options;
		$menu_slug	 = $post_type_obj->name . '-description';
		$function    = 'pxlcore_manage_description';

		add_submenu_page(
			$parent_slug,
			$page_title,
			$menu_title,
			$capability,
			$menu_slug,
			$function
		);

	}
}

add_action( 'admin_menu', 'pxlcore_add_submenu_page' );

/***************************************************************
* Function pxlcore_manage_description()
* Adds the content of the admin page to edit the description
***************************************************************/
function pxlcore_manage_description() {
	
	/* if we don't have a post type go no further */
	if ( empty( $_GET['post_type'] ) )
		return;
		
	/* get the post type object - we have one! */
	$post_type = get_post_type_object( $_GET[ 'post_type' ] ); 
	
	/* if a current description is already added get it */
	$current_description = stripslashes( get_option( $post_type->name . '-description' ) ); 

	?>
	<h2><?php echo esc_html( $post_type->labels->name ); ?> Description</h2>

	<?php if ( isset( $_GET['updated'] ) && $_GET['updated'] ) { ?>

		<div id="message" class="updated">
			<p>Description Updated.</p>
		</div>

	<?php } ?>

	<form method="POST">
		<div style="width: 95%; margin-top: 50px;">
			<?php
				/* use a tinymce editor */
				wp_editor(
					$current_description,
					'description',
					$settings = array(
						'textarea_rows' => 10,
					)
				);
			?>
		</div>

		<input type="hidden" name="post_type" value="<?php echo esc_attr( $post_type->name ); ?>" />
		
		<p class="submit">
			<input class="button-primary" type="submit" name="pxlcore_update_description" value="Update Description"/>
		</p>

	</form>


<?php }

/***************************************************************
* Function pxlcore_update_description()
* Updates/saves the added description
***************************************************************/
function pxlcore_update_description() {

	if( isset( $_POST[ 'pxlcore_update_description' ] ) ) {
		
		/* get the posted values for post type and description */
		$post_type = $_POST[ 'post_type' ];
		$description = $_POST[ 'description' ];
		
		/* update the option using the new description entered */
		update_option( $post_type . '-description', $description );
		
		/* redirect the user to description admin page with added query vars */
		wp_redirect(
			add_query_arg(
				array(
					'post_type' => $post_type,
					'page' => $post_type . '-description',
					'updated' => 'true',
					'post_type' => $post_type
				),
				$wp_get_referer
			)
		);
		exit;

	}

}

add_action( 'init', 'pxlcore_update_description' );

/***************************************************************
* Function pxlcore_description()
* front end function to display the description in the template
***************************************************************/
function pxlcore_description() {
	
	/* get the current post type for this archive page */
	$post_type = get_query_var( 'post_type' );
	
	/* get the saved description from the options table */
	$post_type_description = stripslashes( get_option( $post_type . '-description' ) );

	/* outout the description, running it through the content for wpautop */
	echo apply_filters( 'the_content', $post_type_description );

}

/***************************************************************
* Function pxlcore_description()
* helper function to get all the post types (filterable)
***************************************************************/
function pxlcore_get_post_types() {

	$post_types = get_post_types(
		array(
			'public' => true,
			'show_ui' => true,
		),
		'objects'
	);

	/* allow post types to be filterable */
	$post_types = apply_filters( 'pxlcore_enabled_post_types', $post_types );

	return $post_types;

}

/***************************************************************
* Function pxlcore_description()
* helper function to get an array of post types
***************************************************************/
function pxlcore_get_enabled_post_type_array() {
	
	/* create an empty array to add to */
	$post_types_array = array();

	/* get our post types list */
	$post_types = pxlcore_get_post_types();
	
	/* loop through each returned post type */
	foreach( $post_types as $post_type => $post_type_obj ) {
		
		/* add the post type to our post type array */
		$post_types_array[] = $post_type;

	}
	
	/* return the array of post types */
	return $post_types_array;

}

/***************************************************************
* Function pxlcore_description()
* remove post types from having a description
***************************************************************/
function pxlcore_remove_pages_post_type($post_types) {

    unset( $post_types[ 'page' ] );
    return $post_types;
    
}

add_filter( 'pxlcore_enabled_post_types', 'pxlcore_remove_pages_post_type' );