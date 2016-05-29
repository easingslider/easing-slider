<?php

namespace EasingSlider\Plugin\Options;

use EasingSlider\Foundation\Options\OptionArray;
use EasingSlider\Plugin\Contracts\Options\License as LicenseContract;

/**
 * Exit if accessed directly
 */
if ( ! defined('ABSPATH')) {
	exit;
}

class License extends OptionArray implements LicenseContract
{
	/**
	 * Name
	 *
	 * @var string
	 */
	protected $name = 'easingslider_license';

	/**
	 * Gets the license key with all characters hidden except for the last 4.
	 *
	 * @return string
	 */
	public function maskedKey()
	{
		$licenseKey = $this->offsetGet('key');

		// Get the key length for character counting
		$keyLength = strlen($licenseKey);

		// Generate the masked key
		$maskedKey = str_repeat('*', $keyLength - 4) . substr($licenseKey, $keyLength - 4, 4);

		echo $maskedKey;
	}

	/**
	 * Gets the default settings
	 *
	 * @return mixed
	 */
	public function getDefaults()
	{
		return array(
			'key'    => '',
			'status' => ''
		);
	}
}
