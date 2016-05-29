<?php

namespace EasingSlider\Foundation\Contracts\Assets;

/**
 * Exit if accessed directly
 */
if ( ! defined('ABSPATH')) {
	exit;
}

interface Assets
{
	/**
	 * Enqueues the assets
	 *
	 * @return void
	 */
	public function enqueue();
}
