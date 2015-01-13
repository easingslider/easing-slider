<?php

/**
 * Exit if accessed directly
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Defines our shortcode functionality
 *
 * @uses   ES_Slider
 * @author Matthew Ruddy
 */
class ES_Shortcode {

	/**
	 * Prints the "Add Slider" media thickbox
	 *
	 * @return void
	 */
	public function print_media_thickbox() {

		global $pagenow;

		// Bail if not in the post/page editor
		if ( ( 'post.php' OR 'post-new.php' ) != $pagenow ) {
			return;
		}

		// Get all sliders
		$sliders = ES_Slider::all();

		// Display the thickbox
		?>
			<style type="text/css">
				.section {
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
					if ( '-1' === id ) {
						return alert("<?php _e( 'Please select a slider', 'easingslider' ); ?>");
					}

					// Send shortcode to editor
					send_to_editor('[easingslider id="'+ id +'"]');

					// Close thickbox
					tb_remove();

				}
			</script>

			<div id="select-slider" style="display: none;">
				<div class="section">
					<h2><?php _e( 'Add a slider', 'easingslider' ); ?></h2>
					<span><?php _e( 'Select a slider to insert from the box below.', 'easingslider' ); ?></span>
				</div>

				<div class="section">
					<select name="slider" id="slider">
						<option value="-1"><?php _e( 'Select a slider', 'easingslider' ); ?></option>
						<?php
							foreach ( $sliders as $slider ) {
								echo "<option value=\"{$slider->ID}\">{$slider->post_title} (ID #{$slider->ID})</option>";
							}
						?>
					</select>
				</div>

				<div class="section">
					<button id="insert-slider" class="button-primary" onClick="insertSlider();"><?php _e( 'Insert Slider', 'easingslider' ); ?></button>
					<button id="close-slider-thickbox" class="button-secondary" style="margin-left: 5px;" onClick="tb_remove();"><?php _e( 'Close', 'easingslider' ); ?></a>
				</div>
			</div>
		<?php
		
	}

	/**
	 * Prints the "Add Slider" media button
	 *
	 * @param  int $editor_id The editor ID
	 * @return void
	 */
	public function print_media_button( $editor_id ) {

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
			
			<a href="#TB_inline?width=480&amp;inlineId=select-slider" class="button thickbox insert-slider" data-editor="<?php echo esc_attr( $editor_id ); ?>" title="<?php _e( 'Add a Slider', 'easingslider' ); ?>">
				<span class="wp-media-buttons-icon dashicons dashicons-format-image"></span><?php _e( ' Add Slider', 'easingslider' ); ?>
			</a>
		<?php

	}

	/**
	 * Renders a slider, returning the HTML
	 *
	 * @param  array $atts The shortcode attributes
	 * @return string
	 */
	public function render( $atts = array() ) {

		// Allow extensions to modify
		$defaults = apply_filters( 'easingslider_shortcode_defaults', array( 'id' => false ) );

		// Combine shortcode attributes with defaults
		$atts = (object) shortcode_atts( $defaults, $atts );

		/**
		 * Continue as normal if we have an ID.
		 * 
		 * Otherwise, let's allow extensions to render sliders using their own method(s).
		 */
		if ( ! empty( $atts->id ) ) {

			// Find the slider
			$slider = ES_Slider::find( $atts->id );

			// Display error message if no slider has been found
			if ( ! $slider ) {
				return sprintf( __( '<p><strong>The slider specified (ID #%s) could not be found.</strong></p>', 'easingslider' ), $atts->id );
			}

			// Render and return the slider
			return $slider->render();

		}
		else {

			// Start output buffer
			ob_start();

			// Trigger action for our extensions
			do_action( 'easingslider_display_shortcode', (array) $atts );

			// Return output buffer contents
			return ob_get_clean();

		}

	}

}