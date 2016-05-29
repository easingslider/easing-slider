<?php

namespace EasingSlider\Foundation\Contracts\Admin\Notices;

/**
 * Exit if accessed directly
 */
if ( ! defined('ABSPATH')) {
	exit;
}

interface Notice
{
	/**
	 * Displays the notice
	 *
	 * @return void
	 */
	public function display();
}
