<?php
	/**
	 * Allow for additional boxes
	 */
	do_action('easingslider_before_edit_slider_sidebar', $slider);
?>

<!-- Publish -->
<div class="widgets-holder-wrap fixed">
	<div class="sidebar-name">
		<h3><?php _e('Publish', 'easingslider'); ?></h3>
	</div>
	<div class="sidebar-content widgets-sortables clearfix">
		<?php
			/**
			 * Allow for additional options
			 */
			do_action('easingslider_publish_metabox_top', $slider);
		?>
		
		<div class="misc-pub-section curtime misc-pub-curtime">
			<span id="timestamp">
				<?php if ( ! empty($slider->ID)) : ?>
					<?php printf(__('Published on: <b>%s</b>', 'easingslider'), date('M j, Y @ G:i', strtotime($slider->post_date))); ?>
				<?php else : ?>
					<?php printf(__('Not published yet.', 'easingslider')); ?>
				<?php endif; ?>
			</span>
		</div>
		
		<?php
			/**
			 * Allow for additional options
			 */
			do_action('easingslider_publish_metabox_bottom', $slider);
		?>
	</div>
	<div id="major-publishing-actions">
		<?php if ( ! empty($slider->ID)) : ?>
			<div id="delete-action">
				<a class="submitdelete deletion" href="<?php echo esc_attr(wp_nonce_url("?page={$page}&easingslider_action=trash_slider&id={$slider->ID}", 'trash')); ?>"><?php _e('Move to Trash', 'easingslider'); ?></a>
			</div>
		<?php endif; ?>
		<div id="publishing-action">
			<span class="spinner"></span>
			<?php if ( ! empty($slider->ID)) : ?>
				<input type="submit" name="save" class="button button-primary button-large" id="save" accesskey="p" disabled="disabled" value="<?php _e('Update', 'easingslider'); ?>">
			<?php else : ?>
				<input type="submit" name="publish" class="button button-primary button-large" id="publish" accesskey="p" value="<?php _e('Publish', 'easingslider'); ?>">
			<?php endif; ?>
		</div>
		<div class="clear"></div>
	</div>
</div>

<!-- Slider Type -->
<?php
	/**
	 * No slider type options are displayed if we only have one type available.
	 * This is likely to be the default type, `media`.
	 */
?>
<?php if (count($types) > 1) : ?>
	<div class="widgets-holder-wrap open">
		<div class="sidebar-name">
			<div class="sidebar-name-arrow"></div>
			<h3><?php _e('Slider Type', 'easingslider'); ?></h3>
		</div>
		<div class="sidebar-content widgets-sortables clearfix">
			<?php
				/**
				 * Allow for additional options
				 */
				do_action('easingslider_slider_type_metabox_top', $slider);
			?>

			<div>
				<div class="field">
					<label for="type">
						<span><?php _e('Choose a Slider Type:', 'easingslider'); ?></span>
						<select name="type" id="effect">
							<?php foreach ($types as $type => $label) : ?>
								<option value="<?php echo esc_attr($type); ?>" <?php selected($slider->type, $type); ?>><?php echo esc_html($label); ?></option>
							<?php endforeach; ?>
						</select>
					</label>
				</div>
				<p class="description"><?php _e('Select a slider type above. Changing the type dictates where the slider sources its slides from.', 'easingslider'); ?></p>
			</div>

			<?php
				/**
				 * Allow for additional options
				 */
				do_action('easingslider_slider_type_metabox_bottom', $slider);
			?>
		</div>
	</div>
<?php endif; ?>

