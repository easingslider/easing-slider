<?php

namespace EasingSlider\Foundation\Contracts\Shortcodes;

/**
 * Exit if accessed directly
 */
if ( ! defined('ABSPATH')) {
	exit;
}

interface Shortcode
{
	/**
	 * Returns the shortcode tag
	 *
	 * @return string
	 */
	public function tag();

	/**
	 * Renders the shortcode
	 *
	 * @param  array $atts The shortcode attributes
	 * @return string
	 */
	public function render($atts = array());
}
