<?php

namespace EasingSlider\Foundation\Contracts\Options;

/**
 * Exit if accessed directly
 */
if ( ! defined('ABSPATH')) {
	exit;
}

interface Option
{
	/**
	 * Gets the option name
	 *
	 * @return string
	 */
	public function getName();

	/**
	 * Gets the option value
	 *
	 * @return mixed
	 */
	public function getValue();

	/**
	 * Sets the option value
	 *
	 * @param  mixed $value
	 * @return void
	 */
	public function setValue($value);

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
