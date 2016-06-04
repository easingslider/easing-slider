<?php

namespace EasingSlider\Plugin\Models;

use EasingSlider\Foundation\Models\Model;

/**
 * Exit if accessed directly
 */
if ( ! defined('ABSPATH')) {
	exit;
}

class Slider extends Model
{
	/**
	 * Sets the model defaults
	 *
	 * @return array
	 */
	public function getDefaults()
	{
		return apply_filters('easingslider_slider_defaults', array(
			'type'                => 'media',
			'slides'              => array(),
			'randomize'           => false,
			'width'               => 640,
			'height'              => 400,
			'full_width'          => false,
			'image_resizing'      => true,
			'auto_height'         => false,
			'transition_effect'   => 'fade',
			'transition_duration' => 400,
			'arrows'              => true,
			'arrows_hover'        => false,
			'arrows_position'     => 'inside',
			'pagination'          => true,
			'pagination_hover'    => false,
			'pagination_position' => 'inside',
			'pagination_location' => 'bottom-center',
			'lazy_loading'        => true,
			'playback_enabled'    => true,
			'playback_pause'      => 4000
		));
	}
}
