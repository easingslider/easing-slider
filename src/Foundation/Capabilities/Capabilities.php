<?php

namespace EasingSlider\Foundation\Capabilities;

use EasingSlider\Foundation\Contracts\Capabilities\Capabilities as CapabilitiesContract;
use WP_Roles;

/**
 * Exit if accessed directly
 */
if ( ! defined('ABSPATH')) {
	exit;
}

abstract class Capabilities implements CapabilitiesContract
{
	/**
	 * Capabilities
	 *
	 * @var array
	 */
	protected $capabilities;

	/**
	 * Constructor
	 *
	 * @return void
	 */
	public function __construct()
	{
		$this->capabilities = $this->getCapabilities();
	}

	/**
	 * Gets the user roles
	 *
	 * @return \WP_Roles
	 */
	protected function getUserRoles()
	{
		global $wp_roles;

		if ( ! isset($wp_roles)) {
			$wp_roles = new WP_Roles();
		}

		return $wp_roles;
	}

	/**
	 * Adds the capabilities to all user roles
	 *
	 * @return void
	 */
	public function addToRoles()
	{
		$userRoles = $this->getUserRoles();

		foreach ($userRoles->roles as $role => $object) {
			foreach ($this->capabilities as $capability) {
				$userRoles->add_cap($role, $capability);
			}
		}
	}

	/**
	 * Removes the capabilities from all user roles
	 *
	 * @return void
	 */
	public function removeFromRoles()
	{
		$userRoles = $this->getUserRoles();

		foreach ($userRoles->roles as $role => $object) {
			foreach ($this->capabilities as $capability) {
				$userRoles->remove_cap($role, $capability);
			}
		}
	}

	/**
	 * Gets the capabilities
	 *
	 * @return array
	 */
	abstract protected function getCapabilities();
}
