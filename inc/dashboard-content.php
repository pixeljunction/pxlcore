<?php global $wp_version; ?>
<div class="wrap about-wrap pxlcore-dashboard-wrap">

	<h1><?php bloginfo( 'name' ); ?> - Dashboard</h1>
	
	<div class="about-text"><?php echo apply_filters( 'pxlcore_welcome_text', 'Welcome to your WordPress website, designed & developed by Pixel Junction.' ); ?></div>
	
	<div class="pxlcore-badge"><?php printf( __( 'Version %s' ), $wp_version ); ?></div>
	
	<div class="pxlcore-tabs-wrapper">
	
		<ul class="pxlcore-tabs">
			
			<?php
			
				/* set an array of tabs */
				$pxlcore_dashboard_tabs = apply_filters(
					'pxlcore_dashboard_tabs',
					array(
						'welcome' => array(
							'id' => '#pixel-welcome',
							'label' => 'Welcome',
						),
						'services' => array(
							'id' => '#pixel-services',
							'label' => 'Services',
						),
						'support' => array(
							'id' => '#pixel-support',
							'label' => 'Support',
						),
						'offers' => array(
							'id' => '#pixel-offers',
							'label' => 'Latest Offers',
						),
					)
				);
				
				/* check we have items to show */
				if( ! empty( $pxlcore_dashboard_tabs ) ) {
					
					/* loop through each item */
					foreach( $pxlcore_dashboard_tabs as $pxlcore_dashboard_tab ) {
						
						?>
						<li><a href="<?php echo esc_attr( $pxlcore_dashboard_tab[ 'id' ] ); ?>"><?php echo esc_html( $pxlcore_dashboard_tab[ 'label' ] ); ?></a></li>
						<?php
						
					}
					
				}
			
			?>
			
		</ul>
	
		<?php
		
			/* set an array of tab content blocks */
			$pxlcore_dashboard_tabs_contents = apply_filters(
				'pxlcore_dashboard_tabs_contents',
				array(
					'welcome' => array(
						'callback' => 'pxlcore_dashboard_welcome_tab',
					),
					'services' => array(
						'callback' => 'pxlcore_dashboard_services_tab',
					),
					'support' => array(
						'callback' => 'pxlcore_dashboard_support_tab',
					),
					'offers' => array(
						'callback' => 'pxlcore_dashboard_offers_tab',
					),
				)
			);
			
			/* check we have items to show */
			if( ! empty( $pxlcore_dashboard_tabs_contents ) ) {
			
				/* loop through each item */
				foreach( $pxlcore_dashboard_tabs_contents as $pxlcore_dashboard_tabs_content ) {
					
					/* run the callback function for showing the content */
					$pxlcore_dashboard_tabs_content[ 'callback' ]();
				
				}
			
			}
		
		?>
	
	</div><!-- // pxlcore-tabs -->

</div>