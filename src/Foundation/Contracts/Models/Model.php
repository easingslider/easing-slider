<?php

namespace EasingSlider\Foundation\Contracts\Models;

/**
 * Exit if accessed directly
 */
if ( ! defined('ABSPATH')) {
	exit;
}

interface Model
{
	/**
	 * Fills attributes
	 *
	 * @param  array $data
	 * @return void
	 */
	public function fill($data = array());

	/**
	 * Set an attribute value
	 *
	 * @param  string $key
	 * @param  mixed  $value
	 * @return void
	 */
	public function set($key, $value);

	/**
	 * Get an attribute value
	 *
	 * @param  string $key
	 * @return array
	 */
	public function get($key);

	/**
	 * Gets the model attributes
	 *
	 * @return array 
	 */
	public function getAttributes();
}
