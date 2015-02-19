<?php
	/**
	 * Allow for additional boxes
	 */
	do_action( 'easingslider_before_edit_slider_sidebar', $slider );
?>

<!-- Publish -->
<div class="widgets-holder-wrap fixed">
	<div class="sidebar-name">
		<h3><?php _e( 'Publish', 'easingslider' ); ?></h3>
	</div>
	<div class="sidebar-content widgets-sortables clearfix">
		<?php
			/**
			 * Allow for additional options
			 */
			do_action( 'easingslider_publish_metabox_top', $slider );
		?>
		
		<div class="misc-pub-section curtime misc-pub-curtime">
			<span id="timestamp">
				<?php if ( ! empty( $slider->ID ) ) : ?>
					<?php printf( __( 'Published on: <b>%s</b>', 'easingslider' ), date( 'M j, Y @ G:i', strtotime( $slider->post_date ) ) ); ?>
				<?php else : ?>
					<?php printf( __( 'Not published yet.', 'easingslider' ) ); ?>
				<?php endif; ?>
			</span>
		</div>
		
		<?php
			/**
			 * Allow for additional options
			 */
			do_action( 'easingslider_publish_metabox_bottom', $slider );
		?>
	</div>
	<div id="major-publishing-actions">
		<div id="publishing-action">
			<span class="spinner"></span>
			<input type="submit" name="save" class="button button-primary button-large" id="save" accesskey="p" disabled="disabled" value="<?php _e( 'Update', 'easingslider' ); ?>">
		</div>
		<div class="clear"></div>
	</div>
</div>

<!-- Support Easing Slider -->
<?php if ( apply_filters( 'easingslider_show_advert', __return_true() ) ) : ?>
	<?php
		/**
		 * Create the Tweet URL
		 */
		$tweet_url = add_query_arg( array(
			'text'     => __( 'Check out Easing Slider, an easy to use free slider plugin for WordPress.', 'easingslider' ),
			'url'      => 'http://easingslider.com/',
			'via'      => 'EasingSlider',
			'hashtags' => 'wordpress',
		), 'https://twitter.com/intent/tweet' );
	?>

	<div class="widgets-holder-wrap fixed">
		<a href="http://easingslider.com/addons/pro-bundle" target="_blank">
			<img src="<?php echo plugin_dir_url( Easing_Slider::$file ) . 'images/pro-bundle.png'; ?>" alt="\"Pro\" add-on bundle" style="display: block; width: 100%;" />
		</a>

		<div class="sidebar-content widgets-sortables clearfix">
			<p><?php printf( __( 'Like Easing Slider? <br><a href="%s">Support us with a Tweet &raquo;</a>', 'easingslider' ), $tweet_url ); ?>
			<p><?php printf( __( 'Remove this ad? <br><a href="%s" target="_blank">Purchase a premium add-on &raquo;</a>', 'easingslider' ), 'http://easingslider.com/addons' ); ?></p>
		</div>
	</div>
<?php endif; ?>

