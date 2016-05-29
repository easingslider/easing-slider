<?php

namespace EasingSlider\Plugin\Admin\Upgrades;

use EasingSlider\Foundation\Admin\Upgrades\Upgrades as BaseUpgrades;

/**
 * Exit if accessed directly
 */
if ( ! defined('ABSPATH')) {
	exit;
}

class Upgrades extends BaseUpgrades
{
	/**
	 * Boot
	 *
	 * @return void
	 */
	protected function boot()
	{
		$this->upgraders[] = $this->plugin->make('\EasingSlider\Plugin\Admin\Upgrades\UpgradeTo300');
	}
}
