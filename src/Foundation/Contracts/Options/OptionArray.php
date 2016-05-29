<?php

namespace EasingSlider\Foundation\Contracts\Options;

use ArrayAccess;

/**
 * Exit if accessed directly
 */
if ( ! defined('ABSPATH')) {
	exit;
}

interface OptionArray extends ArrayAccess
{
	/**
	 * Saves the option
	 *
	 * @return viod
	 */
	public function save();

	/**
	 * Deletes the option
	 *
	 * @return void
	 */
	public function delete();
}
