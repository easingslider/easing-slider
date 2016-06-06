<?php

namespace EasingSlider\Foundation\Admin\Upgrades;

use EasingSlider\Foundation\Contracts\Admin\Upgrades\Upgrade as UpgradeContract;
use EasingSlider\Foundation\Contracts\Options\Option;

/**
 * Exit if accessed directly
 */
if ( ! defined('ABSPATH')) {
	exit;
}

abstract class Upgrade implements UpgradeContract
{
	/**
	 * Forces the upgrade, regardless of version
	 *
	 * @var boolean
	 */
	protected $force = false;

	/**
	 * The version we're upgrading from (or greater)
	 *
	 * @var string
	 */
	protected $upgradeFrom;

	/**
	 * The version we're upgrading too
	 *
	 * @var string
	 */
	protected $upgradeTo;

	/**
	 * Get the version we are upgrading too
	 *
	 * @return string
	 */
	public function getVersion()
	{
		return $this->upgradeTo;
	}

	/**
	 * Checks if the provided version is eligible for an upgrade
	 *
	 * @param  string $version
	 * @return boolean
	 */
	public function isEligible($version)
	{
		// Always eligible if forced
		if (true === $this->force) {
			return true;
		}

		// Compare version against upgrade "to" and "from" values to ensure a strict bracket of eligible versions.
		if (version_compare($version, $this->upgradeFrom, '>=')) {
			if (version_compare($version, $this->upgradeTo, '<')) {
				return true;
			}
		}

		return false;
	}

	/**
	 * Executes the upgrade
	 *
	 * @return void
	 */
	public function upgrade()
	{
		//
	}
}