<?php

namespace EasingSlider\Plugin\Admin;

use EasingSlider\Foundation\Contracts\Shortcodes\Shortcode;
use EasingSlider\Foundation\Contracts\Repositories\Repository;

/**
 * Exit if accessed directly
 */
if ( ! defined('ABSPATH')) {
	exit;
}

class MediaButton
{
	/**
	 * Shortcode
	 *
	 * @var \EasingSlider\Plugin\Contracts\Shortcodes\Shortcode
	 */
	protected $shortcode;

	/**
	 * Slides
	 *
	 * @var \EasingSlider\Plugin\Contracts\Repositories\Repository
	 */
	protected $sliders;

	/**
	 * Constructor
	 *
	 * @param  \EasingSlider\Plugin\Contracts\Shortcodes\Shortcode     $sliders
	 * @param  \EasingSlider\Plugnin\Contracts\Repositories\Repository $shortcode
	 * @return void
	 */
	public function __construct(Repository $sliders, Shortcode $shortcode)
	{
		$this->sliders = $sliders;
		$this->shortcode = $shortcode;

		$this->defineHooks();
	}

	/**
	 * Define hooks
	 *
	 * @return void
	 */
	public function defineHooks()
	{
		add_action('media_buttons', array($this, 'displayButton'));
	}

	/**
	 * Displays the media button
	 *
	 * @return void
	 */
	public function displayButton()
	{
		// Print the button's HTML and CSS
		?>
			<style type="text/css">
				.wp-media-buttons .insert-slider span.wp-media-buttons-icon {
					margin-top: -2px;
				}
				.wp-media-buttons .insert-slider span.wp-media-buttons-icon:before {
					content: "\f232";
					font: 400 18px/1 dashicons;
					speak: none;
					-webkit-font-smoothing: antialiased;
					-moz-osx-font-smoothing: grayscale;
				}
			</style>
			
			<a href="#TB_inline?width=480&amp;inlineId=select-slider" class="button thickbox insert-slider" data-editor="<?php echo esc_attr($editor_id); ?>" title="<?php _e('Add a Slider', 'easingslider'); ?>">
				<span class="wp-media-buttons-icon dashicons dashicons-format-image"></span><?php _e(' Add Slider', 'easingslider'); ?>
			</a>
		<?php

		// Enqueue the thickbox (required for button to work)
		add_action('admin_footer', array($this, 'printThickbox'));
	}

	/**
	 * Prints the thickbox for our media button
	 *
	 * @return void
	 */
	public function printThickbox()
	{
		?>
			<style type="text/css">
				#TB_window .section {
					padding: 15px 15px 0 15px;
				}
			</style>

			<script type="text/javascript">
				/**
				 * Sends a shortcode to the post/page editor
				 */
				function insertSlider() {

					// Get the slider ID
					var id = jQuery('#slider').val();

					// Display alert and bail if no slideshow was selected
					if ('-1' === id) {
						return alert("<?php _e('Please select a slider', 'easingslider'); ?>");
					}

					// Send shortcode to editor
					send_to_editor('[<?php echo esc_attr($this->shortcode->tag()); ?> id=\"'+ id +'\"]');

					// Close thickbox
					tb_remove();

				}
			</script>

			<div id="select-slider" style="display: none;">
				<div class="section">
					<h2><?php _e('Add a slider', 'easingslider'); ?></h2>
					<span><?php _e('Select a slider to insert from the box below.', 'easingslider'); ?></span>
				</div>

				<div class="section">
					<select name="slider" id="slider">
						<option value="-1"><?php _e('Select a slider', 'easingslider'); ?></option>
						<?php foreach ($this->sliders->all() as $slider) : ?>
							<option value="<?php echo esc_attr($slider->ID); ?>"><?php echo esc_html(sprintf("%s (ID #%d)", $slider->post_title, $slider->ID)); ?></option>
						<?php endforeach; ?>
					</select>
				</div>

				<div class="section">
					<button id="insert-slider" class="button-primary" onClick="insertSlider();"><?php _e('Insert Slider', 'easingslider'); ?></button>
					<button id="close-slider-thickbox" class="button-secondary" style="margin-left: 5px;" onClick="tb_remove();"><?php _e('Close', 'easingslider'); ?></a>
				</div>
			</div>
		<?php
	}
}
