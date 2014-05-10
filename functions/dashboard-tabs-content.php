<?php
/***************************************************************
* function pxlcore_dashboard_welcome_tab
* Outputs the content for the dashboard welcome tab
***************************************************************/
function pxlcore_dashboard_welcome_tab() {
	
	global $wp_version;
	
	/* get todays date */
	$pxlcore_date = date( '' );
	
	/* get the hosting renewal date */
	$pxlcore_hosting_renewal_date = get_option( 'pxlcore_hosting_renewal_date' );
	
	/* get the aftercare end date */
	$pxlcore_aftercare_date = get_option( 'pxlcore_aftercare_end_date' );
	
	/* check whether hosting renewal date has ellapsed */
	if( time() > strtotime( $pxlcore_hosting_renewal_date ) ) {
		$pxlcore_hosting_class = ' red';
	} else {
		$pxlcore_hosting_class = '';
	}
	
	/* check whether hosting renewal date has ellapsed */
	if( time() > strtotime( $pxlcore_aftercare_date ) ) {
		$pxlcore_aftercare_class = ' red';
	} else {
		$pxlcore_aftercare_class = '';
	}
	
	?>
	
	<div id="pixel-welcome" class="pxlcore-tab-content" style="display:block;">
	
		<?php
		
			/***************************************************************
			* @hook pxlcore_before_welcome_tab_content
			***************************************************************/
			do_action( 'pxlcore_before_welcome_tab_content' );
		
		?>
			
		<div class="col col-half pixel-welcome-text">
			<h3><?php echo apply_filters( 'pxlcore_thanks_heading', 'Thank you for choosing Pixel Junction.' ); ?></h3>
			<p class="pxlcore-thankyou-text">Thank you for choosing Pixel Junction to build your website; we appreciate your business and have enjoyed working with you.</p>
			
			<h4 class="pixel-important-info">Important Information</h4>
			
			<p>Below are some important dates about your account.</p>
			
			<ul class="pixel-important-info-list">
			
				<li class="aftercare-date<?php echo esc_attr( $pxlcore_aftercare_class ); ?>"><span class="label">Aftercare ends on:</span> <span class="value"><?php echo esc_html( $pxlcore_aftercare_date ); ?></span></li>
				
				<?php
				
					/* check whether a hosting renewal date is added */
					if( ! empty( $pxlcore_hosting_renewal_date ) ) {
						
						?>
						<li class="hosting-renewal-date<?php echo esc_attr( $pxlcore_hosting_class ); ?>"><span class="label">Hosting renewal date:</span> <span class="value"><?php echo esc_html( $pxlcore_hosting_renewal_date ); ?></span></li>
						<?php
						
					}
				
				?>
			
			</ul>
			
		</div>
		
		<div class="col col-half col-last pixel-welcome-members">
		
			<h3>Your Pixel Junction Team</h3>
		
			<?php
				
				/* get user ids of all pixel users */
				$pxlcore_pxl_users = pxlcore_get_core_users();
				
				/* check we have some users to show */
				if( ! empty( $pxlcore_pxl_users ) ) {
					
					?>
					
					<ul class="pixel-members">
						
						<?php
						
							/* loop through each user */
							foreach( $pxlcore_pxl_users as $pxlcore_pxl_user ) {
								
								?>
								
								<li class="pixel-member pixel-member-<?php echo esc_attr( $pxlcore_pxl_user ); ?>">
								
									<div class="pixel-member-avatar">
										<?php echo get_avatar( $pxlcore_pxl_user, 150 ); ?>
										<h4 class="pixel-member-name"><?php echo get_user_meta( $pxlcore_pxl_user, 'first_name', true ); ?> <?php echo get_user_meta( $pxlcore_pxl_user, 'last_name', true ); ?></h4>
									</div>
								
								</li>
								
								<?php
								
							} // end loop through users			
						
						?>
						
					</ul>
					
					<?php
					
				}
			
			?>
		
		</div>
		
		<div class="clearfix"></div>
		
		<?php
		
			/***************************************************************
			* @hook pxlcore_after_welcome_tab_content
			***************************************************************/
			do_action( 'pxlcore_after_welcome_tab_content' );
		
		?>
		
	</div><!-- // pixel-team-welcome -->
	
	<?php
	
}

