<?php

namespace EasingSlider\Foundation\Options;

use EasingSlider\Foundation\Contracts\Options\OptionArray as OptionArrayContract;
use EasingSlider\Foundation\Options\Option;

/**
 * Exit if accessed directly
 */
if ( ! defined('ABSPATH')) {
	exit;
}

abstract class OptionArray extends Option implements OptionArrayContract
{
	/**
	 * Value
	 *
	 * @var array
	 */
	public $value = array();

	/**
	 * Checks if an offset exists
	 *
	 * @param  mixed $offset
	 * @return boolean
	 */
	public function offsetExists($offset)
	{
		return isset($this->value[$offset]);
	}

	/**
	 * Gets an offset
	 *
	 * @param  mixed $offset
	 * @return mixed
	 */
	public function offsetGet($offset)
	{
		return $this->value[$offset];
	}

	/**
	 * Sets a value
	 *
	 * @param  mixed $offset
	 * @param  mixed $value
	 * @return void
	 */
	public function offsetSet($offset, $value)
	{
		$this->value[$offset] = $value;
	}

	/**
	 * Unsets a value
	 *
	 * @param  int $offset
	 * @return void
	 */
	public function offsetUnset($offset)
	{
		unset($this->value[$offset]);
	}
}
