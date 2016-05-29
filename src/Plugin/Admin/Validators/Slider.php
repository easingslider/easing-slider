<?php

namespace EasingSlider\Plugin\Admin\Validators;

use EasingSlider\Foundation\Admin\Validators\Validator;

/**
 * Exit if accessed directly
 */
if ( ! defined('ABSPATH')) {
	exit;
}

class Slider extends Validator
{
	/**
	 * Gets the validation rules
	 *
	 * @return array
	 */
	protected function getRules()
	{
		return apply_filters('easingslider_admin_slider_validation_rules', array(
			'post_title'          => 'string',
			'post_type'           => 'string',
			'post_status'         => 'string',
			'type'                => 'string',
			'slides'              => 'decode_json_array',
			'randomize'           => 'boolean',
			'width'               => 'integer',
			'height'              => 'integer',
			'full_width'          => 'boolean',
			'image_resizing'      => 'boolean',
			'auto_height'         => 'boolean',
			'transition_effect'   => 'string',
			'transition_duration' => 'integer',
			'arrows'              => 'boolean',
			'arrows_hover'        => 'boolean',
			'arrows_position'     => 'string',
			'pagination'          => 'boolean',
			'pagination_hover'    => 'boolean',
			'pagination_position' => 'string',
			'pagination_location' => 'string',
			'lazy_loading'        => 'boolean',
			'playback_enabled'    => 'boolean',
			'playback_pause'      => 'integer'
		));
	}
}
