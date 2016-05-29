<?php

namespace EasingSlider\Foundation\Contracts\Admin\Panels;

/**
 * Exit if accessed directly
 */
if ( ! defined('ABSPATH')) {
	exit;
}

interface Panel
{
	/**
	 * Displays the panel
	 *
	 * @return void
	 */
	public function display();
}
