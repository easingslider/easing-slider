<?php

namespace EasingSlider\Foundation\Contracts\Admin\Upgrades;

/**
 * Exit if accessed directly
 */
if ( ! defined('ABSPATH')) {
	exit;
}

interface Upgrade
{
	/**
	 * Get the version we are upgrading too
	 *
	 * @return string
	 */
	public function getVersion();

	/**
	 * Checks if the provided version is eligible for an upgrade
	 *
	 * @param  string $version
	 * @return boolean
	 */
	public function isEligible($version);

	/**
	 * Executes the upgrade
	 *
	 * @return void
	 */
	public function upgrade();
}
