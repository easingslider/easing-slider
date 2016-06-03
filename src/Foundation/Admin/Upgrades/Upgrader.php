<?php

namespace EasingSlider\Foundation\Admin\Upgrades;

use EasingSlider\Foundation\Contracts\Admin\Upgrades\Upgrade;
use EasingSlider\Foundation\Contracts\Admin\Upgrades\Upgrader as UpgraderContract;
use EasingSlider\Foundation\Contracts\Plugin;

/**
 * Exit if accessed directly
 */
if ( ! defined('ABSPATH')) {
	exit;
}

abstract class Upgrader implements UpgraderContract
{
	/**
	 * Upgrades
	 *
	 * @var array
	 */
	protected $upgrades = array();

	/**
	 * Plugin
	 *
	 * @var \EasingSlider\Foundation\Contracts\Plugin
	 */
	protected $plugin;

	/**
	 * Version
	 *
	 * @var \EasingSlider\Plugin\Contracts\Options\Version
	 */
	protected $version;

	/**
	 * Constructor
	 *
	 * @param  \EasingSlider\Foundation\Contracts\Plugin $plugin
	 * @return void
	 */
	public function __construct(Plugin $plugin)
	{
		$this->plugin = $plugin;

		$this->version = $plugin->version();

		$this->defineHooks();

		$this->boot();
	}

	/**
	 * Define hooks
	 *
	 * @return void
	 */
	protected function defineHooks()
	{
		add_action('admin_init', array($this, 'doUpgrades'));
	}

	/**
	 * Gets the plugin version
	 *
	 * @return string
	 */
	protected function getVersion()
	{
		return $this->version->getValue();
	}

	/**
	 * Gets the current plugin version
	 *
	 * @return string
	 */
	protected function getCurrentVersion()
	{
		return EASINGSLIDER_VERSION;
	}

	/**
	 * Sets the plugin version
	 *
	 * @param  string $version
	 * @return void
	 */
	protected function setVersion($version)
	{
		$this->version->setValue($version);
		$this->version->save();
	}

	/**
	 * Sets the current plugin version
	 *
	 * @return void
	 */
	protected function setCurrentVersion()
	{
		$version = $this->getVersion();
		$currentVersion = $this->getCurrentVersion();

		if ($currentVersion != $version) {
			$this->setVersion($currentVersion);
		}
	}

	/**
	 * Handles an upgrade
	 *
	 * @param  \EasingSlider\Foundation\Contracts\Admin\Upgrades\Upgrade $upgrade
	 * @return void
	 */
	protected function handleUpgrade(Upgrade $upgrade)
	{
		$version = $this->getVersion();

		if ($upgrade->isEligible($version)) {

			// Do the upgrade
			$upgrade->upgrade();

			// Set the version
			$this->setVersion($upgrade->getVersion());

		}
	}

	/**
	 * Do upgrades
	 *
	 * @return void
	 */
	public function doUpgrades()
	{
		foreach ($this->upgrades as $upgrade) {
			$this->handleUpgrade($upgrade);
		}

		$this->setCurrentVersion();
	}

	/**
	 * Boot
	 *
	 * @return void
	 */
	abstract protected function boot();
}
