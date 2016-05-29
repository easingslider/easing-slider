<?php

namespace EasingSlider\Foundation\Contracts\Admin\LicenseHandlers;

/**
 * Exit if accessed directly
 */
if ( ! defined('ABSPATH')) {
	exit;
}

interface LicenseHandler
{
	/**
	 * Gets the license key
	 *
	 * @return string
	 */
	public function getKey();

	/**
	 * Sets the license key
	 *
	 * @param  string $key
	 * @return void
	 */
	public function setKey($key);

	/**
	 * Sets the license status
	 *
	 * @param  string $status
	 * @return void
	 */
	public function setStatus($status);

	/**
	 * Checks if a license is valid
	 *
	 * @return boolean
	 */
	public function isValid();

	/**
	 * Activates the license key
	 *
	 * @return boolean
	 */
	public function activate();

	/**
	 * Deactivates the license key
	 *
	 * @return boolean
	 */
	public function deactivate();
}
