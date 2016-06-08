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
	 * Unfortunately some users would still have a semi-broken plugin as a result.
	 * Re-running the v3.0.0 upgrade should resolve the issue and regenerate any missing options.
	 * We also have to re-transfer the capabilities as these were also affected by the bug.
	 *
	 * @return void
	 */
	public function fixBrokenUpgrade()
	{
		$upgrade = $this->plugin->make('\EasingSlider\Plugin\Admin\Upgrades\UpgradeTo300');
		$upgrade->transferCapabilities();
		$upgrade->upgradeSliders();
	}

	/**
	 * Executes the upgrade
	 *
	 * @return void
	 */
	public function upgrade()
	{
		$this->fixBrokenUpgrade();
	}
}