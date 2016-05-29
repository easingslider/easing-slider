<?php

namespace EasingSlider\Foundation\Contracts\Uninstallation;

/**
 * Exit if accessed directly
 */
if ( ! defined('ABSPATH')) {
	exit;
}

interface Uninstaller
{
	/**
	 * Executes uninstallation
	 *
	 * @return void
	 */
	public function uninstall();
}
