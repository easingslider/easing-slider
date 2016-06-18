<?php

use EasingSlider\Foundation\Contracts\Models\Model;
use EasingSlider\Plugin\Admin\UpdateManager;
use EasingSlider\Plugin\Support\AttachmentImageResizer;

/**
 * Exit if accessed directly
 */
if ( ! defined('ABSPATH')) {
	exit;
}

if ( ! function_exists('Easing_Slider')) {
	/**
	 * The main function responsible for returning the one true Easing_Slider instance to functions everywhere.
	 *
	 * Use this function like you would a global variable, except without needing
	 * to declare the global.
	 *
	 * Example: <?php $easing_slider = Easing_Slider(); ?>
	 *
	 * @return Easing_Slider
	 */
	function Easing_Slider()
	{
		return \EasingSlider\Plugin\Plugin::instance();
	}
}

if ( ! function_exists('easingslider')) {
	/**
	 * Alias for displaying a slider shortcode
	 *
	 * @param  int $id
	 * @return void
	 */
	function easingslider($id)
	{
		$shortcode = Easing_Slider()->shortcode();

		echo $shortcode->render(array('id' => $id));
	}
}

if ( ! function_exists('easingslider_activate')) {
	/**
	 * Activator
	 *
	 * @return void
	 */
	function easingslider_activate()
	{	
		$activator = Easing_Slider()->activator();
		$activator->activate();
	}
	register_activation_hook(EASINGSLIDER_PLUGIN_FILE, 'easingslider_activate');
}

if ( ! function_exists('easingslider_get_template_part')) {
	/**
	 * Retrieves a template part
	 *
	 * @param  array     $data   The data to be inserted
	 * @param  string    $slug   The template slug
	 * @param  string    $name   The template name
	 * @param  boolean   $load   Whether to load the template or return it
	 * @return string
	 */
	function easingslider_get_template_part($data, $slug, $name = null, $load = true)
	{
		$templateLoader = Easing_Slider()->templateLoader();
		$templateLoader->getTemplatePart($data, $slug, $name, $load);
	}
}

if ( ! function_exists('easingslider_container_classes')) {
	/**
	 * Prints the slider container classes
	 *
	 * @param  \EasingSlider\Foundation\Contracts\Models\Model $slider
	 * @return void
	 */
	function easingslider_container_classes(Model $slider)
	{
		// Base classes
		$classes = array(
			'easingslider',
			'easingslider-'. absint($slider->ID),
			'easingslider-container'
		);

		// Full width
		if (true == $slider->full_width) {
			$classes[] = 'easingslider-full-width';
		}

		// Image resizing
		if (true == $slider->image_resizing) {
			$classes[] = 'easingslider-resizing-enabled';
		}

		// Auto height/aspect ratio
		if (true == $slider->auto_height) {
			$classes[] = 'easingslider-auto-height';
		} else {
			$classes[] = 'easingslider-aspect-ratio';
		}

		// Arrows
		if ($slider->arrows) {
			if ($slider->arrows_hover) {
				$classes[] = 'easingslider-arrows-hover';
			}
			if ('inside' == $slider->arrows_position) {
				$classes[] = 'easingslider-arrows-inside';
			} elseif ('outside' == $slider->arrows_position) {
				$classes[] = 'easingslider-arrows-outside';
			}
		}

		// Pagination
		if ($slider->pagination) {
			if ($slider->pagination_hover) {
				$classes[] = 'easingslider-pagination-hover';
			}
			if ('inside' == $slider->pagination_position) {
				$classes[] = 'easingslider-pagination-inside';
			} elseif ('outside' == $slider->pagination_position) {
				$classes[] = 'easingslider-pagination-outside';
			}
			$classes[] = "easingslider-pagination-{$slider->pagination_location}";
		}

		// Run through a filter
		$classes = apply_filters('easingslider_container_classes', $classes, $slider);
		
		// Print classes if not empty
		if ( ! empty($classes)) {
			echo 'class="';
				echo esc_attr(implode(' ', $classes));
			echo '"';
		}
	}
}

