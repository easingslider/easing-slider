<?php

namespace EasingSlider\Plugin\Admin;

use EasingSlider\Foundation\Admin\LicenseHandlers\EDDLicenseHandler;
use EasingSlider\Plugin\Contracts\Options\License;

/**
 * Exit if accessed directly
 */
if ( ! defined('ABSPATH')) {
	exit;
}

class LicenseHandler extends EDDLicenseHandler
{
	/**
	 * Constructor
	 *
	 * @param  \EasingSlider\Plugin\Contracts\Options\License $license
	 * @return void
	 */
	public function __construct(License $license)
	{
		parent::__construct(
			$license,
			EASINGSLIDER_NAME,
			EASINGSLIDER_PLUGIN_FILE,
			EASINGSLIDER_VERSION
		);
	}
}
