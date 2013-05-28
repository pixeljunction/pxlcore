<div class="wrap">
	
	<?php
		
		/* show a screen icon next to the title - same one as the normal dashboard */
		screen_icon( 'index' );
	
	?>
	
	<h2>Dashboard</h2>
	
	<?php
		
		/* include the dashboard content file from the theme if there is one */
		get_template_part( 'dashboard', 'content' );
		
		/* get the pixel dashboard content file */
		$pxlcore_dashboard_content = wp_remote_get( 'http://content.pixeljunction.co.uk/dashboard.html' );
		
		/* check whether there is an error in the remote repsonse */
		if( is_wp_error( $pxlcore_dashboard_content ) ) {
			
			/* get the error message from the repsonse */
			$pxlcore_error_message = $pxlcore_dashboard_content->get_error_message();
			
			/* output the error */
			echo "Something went wrong: $pxlcore_error_message";
		
		/* there is no error in the repsonse */
		} else {
			
			/* out the body of the retreived page */
			echo $pxlcore_dashboard_content[ 'body' ];
			
		}
		
	?>

</div>