if ( ! function_exists('easingslider_inline_script')) {
	/**
	 * Prints inline scripting for a slider
	 *
	 * @param  \EasingSlider\Foundation\Contracts\Models\Model $slider
	 * @return void
	 */
	function easingslider_inline_script(Model $slider)
	{
		$params = array(
			'width'             => intval($slider->width),
			'height'            => intval($slider->height),
			'singleItem'        => true,
			'items'             => 1,
			'itemsDesktop'      => false,
			'itemsDesktopSmall' => false,
			'itemsTablet'       => false,
			'itemsTabletSmall'  => false,
			'itemsMobile'       => false,
			'responsive'        => true,
			'lazyLoad'          => (true == $slider->lazy_loading) ? true : false,
			'autoPlay'          => (true == $slider->playback_enabled) ? intval($slider->playback_pause) : false,
			'slideSpeed'        => intval($slider->transition_duration),
			'navigation'        => (true == $slider->arrows) ? true : false,
			'navigationText'    => array('', ''),
			'pagination'        => (true == $slider->pagination) ? true : false,
			'autoHeight'        => (true == $slider->auto_height) ? true : false,
			'mouseDrag'         => false,
			'touchDrag'         => false,
			'addClassActive'    => true,
			'transitionStyle'   => ('fade' == $slider->transition_effect) ? 'fade' : false,
		);

		// Run through a filter
		$params = apply_filters('easingslider_inline_script', $params, $slider);

		// Print scripting if not empty
		if ( ! empty($params)) {
			?>
				<script type="text/javascript">
					window.EasingSlider<?php echo esc_attr($slider->ID); ?> = <?php echo json_encode($params); ?>;
				</script>
			<?php
		}
	}
}

if ( ! function_exists('easingslider_inline_styles')) {
	/**
	 * Prints inline styling for a slider
	 *
	 * @param  \EasingSlider\Foundation\Contracts\Models\Model $slider
	 * @return void
	 */
	function easingslider_inline_styles(Model $slider) {

		// Initiate styles
		$styles = '';

		// Styling for "width" and "height"
		$styles .= '.easingslider-'. absint($slider->ID) .' { ';
			if (true == $slider->full_width) {
				$styles .= 'width: 100%; ';
			} else {
				$styles .= 'max-width: '. absint($slider->width) .'px; ';
			}
		$styles .= "}\n";
		
		// Styling for slide dimensions
		if (false == $slider->auto_height) {
			$styles .= '.easingslider-'. absint($slider->ID) .' .easingslider-image { ';
				$styles .= 'max-height: '. absint($slider->height) .'px; ';
				$styles .= 'max-width: '. absint($slider->width) .'px; ';
			$styles .= "}\n";
		}

		// Styling for "fade" transition speed
		if ($slider->transition_effect) {
			$styles .= '.easingslider-'. absint($slider->ID) .' .easingslider-fade-in, .easingslider-fade-out { ';
				$styles .= '-webkit-animation-duration: '. absint($slider->transition_duration) .'ms; ';
				$styles .= '-moz-animation-duration: '. absint($slider->transition_duration) .'ms; ';
				$styles .= 'animation-duration: '. absint($slider->transition_duration) .'ms; ';
			$styles .= "}\n";
		}

		// Run through a filter
		$styles = apply_filters('easingslider_inline_styles', $styles, $slider);

		// Print styling if not empty
		if ( ! empty($styles)) {
			echo '<style type="text/css">';
				echo esc_attr($styles);
			echo '</style>';
		}
	}
}

if ( ! function_exists('easingslider_get_placeholder_pixel')) {
	/**
	 * Returns the URL to our single pixel placeholder image
	 *
	 * @return string
	 */
	function easingslider_get_placeholder_pixel()
	{
		return EASINGSLIDER_ASSETS_URL . 'images/placeholder-pixel.png';
	}
}

if ( ! function_exists('easingslider_slide_classes')) {
	/**
	 * Prints the slide classes
	 *
	 * @param  object                                          $slide
	 * @param  \EasingSlider\Foundation\Contracts\Models\Model $slider
	 * @return void
	 */
	function easingslider_slide_classes($slide, Model $slider)
	{
		// Base classes
		$classes = array(
			'easingslider-slide'
		);

		// Run through a filter
		$classes = apply_filters('easingslider_slide_classes', $classes, $slide, $slider);
		
		// Print classes if not empty
		if ( ! empty($classes)) {
			echo 'class="';
				echo esc_attr(implode(' ', $classes));
			echo '"';
		}
	}
}

if ( ! function_exists('easingslider_display_slide')) {
	/**
	 * Runs the appropriate action based on the slide type.
	 * Should be used to display said slide type whilst remaining extensible.
	 *
	 * @param  object                                          $slide
	 * @param  \EasingSlider\Foundation\Contracts\Models\Model $slider
	 * @return void
	 */
	function easingslider_display_slide($slide, Model $slider)
	{
		// Before slide
		do_action('easingslider_before_display_slide', $slide, $slider);

		// Run the action
		do_action("easingslider_display_{$slide->type}_slide", $slide, $slider);

		// After slide
		do_action('easingslider_after_display_slide', $slide, $slider);
	}
}

