<?php

namespace EasingSlider\Plugin\Admin\Upgrades;

use EasingSlider\Foundation\Admin\Upgrades\Upgrader as BaseUpgrader;

/**
 * Exit if accessed directly
 */
if ( ! defined('ABSPATH')) {
	exit;
}

class Upgrader extends BaseUpgrader
{
	/**
	 * Boot
	 *
	 * @return void
	 */
	protected function boot()
	{
		$this->upgrades[] = $this->plugin->make('\EasingSlider\Plugin\Admin\Upgrades\UpgradeTo220');
		$this->upgrades[] = $this->plugin->make('\EasingSlider\Plugin\Admin\Upgrades\UpgradeTo300');
		$this->upgrades[] = $this->plugin->make('\EasingSlider\Plugin\Admin\Upgrades\UpgradeTo306');
	}
}
