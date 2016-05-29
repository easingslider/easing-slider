<?php

namespace EasingSlider\Foundation\Contracts\Admin\Actions;

/**
 * Exit if accessed directly
 */
if ( ! defined('ABSPATH')) {
	exit;
}

interface Processor
{
	/**
	 * Processes an action
	 *
	 * @return void
	 */
	public function process();
}
