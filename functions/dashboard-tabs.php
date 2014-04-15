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
			<p class="pxlcore-thankyou-text">The team at Pixel Junction would like to thank you for choosing Pixel Junction to create your website. The Pixel team members here will be working on your helping with your website.</p>
			
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
				$pxlcore_pxl_users = pxlcore_get_pixel_users();
				
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
									</div>
									
									<div class="pixel-member-info">
									
										<h4 class="pixel-member-name"><?php echo get_user_meta( $pxlcore_pxl_user, 'first_name', true ); ?> <?php echo get_user_meta( $pxlcore_pxl_user, 'last_name', true ); ?></h4>
										
										<p class="pixel-member-desription"><?php echo wpautop( get_user_meta( $pxlcore_pxl_user, 'description', true ) ); ?></p>
									
									</div>
									
									<div class="clearfix"></div>
								
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
			
			<p>We do more than just websites! We offer a number of additional website design services as well as a whole host of graphic design offerings too. Take a look at what we can offer you below.</p>
			
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
			
				<p>At Pixel Junction we realise that you are always going to need a little helping hand with your website, so that you can get the most out of it. This is why we offer you 21 days free after care. After that many clients worry about what ongoing costs there may be.</p>
				
				<h4 class="pixel-content-title">What are my Support Options?</h4>
				
				<p>You essentially have two options:</p>
				
				<h5 class="pixel-content-title">Pixel Support Hub Plan</h5>
				
				<p>Experience tells us the best websites are those that are flexible with dynamic content to keep the user interested. Sometimes you will find you want to change things outside of the scope of the CMS and for this you may well need our help. To assist you in this we offer some fixed price support plans that allow you a set amount of support time for one of your designers or developers to work on your site. The added bonus with our support hub plans, is that the time can be used in shorter 10 minutes block as opposed to 1 hour slots.</p>
				
				<p>These plans work on a ticketing system. If you have a support request simply create hub ticket which will be assigned to one of our team and you will be notified once the tasks is completed. You can track the time you have used in your plan easily in the system.</p>
				
				<p>The following Pixel Support Hub Plans are available:</p>
				
				<ul>
				
					<li><strong>Starter</strong>: 2 hours for £80</li>
					<li><strong>Bronze</strong>: 4 hours for £150</li>
					<li><strong>Silver</strong>: 8 hours for £280</li>
					<li><strong>Gold</strong>: 16 hours for £520</li>
				
				</ul>
				
				<p>Plans with greater time can be offered with increasing discounts for the more time in the plan. Please feel free to discuss this with your account manager.</p>
				
				<h5 class="pixel-content-title">Hourly Rate Pay as You Go</h5>
				
				<p>Some sites need little updating throughout their lifetime. Therefore it is likely that they won't need much support as content won't change and new features may not be necessary.</p>

			
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

/***************************************************************
* function pxlcore_dashboard_offers_tab
* Outputs the content for the dashboard support tab
***************************************************************/
function pxlcore_dashboard_offers_tab() {
	
	?>
	
		<div id="pixel-offers" class="pxlcore-tab-content">
		
			<?php
		
				/***************************************************************
				* @hook pxlcore_before_welcome_tab_content
				***************************************************************/
				do_action( 'pxlcore_before_offers_tab_content' );
			
			?>
		
			<h3><?php echo apply_filters( 'pxlcore_offers_heading', 'Something to tempt you with - we hope!' ); ?></h3>
			
			<?php
		
				/***************************************************************
				* @hook pxlcore_after_welcome_tab_content
				***************************************************************/
				do_action( 'pxlcore_after_offers_tab_content' );
			
			?>
		
		</div>
	
	<?php
	
}