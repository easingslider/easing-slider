<?php

namespace EasingSlider\Foundation\Admin\Upgrades;

use EasingSlider\Foundation\Contracts\Admin\Upgrades\Upgrader as UpgraderContract;
use EasingSlider\Foundation\Contracts\Options\Option;

/**
 * Exit if accessed directly
 */
if ( ! defined('ABSPATH')) {
	exit;
}

abstract class Upgrader implements UpgraderContract
{
	/**
	 * Version
	 *
	 * @var \EasingSlider\Foundation\Contracts\Options\Option
	 */
	protected $version;

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
	 * Operator for version comparison
	 *
	 * @var string
	 */
	protected $operator = '=';

	/**
	 * Constructor
	 *
	 * @param \EasingSlider\Foundation\Contracts\Options\Option $version
	 * @return void
	 */
	public function __construct(Option $version)
	{
		$this->version = $version;
	}

	/**
	 * Checks if we meet the requirements to upgrade
	 *
	 * @return boolean
	 */
	protected function meetsRequirements()
	{
		// Get the current version
		$version = $this->version->getValue();

		// Compare version against upgrade "to" and "from" values to ensure a strict bracket of eligible versions.
		if (version_compare($version, $this->upgradeFrom, '>=')) {
			if (version_compare($version, $this->upgradeTo, '<')) {
				return true;
			}
		}

		return false;
	}

	/**
	 * Updates the current plugin version.
	 *
	 * @return void
	 */
	protected function updateVersion()
	{
		$this->version->setValue($this->upgradeTo);
		$this->version->save();
	}

	/**
	 * Executes an upgrade, first checking the version.
	 *
	 * @return void
	 */
	public function upgrade()
	{
		if ($this->meetsRequirements()) {
			$this->doUpgrade();

			$this->updateVersion();
		}
	}

	/**
	 * Handles the upgrade
	 *
	 * @return void
	 */
	abstract protected function doUpgrade();
}