<!-- Dimensions -->
<div class="widgets-holder-wrap closed">
	<div class="sidebar-name">
		<div class="sidebar-name-arrow"></div>
		<h3><?php _e('Dimensions', 'easingslider'); ?></h3>
	</div>
	<div class="sidebar-content widgets-sortables clearfix" style="display: none;">
		<?php
			/**
			 * Allow for additional options
			 */
			do_action('easingslider_dimensions_metabox_top', $slider);
		?>

		<div class="dimension-settings">
			<div class="field half">
				<label for="width">
					<span><?php _e('Width:', 'easingslider'); ?></span>
					<input type="number" name="width" id="width" value="<?php echo esc_attr($slider->width); ?>">
				</label>
			</div>
			<div class="field half">
				<label for="height">
					<span><?php _e('Height:', 'easingslider'); ?></span>
					<input type="number" name="height" id="height" value="<?php echo esc_attr($slider->height); ?>">
				</label>
			</div>
			<div class="field field-extra">
				<label for="full_width">
					<input type="hidden" name="full_width" value="false">
					<input type="checkbox" name="full_width" id="full_width" value="true" <?php checked($slider->full_width, true); ?>><span style="display: inline;"><?php _e('Make 100% full width.', 'easingslider'); ?></span>
				</label>
			</div>
			<p class="description"><?php _e('Set the "width" and "height" values (in pixels). Enable the full width option to make the slider container 100% width.', 'easingslider'); ?></p>
		</div>

		<div>
			<a href="#" class="show-advanced-options"><?php printf('+ %s', __('Show Advanced Options', 'easingslider')); ?></a>
			<div class="advanced-options hide">
				<div>
					<div class="field">
						<label for="image_resizing">
							<input type="hidden" name="image_resizing" value="false">
							<input type="checkbox" name="image_resizing" id="image_resizing" value="true" <?php checked($slider->image_resizing, true); ?>><span style="display: inline;"><?php _e('Crop & Resize images.', 'easingslider'); ?></span>
						</label>
					</div>
					<p class="description"><?php _e('Crops & resizes images to fit the width and height specified above. Only works with images from the WordPress Media Library.', 'easingslider'); ?></p>
				</div>

				<div>
					<div class="field">
						<label for="auto_height">
							<input type="hidden" name="auto_height" value="false">
							<input type="checkbox" name="auto_height" id="auto_height" value="true" <?php checked($slider->auto_height, true); ?>><span style="display: inline;"><?php _e('Enable Adaptive Height.', 'easingslider'); ?></span>
						</label>
					</div>
					<p class="description"><?php _e('Allows the slider to adapt to the height of each individual slide.', 'easingslider'); ?></p>
				</div>
			</div>
		</div>

		<?php
			/**
			 * Allow for additional options
			 */
			do_action('easingslider_dimensions_metabox_bottom', $slider);
		?>
	</div>
</div>

<!-- Transitions -->
<div class="widgets-holder-wrap closed">
	<div class="sidebar-name">
		<div class="sidebar-name-arrow"></div>
		<h3><?php _e('Transitions', 'easingslider'); ?></h3>
	</div>
	<div class="sidebar-content widgets-sortables clearfix" style="display: none;">
		<?php
			/**
			 * Allow for additional options
			 */
			do_action('easingslider_transitions_metabox_top', $slider);
		?>

		<div>
			<div class="field">
				<label for="effect">
					<span><?php _e('Effect:', 'easingslider'); ?></span>
					<select name="transition_effect" id="effect">
						<?php foreach ($transitions as $transition => $label) : ?>
							<option value="<?php echo esc_attr($transition); ?>" <?php selected($slider->transition_effect, $transition); ?>><?php echo esc_html($label); ?></option>
						<?php endforeach; ?>
					</select>
				</label>
			</div>
			<p class="description"><?php _e('Choose the transition effect you would like to use.', 'easingslider'); ?></p>
		</div>

		<div>
			<div class="field">
				<label for="duration">
					<span><?php _e('Duration:', 'easingslider'); ?></span>
					<input type="number" name="transition_duration" id="duration" value="<?php echo esc_attr($slider->transition_duration); ?>">
				</label>
			</div>
			<p class="description"><?php _e('Sets the duration (in milliseconds) for the slideshow transition.', 'easingslider'); ?></p>
		</div>

		<?php
			/**
			 * Allow for additional options
			 */
			do_action('easingslider_transitions_metabox_bottom', $slider);
		?>
	</div>
</div>

<!-- Preloading -->
<div class="widgets-holder-wrap closed">
	<div class="sidebar-name">
		<div class="sidebar-name-arrow"></div>
		<h3><?php _e('Preloading', 'easingslider'); ?></h3>
	</div>
	<div class="sidebar-content widgets-sortables clearfix" style="display: none;">
		<?php
			/**
			 * Allow for additional options
			 */
			do_action('easingslider_preloading_metabox_top', $slider);
		?>

		<div>
			<div class="field">
				<label for="lazy_loading">
					<input type="hidden" name="lazy_loading" value="false">
					<input type="checkbox" name="lazy_loading" id="lazy_loading" value="true" <?php checked($slider->lazy_loading, true); ?>><span style="display: inline;"><?php _e('Enable Lazy Loading.', 'easingslider'); ?></span>
				</label>
			</div>
			<p class="description"><?php _e('Tick this option to enable slide lazy loading. Preloading images in this way can help reduce loading times and speed up your website.', 'easingslider'); ?></p>
		</div>

		<?php
			/**
			 * Allow for additional options
			 */
			do_action('easingslider_preloading_metabox_bottom', $slider);
		?>
	</div>
