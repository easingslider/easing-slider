<?php

namespace EasingSlider\Plugin\Options;

use EasingSlider\Foundation\Options\Option;
use EasingSlider\Plugin\Contracts\Options\Version as VersionContract;

/**
 * Exit if accessed directly
 */
if ( ! defined('ABSPATH')) {
	exit;
}

class Version extends Option implements VersionContract
{
	/**
	 * Name
	 *
	 * @var string
	 */
	protected $name = 'easingslider_version';

	/**
	 * Gets the default settings
	 *
	 * @return mixed
	 */
	public function getDefaults()
	{
		return EASINGSLIDER_VERSION;
	}
}
