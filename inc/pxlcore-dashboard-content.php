<div class="wrap">
	
	<?php
		
		/* show a screen icon next to the title - same one as the normal dashboard */
		screen_icon( 'index' );
	
	?>
	
	<h2>Dashboard</h2>
	
	<?php
		
		/* include the dashboard content file from the theme if there is one */
		get_template_part( 'dashboard', 'content' );
		
		/* get the transient for the dashboard file */
		$pxlcore_dashboard_content = get_transient( 'pxlcore_dashboard' );
		
		/* check we have any content in our transient */
		if( $pxlcore_dashboard_content ) {
			
			/* we already have content - so lets use that */
			echo $pxlcore_dashboard_content;
		
		/* no content in transient - must have expired */
		} else {
			
			/* get the pixel dashboard content file */
			$pxlcore_dashboard_content = wp_remote_get( apply_filters( 'pxlcore_dashboard_remote_get', 'http://content.pixeljunction.co.uk/dashboard.html' ) );
			
			/* check whether there is an error in the remote repsonse */
			if( is_wp_error( $pxlcore_dashboard_content ) ) {
				
				/* get the error message from the repsonse */
				$pxlcore_error_message = $pxlcore_dashboard_content->get_error_message();
				
				/* output the error */
				echo "Something went wrong: $pxlcore_error_message";
			
			/* there is no error in the repsonse - therefore we have received the page OK */
			} else {
				
				/* lets store the content in our transient for 24 hours */
				set_transient( 'pxlcore_dashboard', $pxlcore_dashboard_content[ 'body' ], 60*60*24 );
				
				/* output the content of our new transient */
				$pxlcore_dashboard_content = get_transient( 'pxlcore_dashboard' );
				echo $pxlcore_dashboard_content;
				
			}
			
		}
		
	?>

</div>