</div>

<!-- Next & Previous Arrows -->
<div class="widgets-holder-wrap closed">
	<div class="sidebar-name">
		<div class="sidebar-name-arrow"></div>
		<h3><?php _e('Next & Previous Arrows', 'easingslider'); ?></h3>
	</div>
	<div class="sidebar-content widgets-sortables" style="display: none;">
		<?php
			/**
			 * Allow for additional options
			 */
			do_action('easingslider_arrows_metabox_top', $slider);
		?>

		<div>
			<div class="radio clearfix">
				<span><?php _e('Arrows:', 'easingslider'); ?></span>
				<div class="buttons">
					<label for="arrows-enable"><input type="radio" name="arrows" id="arrows-enable" value="true" <?php checked($slider->arrows, true); ?>>
						<span><?php _e('Enable', 'easingslider'); ?></span>
					</label>
					<label for="arrows-disable"><input type="radio" name="arrows" id="arrows-disable" value="false" <?php checked($slider->arrows, false); ?>>
						<span><?php _e('Disable', 'easingslider'); ?></span>
					</label>
				</div>
			</div>
			<p class="description"><?php _e('Toggles the next and previous slide arrows.', 'easingslider'); ?></p>
		</div>

		<div>
			<div class="radio clearfix">
				<span><?php _e('On Hover:', 'easingslider'); ?></span>
				<div class="buttons">
					<label for="arrows-hover-true"><input type="radio" name="arrows_hover" id="arrows-hover-true" value="true" <?php checked($slider->arrows_hover, true); ?>>
						<span><?php _e('True', 'easingslider'); ?></span>
					</label>
					<label for="arrows-hover-false"><input type="radio" name="arrows_hover" id="arrows-hover-false" value="false" <?php checked($slider->arrows_hover, false); ?>>
						<span><?php _e('False', 'easingslider'); ?></span>
					</label>
				</div>
			</div>
			<p class="description"><?php _e('Set to "True" to only show the arrows when the user hovers over the slideshow.', 'easingslider'); ?></p>
		</div>

		<div>
			<div class="field">
				<label for="arrows_position">
					<span><?php _e('Position:', 'easingslider'); ?></span>
					<select name="arrows_position" id="arrows_position">
						<option value="inside" <?php selected($slider->arrows_position, 'inside'); ?>><?php _e('Inside', 'easingslider'); ?></option>
						<option value="outside" <?php selected($slider->arrows_position, 'outside'); ?>><?php _e('Outside', 'easingslider'); ?></option>
					</select>
				</label>
			</div>
			<p class="description"><?php _e('Select a position for the arrows.', 'easingslider'); ?></p>
		</div>

		<?php
			/**
			 * Allow for additional options
			 */
			do_action('easingslider_arrows_metabox_bottom', $slider);
		?>
	</div>
</div>

