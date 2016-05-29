<?php

namespace EasingSlider\Plugin\Contracts\Options;

use EasingSlider\Foundation\Contracts\Options\OptionArray as OptionArrayContract;

/**
 * Exit if accessed directly
 */
if ( ! defined('ABSPATH')) {
	exit;
}

interface Settings extends OptionArrayContract
{
	//
}
