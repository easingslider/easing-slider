<?php

namespace EasingSlider\Foundation\Admin\Upgrades;

use EasingSlider\Foundation\Contracts\Admin\Upgrades\Upgrades as UpgradesContract;
use EasingSlider\Foundation\Contracts\Plugin;

/**
 * Exit if accessed directly
 */
if ( ! defined('ABSPATH')) {
	exit;
}

abstract class Upgrades implements UpgradesContract
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
	 * Constructor
	 *
	 * @param  \EasingSlider\Foundation\Contracts\Plugin $plugin
	 * @return void
	 */
	public function __construct(Plugin $plugin)
	{
		$this->plugin = $plugin;

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
	 * Do upgrades
	 *
	 * @return void
	 */
	public function doUpgrades()
	{
		foreach ($this->upgraders as $upgrader) {
			$upgrader->upgrade();
		}

		if ( ! $this->isVersionUpdated()) {
			$this->setCurrentVersion();
		}
	}

	/**
	 * Checks if the current plugin version is up to date
	 *
	 * @return boolean
	 */
	protected function isVersionUpdated()
	{
		$version = $this->plugin->version();

		return (EASINGSLIDER_VERSION == $version->getValue()) ? true : false;
	}

	/**
	 * Sets the current plugin version
	 *
	 * @return void
	 */
	protected function setCurrentVersion()
	{
		$version = $this->plugin->version();

		$version->setValue(EASINGSLIDER_VERSION);
		$version->save();
	}

	/**
	 * Boot
	 *
	 * @return void
	 */
	abstract protected function boot();
}