<!-- Pagination Icons -->
<div class="widgets-holder-wrap closed">
	<div class="sidebar-name">
		<div class="sidebar-name-arrow"></div>
		<h3><?php _e('Pagination Icons', 'easingslider'); ?></h3>
	</div>
	<div class="sidebar-content widgets-sortables" style="display: none;">
		<?php
			/**
			 * Allow for additional options
			 */
			do_action('easingslider_pagination_metabox_top', $slider);
		?>

		<div>
			<div class="radio clearfix">
				<span><?php _e('Pagination:', 'easingslider'); ?></span>
				<div class="buttons">
					<label for="pagination-enable"><input type="radio" name="pagination" id="pagination-enable" value="true" <?php checked($slider->pagination, true); ?>>
						<span><?php _e('Enable', 'easingslider'); ?></span>
					</label>
					<label for="pagination-disable"><input type="radio" name="pagination" id="pagination-disable" value="false" <?php checked($slider->pagination, false); ?>>
						<span><?php _e('Disable', 'easingslider'); ?></span>
					</label>
				</div>
			</div>
			<p class="description"><?php _e('Enable/Disable the Pagination Icons. Each icon represents a slide in their respective order.', 'easingslider'); ?></p>
		</div>

		<div>
			<div class="radio clearfix">
				<span><?php _e('On Hover:', 'easingslider'); ?></span>
				<div class="buttons">
					<label for="pagination-hover-true"><input type="radio" name="pagination_hover" id="pagination-hover-true" value="true" <?php checked($slider->pagination_hover, true); ?>>
						<span><?php _e('True', 'easingslider'); ?></span>
					</label>
					<label for="pagination-hover-false"><input type="radio" name="pagination_hover" id="pagination-hover-false" value="false" <?php checked($slider->pagination_hover, false); ?>>
						<span><?php _e('False', 'easingslider'); ?></span>
					</label>
				</div>
			</div>
			<p class="description"><?php _e('Set to "True" to only show the pagination when the user hovers over the slideshow.', 'easingslider'); ?></p>
		</div>

		<div>
			<div class="field">
				<label for="pagination_position">
					<span><?php _e('Position:', 'easingslider'); ?></span>
					<select name="pagination_position" id="pagination_position" style="width: 45%; float: left;">
						<option value="inside" <?php selected($slider->pagination_position, 'inside'); ?>><?php _e('Inside', 'easingslider'); ?></option>
						<option value="outside" <?php selected($slider->pagination_position, 'outside'); ?>><?php _e('Outside', 'easingslider'); ?></option>
					</select>
					<select name="pagination_location" id="pagination_location" style="width: 45%; float: left; margin-left: 10px;">
						<option value="top-left" <?php selected($slider->pagination_location, 'top-left'); ?>><?php _e('Top Left', 'easingslider'); ?></option>
						<option value="top-right" <?php selected($slider->pagination_location, 'top-right'); ?>><?php _e('Top Right', 'easingslider'); ?></option>
						<option value="top-center" <?php selected($slider->pagination_location, 'top-center'); ?>><?php _e('Top Center', 'easingslider'); ?></option>
						<option value="bottom-left" <?php selected($slider->pagination_location, 'bottom-left'); ?>><?php _e('Bottom Left', 'easingslider'); ?></option>
						<option value="bottom-right" <?php selected($slider->pagination_location, 'bottom-right'); ?>><?php _e('Bottom Right', 'easingslider'); ?></option>
						<option value="bottom-center" <?php selected($slider->pagination_location, 'bottom-center'); ?>><?php _e('Bottom Center', 'easingslider'); ?></option>
					</select>
				</label>
			</div>
			<p class="description"><?php _e('Select a position for the pagination icons.', 'easingslider'); ?></p>
		</div>

		<?php
			/**
			 * Allow for additional options
			 */
			do_action('easingslider_pagination_metabox_bottom', $slider);
		?>
	</div>
</div>

<!-- Playback -->
<div class="widgets-holder-wrap closed">
	<div class="sidebar-name">
		<div class="sidebar-name-arrow"></div>
		<h3><?php _e('Automatic Playback', 'easingslider'); ?></h3>
	</div>
	<div class="sidebar-content widgets-sortables" style="display: none;">
		<?php
			/**
			 * Allow for additional options
			 */
			do_action('easingslider_playback_metabox_top', $slider);
		?>

		<div>
			<div class="radio clearfix">
				<span><?php _e('Playback:', 'easingslider'); ?></span>
				<div class="buttons">
					<label for="playback-enable"><input type="radio" name="playback_enabled" id="playback-enable" value="true" <?php checked($slider->playback_enabled, true); ?>>
						<span><?php _e('Enable', 'easingslider'); ?></span>
					</label>
					<label for="playback-disable"><input type="radio" name="playback_enabled" id="playback-disable" value="false" <?php checked($slider->playback_enabled, false); ?>>
						<span><?php _e('Disable', 'easingslider'); ?></span>
					</label>
				</div>
			</div>
			<p class="description"><?php _e('Enable/Disable slideshow automatic playback.', 'easingslider'); ?></p>
		</div>
		
		<div>
			<div class="field">
				<label for="playback_pause">
					<span><?php _e('Pause Duration:', 'easingslider'); ?></span>
					<input type="number" name="playback_pause" id="playback_pause" value="<?php echo esc_attr($slider->playback_pause); ?>">
				</label>
			</div>
			<p class="description"><?php _e('Sets the duration (in milliseconds) for the pause between slide transitions.', 'easingslider'); ?></p>
		</div>

		<?php
			/**
			 * Allow for additional options
			 */
			do_action('easingslider_playback_metabox_bottom', $slider);
		?>
	</div>
</div>

<?php
	/**
	 * Allow for additional boxes
	 */
	do_action('easingslider_after_edit_slider_sidebar', $slider);
?>
