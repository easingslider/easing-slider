<?php

namespace EasingSlider\Foundation\Contracts\Activation;

/**
 * Exit if accessed directly
 */
if ( ! defined('ABSPATH')) {
	exit;
}

interface Activator
{
	/**
	 * Executes activation
	 *
	 * @return void
	 */
	public function activate();
}