if ( ! function_exists('easingslider_display_image_slide')) {
	/**
	 * Displays an image slide
	 *
	 * @param  object                                          $slide
	 * @param  \EasingSlider\Foundation\Contracts\Models\Model $slider
	 * @return void
	 */
	function easingslider_display_image_slide($slide, Model $slider)
	{
		?>
			<?php if ( ! empty($slide->link) && 'none' != $slide->link) : ?><a href="<?php echo esc_attr($slide->linkUrl); ?>" target="<?php echo esc_attr($slide->linkTargetBlank); ?>"><?php endif; ?>
				<?php
					/**
					 * Here we use background images for "full width" sliders to achieve desired effect.
					 */
				?>
				<?php if (true === $slider->lazy_loading) : ?>
					<img src="<?php echo esc_attr(easingslider_get_placeholder_pixel()); ?>" data-src="<?php echo esc_attr($slide->url); ?>" alt="<?php echo esc_attr($slide->alt); ?>" title="<?php echo esc_attr($slide->title); ?>" class="easingslider-image easingslider-lazy" />
				<?php else : ?>
					<img src="<?php echo esc_attr($slide->url); ?>" alt="<?php echo esc_attr($slide->alt); ?>" title="<?php echo esc_attr($slide->title); ?>" class="easingslider-image" />
				<?php endif; ?>
			<?php if ( ! empty($slide->link) && 'none' != $slide->link) : ?></a><?php endif; ?>
		<?php
	}
	add_action('easingslider_display_image_slide', 'easingslider_display_image_slide', 10, 2);
}

if ( ! function_exists('easingslider_resize_attachment_image')) {
	/**
	 * Resizes an attachment image
	 *
	 * @param  int     $attachmentId
	 * @param  int     $width
	 * @param  int     $height
	 * @param  boolean $crop
	 * @param  int     $quality
	 * @return array
	 */
	function easingslider_resize_attachment_image($attachmentId, $width, $height, $crop = true, $quality = 100)
	{
		$resizer = new AttachmentImageResizer($attachmentId);

		return $resizer->resize($width, $height, $crop, $quality);
	}
}

if ( ! function_exists('easingslider_set_attachment_image_urls')) {
	/**
	 * Adds the attachment image URL to each slide
	 *
	 * @param  \EasingSlider\Foundation\Contracts\Models\Model $slider
	 * @return \EasingSlider\Foundation\Contracts\Models\Model
	 */
	function easingslider_set_attachment_image_urls(Model $slider)
	{
		// Continue if using the media library
		if ('media' == $slider->type) {

			// Copy slides
			$slides = $slider->slides;

			// Loop through each slide and get attachment URL
			foreach ($slides as &$slide) {
				if ('image' == $slide->type && ! empty($slide->attachment_id)) {

					// Resize image, if enabled.
					if (true === $slider->image_resizing) {
						$resizedImage = easingslider_resize_attachment_image(
							$slide->attachment_id,
							$slider->width,
							$slider->height
						);

						$slide->url = $resizedImage['url'];
					} else {
						$attachmentUrl = wp_get_attachment_url($slide->attachment_id, 'full');

						$slide->url = $attachmentUrl;
					}
				}
			}

			// Restore slides
			$slider->slides = $slides;

		}

		return $slider;
	}
	add_filter('easingslider_pre_display_slider', 'easingslider_set_attachment_image_urls');
}

if ( ! function_exists('easingslider_maybe_randomize_slides')) {
	/**
	 * Randomizes the slide order, if enabled.
	 *
	 * @param  \EasingSlider\Foundation\Contracts\Models\Model $slider
	 * @return \EasingSlider\Foundation\Contracts\Models\Model
	 */
	function easingslider_maybe_randomize_slides(Model $slider)
	{
		// Copy slides
		$slides = $slider->slides;

		// Randomize if enabled
		if ($slider->randomize) {
			shuffle($slides);
		}

		// Restore slides
		$slider->slides = $slides;

		return $slider;
	}
	add_filter('easingslider_pre_display_slider', 'easingslider_maybe_randomize_slides');
}

if ( ! function_exists('easingslider_api_url')) {
	/**
	 * Gets the API URL
	 *
	 * @return string
	 */
	function easingslider_api_url()
	{
		return apply_filters('easingslider_api_url', EASINGSLIDER_API_URL);
	}
}

if ( ! function_exists('easingslider_register_addon')) {
	/**
	 * Registers an addon
	 *
	 * @param  string $name
	 * @param  string $file
	 * @param  string $version
	 * @return \EasingSlider\Foundation\Contracts\Admin\PluginUpdaters\PluginUpdater
	 */
	function easingslider_register_addon($name, $file, $version)
	{
		if (is_admin()) {
			return Easing_Slider()->make(
				'\EasingSlider\Foundation\Contracts\Admin\PluginUpdaters\PluginUpdater',
				array(
					':name' => $name,
					':file' => $file,
					':version' => $version
				)
			);
		}
	}
}

