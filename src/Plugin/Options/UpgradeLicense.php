<?php

namespace EasingSlider\Plugin\Options;

use EasingSlider\Foundation\Options\Option;

/**
 * Exit if accessed directly
 */
if ( ! defined('ABSPATH')) {
	exit;
}

class UpgradeLicense extends Option
{
	/**
	 * Name
	 *
	 * @var string
	 */
	protected $name = 'easingslider_show_upgrade_license_notice';

	/**
	 * Gets the default settings
	 *
	 * @return mixed
	 */
	public function getDefaults()
	{
		return true;
	}
}
