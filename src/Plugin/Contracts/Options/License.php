<?php

namespace EasingSlider\Plugin\Contracts\Options;

use EasingSlider\Foundation\Contracts\Options\OptionArray as OptionArrayContract;

/**
 * Exit if accessed directly
 */
if ( ! defined('ABSPATH')) {
	exit;
}

interface License extends OptionArrayContract
{
	/**
	 * Gets the license key with all characters hidden except for the last 4.
	 *
	 * @return string
	 */
	public function maskedKey();
}
