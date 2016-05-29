<?php

namespace EasingSlider\Foundation\Contracts\Admin\Upgrades;

/**
 * Exit if accessed directly
 */
if ( ! defined('ABSPATH')) {
	exit;
}

interface Upgrader
{
	/**
	 * Executes an upgrade
	 *
	 * @return void
	 */
	public function upgrade();
}
