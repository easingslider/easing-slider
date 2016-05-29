<?php

namespace EasingSlider\Plugin\Contracts\Admin\Validators;

use EasingSlider\Foundation\Contracts\Admin\Validators\Validator;

/**
 * Exit if accessed directly
 */
if ( ! defined('ABSPATH')) {
	exit;
}

interface License extends Validator
{
	//
}
