<?php

namespace EasingSlider\Plugin\Admin\Upgrades;

use EasingSlider\Foundation\Admin\Upgrades\Upgrade;
use EasingSlider\Foundation\Contracts\Plugin;

/**
 * Exit if accessed directly
 */
if ( ! defined('ABSPATH')) {
	exit;
}

class UpgradeTo305 extends Upgrade
{
	/**
	 * Plugin
	 *
	 * @var \EasingSlider\Foundation\Contracts\Plugin
	 */
	protected $plugin;

	/**
	 * The version we're upgrading from (or greater)
	 *
	 * @var string
	 */
	protected $upgradeFrom = '3.0.4';

	/**
	 * The version we're upgrading too
	 *
	 * @var string
	 */
	protected $upgradeTo = '3.0.5';

	/**
	 * Constructor
	 *
	 * @param  \EasingSlider\Foundation\Contracts\Plugin $plugin
	 * @return void
	 */
	public function __construct(Plugin $plugin)
	{
		$this->plugin = $plugin;
	}

	/**
	 * We had some issues with our v3.0.4 upgrade process due to a bug.
	 * Consequently we pulled the update and worked on a fix.
	 *
	 * Rerunning the upgrade from v3.0.0 (since improved) should fix issues for those users affected.
	 *
	 * @return void
	 */
	protected function fix304Upgrade()
	{
		$upgrade = $this->plugin->make('\EasingSlider\Plugin\Admin\Upgrades\UpgradeTo300');
		$upgrade->upgradeSliders();
	}

	/**
	 * Executes the upgrade
	 *
	 * @return void
	 */
	public function upgrade()
	{
		$this->fix304Upgrade();
	}
}