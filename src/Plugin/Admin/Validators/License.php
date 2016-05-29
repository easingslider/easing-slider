<?php

namespace EasingSlider\Plugin\Admin\Validators;

use EasingSlider\Foundation\Admin\Validators\Validator;

/**
 * Exit if accessed directly
 */
if ( ! defined('ABSPATH')) {
	exit;
}

class License extends Validator
{
	/**
	 * Gets the validation rules
	 *
	 * @return array
	 */
	protected function getRules()
	{
		return apply_filters('easingslider_admin_license_validation_rules', array(
			'key'    => 'string',
			'status' => 'string'
		));
	}
}
