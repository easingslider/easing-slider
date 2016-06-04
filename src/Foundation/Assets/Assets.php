<?php

namespace EasingSlider\Foundation\Assets;

use EasingSlider\Foundation\Contracts\Assets\Assets as AssetsContract;

/**
 * Exit if accessed directly
 */
if ( ! defined('ABSPATH')) {
	exit;
}

abstract class Assets implements AssetsContract
{
	/**
	 * Gets the suffix
	 *
	 * @return string
	 */
	protected function getSuffix()
	{
		// Get suffix based on script debugging flag
		return (defined('SCRIPT_DEBUG') && SCRIPT_DEBUG) ? '' : '.min';
	}

	/**
	 * Enqueues the assets
	 *
	 * @return void
	 */
	public function enqueue()
	{
		//
	}
}
