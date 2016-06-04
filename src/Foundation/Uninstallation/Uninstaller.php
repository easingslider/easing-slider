<?php

namespace EasingSlider\Foundation\Uninstallation;

use EasingSlider\Foundation\Contracts\Uninstallation\Uninstaller as UninstallerContract;

/**
 * Exit if accessed directly
 */
if ( ! defined('ABSPATH')) {
	exit;
}

abstract class Uninstaller implements UninstallerContract
{
	/**
	 * Executes uninstallation
	 *
	 * @return void
	 */
	public function uninstall()
	{
		//
	}
}
