<?php

namespace EasingSlider\Foundation\Models;

use EasingSlider\Foundation\Contracts\Models\Model as ModelContract;

/**
 * Exit if accessed directly
 */
if ( ! defined('ABSPATH')) {
	exit;
}

abstract class Model implements ModelContract
{
	/**
	 * Attributes
	 *
	 * @var array
	 */
	protected $attributes;

	/**
	 * Constructor
	 *
	 * @return void
	 */
	public function __construct()
	{
		$this->attributes = $this->getDefaults();
	}

	/**
	 * __get function.
	 *
	 * @param  string $key
	 * @return mixed
	 */
	public function __get($key)
	{
		return $this->get($key);
	}

	/**
	 * __set function.
	 *
	 * @param string $key
	 * @param mixed  $value
	 */
	public function __set($key, $value)
	{
		$this->set($key, $value);
	}

	/**
	 * __isset function.
	 *
	 * @param  string $key
	 * @return bool
	 */
	public function __isset($key)
	{
		return isset($this->attributes[$key]);
	}

	/**
	 * Fills attributes
	 *
	 * @param  array $data
	 * @return void
	 */
	public function fill($data = array())
	{
		foreach ($data as $key => $value) {
			$this->set($key, $value);
		}
	}

	/**
	 * Set an attribute value
	 *
	 * @param  string $key
	 * @param  mixed  $value
	 * @return void
	 */
	public function set($key, $value)
	{
		$this->attributes[$key] = $value;
	}

	/**
	 * Get an attribute value
	 *
	 * @param  string $key
	 * @return array
	 */
	public function get($key)
	{
		return $this->attributes[$key];
	}

	/**
	 * Gets the model attributes
	 *
	 * @return array 
	 */
	public function getAttributes()
	{
		return $this->attributes;
	}

	/**
	 * Gets the model defaults
	 *
	 * @return array
	 */
	public function getDefaults()
	{
		//
	}
}
