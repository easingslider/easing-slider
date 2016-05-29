<?php

namespace EasingSlider\Foundation\Contracts\Capabilities;

/**
 * Exit if accessed directly
 */
if ( ! defined('ABSPATH')) {
	exit;
}

interface Capabilities
{
	/**
	 * Adds the capabilities to all user roles
	 *
	 * @return void
	 */
	public function addToRoles();

	/**
	 * Removes the capabilities from all user roles
	 *
	 * @return void
	 */
	public function removeFromRoles();
}