if ( ! function_exists('easingslider_get_addon_basename')) {
	/**
	 * Gets the basename for an addon by the provided slug
	 *
	 * @param  string $slug
	 * @return string
	 */
	function easingslider_get_addon_basename($slug)
	{
		$keys = array_keys(get_plugins());

		foreach ($keys as $key) {
			if (preg_match('|^' . $slug . '|', $key)) {
				return $key;
			}
		}

		return $slug;
	}
}

if ( ! function_exists('easingslider_admin_media_slides_panel')) {
	/**
	 * Displays the admin "Media" slides panel
	 *
	 * @param  \EasingSlider\Foundation\Contracts\Models\Model $slider
	 * @return void
	 */
	function easingslider_admin_media_slides_panel(Model $slider) {
		?>
			<div class="media-frame wp-core-ui mode-grid hide-menu">
				<div class="media-frame-content" data-columns="5">
					<div id="slides-browser" class="attachments-browser hide-sidebar">
						<?php
							/**
							 * Slides are dynamically added using Backbone.js here.
							 */
						?>
					</div>
				</div>
			</div>
		<?php
	}
	add_action('easingslider_admin_media_slides_panel', 'easingslider_admin_media_slides_panel');
}

if ( ! function_exists('easingslider_admin_upgrade_license_response')) {
	/**
	 * Tells the user they need to upgrade their license to a new license key.
	 * This is a requirements for licenses purchased prior to v3.0.0.
	 * 
	 * @param  string $message
	 * @param  object $data
	 * @return string
	 */
	function easingslider_admin_upgrade_license_response($message, $data)
	{
		/**
		 * If we've gotten an `upgrade_required` response, tell the user.
		 * This is a custom one-off response we've created due to circumstances
		 * created in major changes to Easing Slider's business model and licensing system.
		 */
		if (isset($data->error) && 'upgrade_required' == $data->error) {

			// Link for upgrading a user's license
			$upgradeLink = 'http://easingslider.com/account/upgrade-license';

			// Alter the message to tell the user an upgrade is needed
			$message = sprintf(__('An upgrade to your license key is required to allow addon access when using Easing Slider v3.0.0 or greater. <a href="%s" target="_blank">Click here</a> to get your new license key for free.', 'easingslider'), $upgradeLink);

		}

		return $message;
	}
	add_filter('easingslider_edd_license_handler_error_response', 'easingslider_admin_upgrade_license_response', 10, 2);
}

if ( ! function_exists('easingslider_admin_show_upgrade_info')) {
	/**
	 * Shows a notice to users who have upgraded.
	 *
	 * @return void
	 */
	function easingslider_admin_show_upgrade_info()
	{
		// Bail if not on one of our admin pages
		if ( ! easingslider_is_admin()) {
			return;
		}

		// Bail if we've not upgraded from v2
		if ( ! get_option('easingslider_upgraded_from_v2', false)) {
			return;
		}

		?>
			<div class="message updated upgrade-info">
				<p class="upgrade-info-title"><?php _e('Thanks for Upgrading!', 'easingslider'); ?></p>
				<p class="upgrade-info-text"><?php _e('Thank you for upgrading to Easing Slider v3! So much has changed - please check out the important update information below.', 'easingslider'); ?></p>
				<ul class="upgrade-info-list">
					<li>
						<span class="dashicons dashicons-info"></span>
						<a href="http://easingslider.com/important-changes" target="_blank"><?php _e('Important Addon & Licensing Changes', 'easingslider'); ?></a>
					</li>
					<li>
						<span class="dashicons dashicons-dismiss"></span>
						<a href="<?php echo esc_attr(add_query_arg('easingslider_action', 'dismiss_upgrade_info')); ?>"><?php _e('Dismiss', 'easingslider'); ?></a>
					</li>
				</ul>
			</div>
		<?php
	}
	add_action('admin_notices', 'easingslider_admin_show_upgrade_info');
}

if ( ! function_exists('easingslider_admin_dismiss_upgrade_info')) {
	/**
	 * Dismisses the upgrade info notice
	 *
	 * @return void
	 */
	function easingslider_admin_dismiss_upgrade_info()
	{
		delete_option('easingslider_upgraded_from_v2');
	}
	add_action('easingslider_dismiss_upgrade_info', 'easingslider_admin_dismiss_upgrade_info');
}
