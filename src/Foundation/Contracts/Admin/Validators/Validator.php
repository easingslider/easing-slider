<?php

namespace EasingSlider\Foundation\Contracts\Admin\Validators;

/**
 * Exit if accessed directly
 */
if ( ! defined('ABSPATH')) {
	exit;
}

interface Validator
{
	/**
	 * Validates the provided data
	 *
	 * @param  array $data
	 * @return array
	 */
	public function validate($data);
}
