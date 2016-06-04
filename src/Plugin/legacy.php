<?php

/**
 * Exit if accessed directly
 */
if ( ! defined('ABSPATH')) {
	exit;
}

if ( ! function_exists('easingsliderlite')) {
	/**
	 * Displays the "Easing Slider 'Lite'" slider
	 *
	 * @return void
	 */
	function easingsliderlite()
	{
		echo do_shortcode('[easingsliderlite]');
	}
}

if ( ! function_exists('easingsliderlite_shortcode')) {
	/**
	 * Displays the "Easing Slider 'Lite'" slider by shortcode
	 *
	 * @return void
	 */
	function easingsliderlite_shortcode()
	{
		$liteSliderId = absint(get_option('easingslider_lite_slider_id'));

		echo do_shortcode("[easingslider id=\"{$liteSliderId}\"]");
	}
	add_shortcode('easingsliderlite', 'easingsliderlite_shortcode');
}
