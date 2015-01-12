<p class="about-description">
	<?php _e( 'Use the tips below to get started using Easing Slider. It\'s super quick and easy!', 'easingslider' ); ?>
</p>

<div class="changelog">
	<div class="feature-section col two-col">
		<div class="col-1">
			<h3><?php _e( 'Creating Your First Slider', 'easingslider' ); ?></h3>

			<h4><a href="<?php echo admin_url( 'admin.php?page=easingslider_edit_sliders' ); ?>"><?php _e( 'Sliders &rarr; Add New', 'easingslider' ); ?></a></h4>
			<p><?php _e( 'The "Sliders" menu is your access point for all aspects of Easing Slider. Simply click "Add New" to create your first slider and start adding slides.', 'easingslider' ); ?></p>

			<h4><?php _e( 'Adding Slides', 'easingslider' ); ?></h4>
			<p><?php printf( __( 'Click the "Add Slides" button to add image slides from the WordPress Media Library. Other slide types can be purchased from our vast array of extensions <a href="%s">found here</a>.', 'easingslider' ), 'http://easingslider.com/extensions' ); ?></p>
		</div>

		<div class="col-2 last-feature">
			<img src="<?php echo "{$image_dir}/welcome-screenshot.jpg"; ?>" alt="<?php _e( 'Welcome Screenshot', 'easingslider' ); ?>" class="welcome-screenshot">
		</div>
	</div>
</div>
<hr>

<div class="changelog">
	<h3><?php _e( 'Displaying A Slider', 'easingslider' ); ?></h3>

	<div class="feature-section col three-col">
		<div class="col-1">
			<img src="<?php echo "{$image_dir}/shortcode-screenshot.jpg"; ?>" alt="<?php _e( 'Shortcode Screenshot', 'easingslider' ); ?>" class="shortcode-screenshot">
			<h4><?php _e( 'In a post', 'easingslider' ); ?></h4>
			<p>Use the "Add Slider" button within the post editor to add a slider to a post or page. For reference, the shortcode can be found below. Be sure to replace the numeric ID (1) with the ID of the slider you wish to show.</p>
			<code>[easingslider id="1"]</code>
		</div>

		<div class="col-2">
			<img src="<?php echo "{$image_dir}/function-screenshot.jpg"; ?>" alt="<?php _e( 'PHP Function Screenshot', 'easingslider' ); ?>" class="function-screenshot">
			<h4>PHP Function</h4>
			<p>You can also display a slider inside a theme file using the PHP function below. Again, be sure to replace the numeric ID (1) with the desired slider ID.</p>
			<code>&lt;?php if ( function_exists( 'easingslider' ) ) { easingslider( 1 ); } ?&gt;</code>
		</div>
		<div class="col-3 last-feature">
			<img src="<?php echo "{$image_dir}/widget-screenshot.jpg"; ?>" alt="<?php _e( 'Widget Screenshot', 'easingslider' ); ?>" class="widget-screenshot">
			<h4>Widget</h4>
			<p>Easing Slider provides a widget that you can place in any widgetized area of your site and select exactly which form you would like displayed in that space.</p>
		</div>
	</div>
</div>
<hr>

<div class="changelog">
	<h3><?php _e( 'Need help?', 'easingslider' ); ?></h3>

	<div class="feature-section col two-col">
		<div class="col-1">
			<h4><?php _e( 'Helpful Documentation', 'easingslider' ); ?></h4>
			<p><?php printf( __( 'We have documentation available covering everything from <a href="%s">Getting Started</a> to our <a href="%s">Developer API</a>. New documents and tutorials are added daily.', 'easingslider' ), 'http://easingslider.com/docs/installation', 'http://easingslider.com/docs/developer-api' ); ?></p>
		</div>

		<div class="col-2 last-feature">
			<h4><?php _e( 'Awesome Support', 'easingslider' ); ?></h4>
			<p><?php printf( __( 'We work hard to ensure every user gets the best support possible. If you\'ve encountered a problem or have a question, don\'t hesitate to <a href="%s">contact us</a>.', 'easingslider' ), 'http://easingslider.com/support' ); ?></p>
		</div>
	</div>
</div>