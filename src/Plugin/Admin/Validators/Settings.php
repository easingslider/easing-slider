<?php

namespace EasingSlider\Plugin\Admin\Validators;

use EasingSlider\Foundation\Admin\Validators\Validator;

/**
 * Exit if accessed directly
 */
if ( ! defined('ABSPATH')) {
	exit;
}

class Settings extends Validator
{
	/**
	 * Gets the validation rules
	 *
	 * @return array
	 */
	protected function getRules()
	{
		return apply_filters('easingslider_admin_settings_validation_rules', array(
			'load_in_footer' => 'boolean',
			'remove_data'    => 'boolean'
		));
	}
}
