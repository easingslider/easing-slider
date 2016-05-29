<?php

namespace EasingSlider\Plugin\Shortcodes;

use EasingSlider\Foundation\Contracts\Repositories\Repository;
use EasingSlider\Foundation\Shortcodes\Shortcode;
use EasingSlider\Plugin\Views\Slider as SliderView;

/**
 * Exit if accessed directly
 */
if ( ! defined('ABSPATH')) {
	exit;
}

class Slider extends Shortcode
{
	/**
	 * Tag
	 *
	 * @var string
	 */
	protected $tag = 'easingslider';

	/**
	 * Sliders
	 *
	 * @var \EasingSlider\Foundation\Contracts\Repositories\Repository
	 */
	protected $sliders;

	/**
	 * Constructor
	 *
	 * @param \EasingSlider\Foundation\Contracts\Repositories\Repository $sliders
	 * @return void
	 */
	public function __construct(Repository $sliders)
	{
		$this->sliders = $sliders;

		parent::__construct();
	}

	/**
	 * Renders the shortcode
	 *
	 * @param  array $atts
	 * @return string
	 */
	public function render($atts = array())
	{
		// Default shortcode attributes
		$defaults = apply_filters('easingslider_shortcode_defaults', array('id' => false));

		// Combine shortcode attributes with defaults
		$atts = (object) shortcode_atts($defaults, $atts);

		// Continue if we have an ID
		if ( ! empty($atts->id)) {

			// Find the slider
			$slider = $this->sliders->find($atts->id);

			// Continue if we have a slider. Otheriwse display error message if no slider has been found
			if ($slider) {

				// Start output buffer
				ob_start();

				// Display the slider
				$view = new SliderView($slider);
				$view->display();

				// Return output buffer
				return ob_get_clean();

			} else {
				
				// Show admins an error
				if (is_super_admin()) {
					return sprintf(__('<p><strong>The slider specified (ID #%d) could not be found.</strong></p>', 'easingslider'), $atts->id);
				}

			}

		}
	}
}