/***************************************************************
* function pxlcore_dashboard_services_tab
* Outputs the content for the dashboard services tab
***************************************************************/
function pxlcore_dashboard_services_tab() {

	?>
	
		<div id="pixel-services" class="pxlcore-tab-content">
			
			<?php
		
				/***************************************************************
				* @hook pxlcore_before_welcome_tab_content
				***************************************************************/
				do_action( 'pxlcore_before_services_tab_content' );
			
			?>
			
			<h3><?php echo apply_filters( 'pxlcore_services_heading', 'We have a lot more to offer - take a look!' ); ?></h3>
			
			<p>We do more than just websites! We offer a number of additional web and graphic design services too. Take a look below to see what we offer. Please feel free to get in touch if you would like further information on these services.</p>
			
			<div class="col col-half pxlcore-service-type pxlcore-service-webdesign">
			
				<h3 class="pixel-service-type-title">Web Design</h3>
				
				<div class="col col-half pixel-service">
				
					<img class="service-icon" src="<?php echo esc_url( plugins_url( 'images/wp-websites.png', dirname( __FILE__ ) ) ); ?>" alt="WordPress Websites" />
					
					<h4 class="pixel-service-title">WordPress Websites</h4>
				
				</div>
				
				<div class="col col-half col-last pixel-service">
				
					<img class="service-icon" src="<?php echo esc_url( plugins_url( 'images/mobile-websites.png', dirname( __FILE__ ) ) ); ?>" alt="WordPress Websites" />
					
					<h4 class="pixel-service-title">Mobile Websites</h4>
				
				</div>
				
				<div class="clearfix"></div>
				
				<div class="col col-half pixel-service">
				
					<img class="service-icon" src="<?php echo esc_url( plugins_url( 'images/wp-plugins.png', dirname( __FILE__ ) ) ); ?>" alt="WordPress Websites" />
					
					<h4 class="pixel-service-title">WordPress Plugins</h4>
				
				</div>
				
				<div class="col col-half col-last pixel-service">
				
					<img class="service-icon" src="<?php echo esc_url( plugins_url( 'images/wp-support.png', dirname( __FILE__ ) ) ); ?>" alt="WordPress Websites" />
					
					<h4 class="pixel-service-title">WordPress Support</h4>
				
				</div>
				
				<div class="clearfix"></div>
			
			</div>
			
			<div class="col col-half col-last pxlcore-service-type pxlcore-service-graphicdesign">
			
				<h3 class="pixel-service-type-title">Graphic Design</h3>
				
				<div class="col col-half pixel-service">
				
					<img class="service-icon" src="<?php echo esc_url( plugins_url( 'images/logo-design.png', dirname( __FILE__ ) ) ); ?>" alt="WordPress Websites" />
					
					<h4 class="pixel-service-title">Logo Design</h4>
				
				</div>
				
				<div class="col col-half col-last pixel-service">
				
					<img class="service-icon" src="<?php echo esc_url( plugins_url( 'images/branding.png', dirname( __FILE__ ) ) ); ?>" alt="WordPress Websites" />
					
					<h4 class="pixel-service-title">Branding</h4>
				
				</div>
				
				<div class="clearfix"></div>
				
				<div class="col col-half pixel-service">
				
					<img class="service-icon" src="<?php echo esc_url( plugins_url( 'images/brochures-reports.png', dirname( __FILE__ ) ) ); ?>" alt="WordPress Websites" />
					
					<h4 class="pixel-service-title">Brochures & Reports</h4>
				
				</div>
				
				<div class="col col-half col-last pixel-service">
				
					<img class="service-icon" src="<?php echo esc_url( plugins_url( 'images/stationery-posters.png', dirname( __FILE__ ) ) ); ?>" alt="WordPress Websites" />
					
					<h4 class="pixel-service-title">Stationery & Posters</h4>
				
				</div>
				
				<div class="clearfix"></div>
			
			</div>
			
			<div class="clearfix"></div>
			
			<?php
		
				/***************************************************************
				* @hook pxlcore_after_welcome_tab_content
				***************************************************************/
				do_action( 'pxlcore_after_services_tab_content' );
			
			?>
		
		</div>
	
	<?php
	
}

/***************************************************************
* function pxlcore_dashboard_support_tab
* Outputs the content for the dashboard support tab
***************************************************************/
function pxlcore_dashboard_support_tab() {
	
	?>
	
		<div id="pixel-support" class="pxlcore-tab-content">
		
			<?php
		
				/***************************************************************
				* @hook pxlcore_before_welcome_tab_content
				***************************************************************/
				do_action( 'pxlcore_before_support_tab_content' );
			
			?>
		
			<h3><?php echo apply_filters( 'pxlcore_support_heading', 'Help is on hand, support when you need it' ); ?></h3>
			
			<div class="pixel-about-support-hub">
			
				<p>Content here...</p>
			
			</div>
			
			<?php
		
				/***************************************************************
				* @hook pxlcore_after_welcome_tab_content
				***************************************************************/
				do_action( 'pxlcore_after_support_tab_content' );
			
			?>
		
		</div>
	
	<?php
	
}