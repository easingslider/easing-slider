<?php

namespace EasingSlider\Foundation\Activation;

use EasingSlider\Foundation\Contracts\Activation\Activator as ActivatorContract;

/**
 * Exit if accessed directly
 */
if ( ! defined('ABSPATH')) {
	exit;
}

abstract class Activator implements ActivatorContract
{
	/**
	 * Executes activation
	 *
	 * @return void
	 */
	public function activate()
	{
		//
	}
}