<!-- Dimensions -->
<div class="widgets-holder-wrap">
	<div class="sidebar-name">
		<div class="sidebar-name-arrow"></div>
		<h3><?php _e( 'Dimensions', 'easingslider' ); ?></h3>
	</div>
	<div class="sidebar-content widgets-sortables clearfix">
		<?php
			/**
			 * Allow for additional options
			 */
			do_action( 'easingslider_dimensions_metabox_top', $slider );
		?>

		<div class="dimension-settings">
			<div class="field half">
				<label for="width">
					<span><?php _e( 'Width:', 'easingslider' ); ?></span>
					<input type="number" name="dimensions[width]" id="width" value="<?php echo esc_attr( $slider->dimensions->width ); ?>">
				</label>
			</div>
			<div class="field half">
				<label for="height">
					<span><?php _e( 'Height:', 'easingslider' ); ?></span>
					<input type="number" name="dimensions[height]" id="height" value="<?php echo esc_attr( $slider->dimensions->height ); ?>">
				</label>
			</div>
			<p class="description"><?php _e( 'Slideshow "width" and "height" values (in pixels).', 'easingslider' ); ?></p>
		</div>

		<div>
			<div class="field">
				<label for="responsive">
					<input type="hidden" name="dimensions[responsive]" value="false">
					<input type="checkbox" name="dimensions[responsive]" id="responsive" value="true" <?php checked( $slider->dimensions->responsive, true ); ?>><span style="display: inline;"><?php _e( 'Make this slider responsive.', 'easingslider' ); ?></span>
				</label>
			</div>
			<p class="description"><?php _e( 'Check this option to make this slider responsive. If enabled, the "width" and "height" values above will act as maximums.', 'easingslider' ); ?></p>
		</div>

		<div>
			<a href="#" class="show-advanced-options"><?php printf( '+ %s', __( 'Advanced Options', 'easingslider' ) ); ?></a>
			<div class="advanced-options hidden">
				<div>
					<div class="field">
						<label for="full_width">
							<input type="hidden" name="dimensions[full_width]" value="false">
							<input type="checkbox" name="dimensions[full_width]" id="full_width" value="true" <?php checked( $slider->dimensions->full_width, true ); ?>><span style="display: inline;"><?php _e( 'Enable 100% full width.', 'easingslider' ); ?></span>
						</label>
					</div>
					<p class="description"><?php _e( 'When enabled, the slider will set its container width to 100%. This option works for responsive sliders only. For best results, it\'s recommend that you disable image resizing when this option is enabled.', 'easingslider' ); ?></p>
				</div>
				<div>
					<div class="field">
						<label for="image_resizing">
							<input type="hidden" name="dimensions[image_resizing]" value="false">
							<input type="checkbox" name="dimensions[image_resizing]" id="image_resizing" value="true" <?php checked( $slider->dimensions->image_resizing, true ); ?>><span style="display: inline;"><?php _e( 'Crop & resize images to fit slider.', 'easingslider' ); ?></span>
						</label>
					</div>
					<p class="description"><?php _e( 'Tick this option to have the slider crop & resize images to fit the slider dimensions. This will only work with images from the WordPress Media Library.', 'easingslider' ); ?></p>
				</div>
				<div>
					<div class="field">
						<label for="keep_ratio">
							<input type="hidden" name="dimensions[keep_ratio]" value="false">
							<input type="checkbox" name="dimensions[keep_ratio]" id="keep_ratio" value="true" <?php checked( $slider->dimensions->keep_ratio, true ); ?>><span style="display: inline;"><?php _e( 'Proportionally resize slider.', 'easingslider' ); ?></span>
						</label>
					</div>
					<p class="description"><?php _e( 'This option enables proportional slider resizing for responsive sliders. This maintains the aspect ratio. Disable this option for fixed height sliders.', 'easingslider' ); ?></p>
				</div>
				<div>
					<div class="field">
						<label for="background_images">
							<input type="hidden" name="dimensions[background_images]" value="false">
							<input type="checkbox" name="dimensions[background_images]" id="background_images" value="true" <?php checked( $slider->dimensions->background_images, true ); ?>><span style="display: inline;"><?php _e( 'Use background images.', 'easingslider' ); ?></span>
						</label>
					</div>
					<p class="description"><?php _e( 'Enable this option if you want the slider to use CSS background images for image slides.', 'easingslider' ); ?></p>
				</div>
			</div>
		</div>

		<?php
			/**
			 * Allow for additional options
			 */
			do_action( 'easingslider_dimensions_metabox_bottom', $slider );
		?>
	</div>
</div>

<!-- Transitions -->
<div class="widgets-holder-wrap closed">
	<div class="sidebar-name">
		<div class="sidebar-name-arrow"></div>
		<h3><?php _e( 'Transitions', 'easingslider' ); ?></h3>
	</div>
	<div class="sidebar-content widgets-sortables clearfix" style="display: none;">
		<?php
			/**
			 * Allow for additional options
			 */
			do_action( 'easingslider_transitions_metabox_top', $slider );
		?>

		<div>
			<div class="field">
				<label for="effect">
					<span><?php _e( 'Effect:', 'easingslider' ); ?></span>
					<select name="transitions[effect]" id="effect">
						<?php foreach ( $slider->get_transitions() as $transition => $label ) : ?>
							<option value="<?php echo esc_attr( $transition ); ?>" <?php selected( $slider->transitions->effect, $transition ); ?>><?php echo esc_html( $label ); ?></option>
						<?php endforeach; ?>
					</select>
				</label>
			</div>
			<p class="description"><?php _e( 'Choose the transition effect you would like to use.', 'easingslider' ); ?></p>
		</div>
		<div class="divider"></div>

		<div>
			<div class="field">
				<label for="duration">
					<span><?php _e( 'Duration:', 'easingslider' ); ?></span>
					<input type="number" name="transitions[duration]" id="duration" value="<?php echo esc_attr( $slider->transitions->duration ); ?>">
				</label>
			</div>
			<p class="description"><?php _e( 'Sets the duration (in milliseconds) for the slideshow transition.', 'easingslider' ); ?></p>
		</div>

		<?php
			/**
			 * Allow for additional options
			 */
			do_action( 'easingslider_transitions_metabox_bottom', $slider );
		?>
	</div>
</div>

