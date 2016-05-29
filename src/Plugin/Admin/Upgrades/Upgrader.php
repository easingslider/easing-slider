<?php

namespace EasingSlider\Plugin\Admin\Upgrades;

use EasingSlider\Foundation\Admin\Upgrades\Upgrader as BaseUpgrader;
use EasingSlider\Plugin\Contracts\Options\Version;

/**
 * Exit if accessed directly
 */
if ( ! defined('ABSPATH')) {
	exit;
}

abstract class Upgrader extends BaseUpgrader
{
	/**
	 * Constructor
	 *
	 * @param \EasingSlider\Plugin\Contracts\Options\Version $version
	 * @return void
	 */
	public function __construct(Version $version)
	{
		parent::__construct($version);
	}
}