<?php

namespace EasingSlider\Plugin\Views;

use EasingSlider\Foundation\Contracts\Models\Model;

/**
 * Exit if accessed directly
 */
if ( ! defined('ABSPATH')) {
	exit;
}

class Slider
{
	/**
	 * Slider
	 *
	 * @var \EasingSlider\Foundation\Contracts\Models\Model;
	 */
	protected $slider;

	/**
	 * Constructor
	 *
	 * @param  \EasingSlider\Foundation\Contracts\Models\Model $slider
	 * @return void
	 */
	public function __construct(Model $slider)
	{
		$this->slider = $slider;
	}

	/**
	 * Displays the view
	 *
	 * @return void
	 */
	public function display()
	{
		// Run through function
		$slider = apply_filters('easingslider_pre_display_slider', $this->slider);

		// Check for slides
		if (0 == count($slider->slides) && is_super_admin()) {

			// Display an error (to admins only) informing the user that the slider has no slides.
			printf(__('<p><strong>The slider ID #%d has no slides. Cannot display the slider.</strong></p>', 'easingslider'), $slider->ID);
		
		} else {

			// Load the slider template
			easingslider_get_template_part(array('slider' => $slider), 'slider');

		}

		// Do action
		do_action('easingslider_render_slider', $slider);
	}
}