<!-- Next & Previous Arrows -->
<div class="widgets-holder-wrap closed">
	<div class="sidebar-name">
		<div class="sidebar-name-arrow"></div>
		<h3><?php _e( 'Next & Previous Arrows', 'easingslider' ); ?></h3>
	</div>
	<div class="sidebar-content widgets-sortables" style="display: none;">
		<?php
			/**
			 * Allow for additional options
			 */
			do_action( 'easingslider_arrows_metabox_top', $slider );
		?>

		<div>
			<div class="radio clearfix">
				<span><?php _e( 'Arrows:', 'easingslider' ); ?></span>
				<div class="buttons">
					<label for="arrows-enable"><input type="radio" name="navigation[arrows]" id="arrows-enable" value="true" <?php checked( $slider->navigation->arrows, true ); ?>>
						<span><?php _e( 'Enable', 'easingslider' ); ?></span>
					</label>
					<label for="arrows-disable"><input type="radio" name="navigation[arrows]" id="arrows-disable" value="false" <?php checked( $slider->navigation->arrows, false ); ?>>
						<span><?php _e( 'Disable', 'easingslider' ); ?></span>
					</label>
				</div>
			</div>
			<p class="description"><?php _e( 'Toggles the next and previous slide arrows.', 'easingslider' ); ?></p>
		</div>
		<div class="divider"></div>

		<div>
			<div class="radio clearfix">
				<span><?php _e( 'On Hover:', 'easingslider' ); ?></span>
				<div class="buttons">
					<label for="arrows-hover-true"><input type="radio" name="navigation[arrows_hover]" id="arrows-hover-true" value="true" <?php checked( $slider->navigation->arrows_hover, true ); ?>>
						<span><?php _e( 'True', 'easingslider' ); ?></span>
					</label>
					<label for="arrows-hover-false"><input type="radio" name="navigation[arrows_hover]" id="arrows-hover-false" value="false" <?php checked( $slider->navigation->arrows_hover, false ); ?>>
						<span><?php _e( 'False', 'easingslider' ); ?></span>
					</label>
				</div>
			</div>
			<p class="description"><?php _e( 'Set to "True" to only show the arrows when the user hovers over the slideshow.', 'easingslider' ); ?></p>
		</div>
		<div class="divider"></div>

		<div>
			<div class="field">
				<label for="arrows_position">
					<span><?php _e( 'Position:', 'easingslider' ); ?></span>
					<select name="navigation[arrows_position]" id="arrows_position">
						<option value="inside" <?php selected( $slider->navigation->arrows_position, 'inside' ); ?>><?php _e( 'Inside', 'easingslider' ); ?></option>
						<option value="outside" <?php selected( $slider->navigation->arrows_position, 'outside' ); ?>><?php _e( 'Outside', 'easingslider' ); ?></option>
					</select>
				</label>
			</div>
			<p class="description"><?php _e( 'Select a position for the arrows.', 'easingslider' ); ?></p>
		</div>

		<?php
			/**
			 * Allow for additional options
			 */
			do_action( 'easingslider_arrows_metabox_bottom', $slider );
		?>
	</div>
</div>

<!-- Pagination Icons -->
<div class="widgets-holder-wrap closed">
	<div class="sidebar-name">
		<div class="sidebar-name-arrow"></div>
		<h3><?php _e( 'Pagination Icons', 'easingslider' ); ?></h3>
	</div>
	<div class="sidebar-content widgets-sortables" style="display: none;">
		<?php
			/**
			 * Allow for additional options
			 */
			do_action( 'easingslider_pagination_metabox_top', $slider );
		?>

		<div>
			<div class="radio clearfix">
				<span><?php _e( 'Pagination:', 'easingslider' ); ?></span>
				<div class="buttons">
					<label for="pagination-enable"><input type="radio" name="navigation[pagination]" id="pagination-enable" value="true" <?php checked( $slider->navigation->pagination, true ); ?>>
						<span><?php _e( 'Enable', 'easingslider' ); ?></span>
					</label>
					<label for="pagination-disable"><input type="radio" name="navigation[pagination]" id="pagination-disable" value="false" <?php checked( $slider->navigation->pagination, false ); ?>>
						<span><?php _e( 'Disable', 'easingslider' ); ?></span>
					</label>
				</div>
			</div>
			<p class="description"><?php _e( 'Enable/Disable the Pagination Icons. Each icon represents a slide in their respective order.', 'easingslider' ); ?></p>
		</div>
		<div class="divider"></div>

		<div>
			<div class="radio clearfix">
				<span><?php _e( 'On Hover:', 'easingslider' ); ?></span>
				<div class="buttons">
					<label for="pagination-hover-true"><input type="radio" name="navigation[pagination_hover]" id="pagination-hover-true" value="true" <?php checked( $slider->navigation->pagination_hover, true ); ?>>
						<span><?php _e( 'True', 'easingslider' ); ?></span>
					</label>
					<label for="pagination-hover-false"><input type="radio" name="navigation[pagination_hover]" id="pagination-hover-false" value="false" <?php checked( $slider->navigation->pagination_hover, false ); ?>>
						<span><?php _e( 'False', 'easingslider' ); ?></span>
					</label>
				</div>
			</div>
			<p class="description"><?php _e( 'Set to "True" to only show the pagination when the user hovers over the slideshow.', 'easingslider' ); ?></p>
		</div>
		<div class="divider"></div>

		<div>
			<div class="field">
				<label for="pagination_position">
					<span><?php _e( 'Position:', 'easingslider' ); ?></span>
					<select name="navigation[pagination_position]" id="pagination_position" style="width: 45%; float: left;">
						<option value="inside" <?php selected( $slider->navigation->pagination_position, 'inside' ); ?>><?php _e( 'Inside', 'easingslider' ); ?></option>
						<option value="outside" <?php selected( $slider->navigation->pagination_position, 'outside' ); ?>><?php _e( 'Outside', 'easingslider' ); ?></option>
					</select>
					<select name="navigation[pagination_location]" id="pagination_location" style="width: 45%; float: left; margin-left: 10px;">
						<option value="top-left" <?php selected( $slider->navigation->pagination_location, 'top-left' ); ?>><?php _e( 'Top Left', 'easingslider' ); ?></option>
						<option value="top-right" <?php selected( $slider->navigation->pagination_location, 'top-right' ); ?>><?php _e( 'Top Right', 'easingslider' ); ?></option>
						<option value="top-center" <?php selected( $slider->navigation->pagination_location, 'top-center' ); ?>><?php _e( 'Top Center', 'easingslider' ); ?></option>
						<option value="bottom-left" <?php selected( $slider->navigation->pagination_location, 'bottom-left' ); ?>><?php _e( 'Bottom Left', 'easingslider' ); ?></option>
						<option value="bottom-right" <?php selected( $slider->navigation->pagination_location, 'bottom-right' ); ?>><?php _e( 'Bottom Right', 'easingslider' ); ?></option>
						<option value="bottom-center" <?php selected( $slider->navigation->pagination_location, 'bottom-center' ); ?>><?php _e( 'Bottom Center', 'easingslider' ); ?></option>
					</select>
				</label>
			</div>
			<p class="description"><?php _e( 'Select a position for the pagination icons.', 'easingslider' ); ?></p>
		</div>

		<?php
			/**
			 * Allow for additional options
			 */
			do_action( 'easingslider_pagination_metabox_bottom', $slider );
		?>
	</div>
</div>

<!-- Playback -->
<div class="widgets-holder-wrap closed">
	<div class="sidebar-name">
		<div class="sidebar-name-arrow"></div>
		<h3><?php _e( 'Automatic Playback', 'easingslider' ); ?></h3>
	</div>
	<div class="sidebar-content widgets-sortables" style="display: none;">
		<?php
			/**
			 * Allow for additional options
			 */
			do_action( 'easingslider_playback_metabox_top', $slider );
		?>

		<div>
			<div class="radio clearfix">
				<span><?php _e( 'Playback:', 'easingslider' ); ?></span>
				<div class="buttons">
					<label for="playback-enable"><input type="radio" name="playback[enabled]" id="playback-enable" value="true" <?php checked( $slider->playback->enabled, true ); ?>>
						<span><?php _e( 'Enable', 'easingslider' ); ?></span>
					</label>
					<label for="playback-disable"><input type="radio" name="playback[enabled]" id="playback-disable" value="false" <?php checked( $slider->playback->enabled, false ); ?>>
						<span><?php _e( 'Disable', 'easingslider' ); ?></span>
					</label>
				</div>
			</div>
			<p class="description"><?php _e( 'Enable/Disable slideshow automatic playback.', 'easingslider' ); ?></p>
		</div>
		<div class="divider"></div>
		
		<div>
			<div class="field">
				<label for="playback_pause">
					<span><?php _e( 'Pause Duration:', 'easingslider' ); ?></span>
					<input type="number" name="playback[pause]" id="playback_pause" value="<?php echo esc_attr( $slider->playback->pause ); ?>">
				</label>
			</div>
			<p class="description"><?php _e( 'Sets the duration (in milliseconds) for the pause between slide transitions.', 'easingslider' ); ?></p>
		</div>

		<?php
			/**
			 * Allow for additional options
			 */
			do_action( 'easingslider_playback_metabox_bottom', $slider );
		?>
	</div>
</div>

<?php
	/**
	 * Allow for additional boxes
	 */
	do_action( 'easingslider_after_edit_slider_sidebar', $slider );
?>