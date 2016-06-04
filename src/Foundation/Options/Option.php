<?php

namespace EasingSlider\Foundation\Options;

use EasingSlider\Foundation\Contracts\Options\Option as OptionContract;

/**
 * Exit if accessed directly
 */
if ( ! defined('ABSPATH')) {
	exit;
}

abstract class Option implements OptionContract
{
	/**
	 * Name
	 *
	 * @var string
	 */
	protected $name;

	/**
	 * Value
	 *
	 * @var mixed
	 */
	public $value;

	/**
	 * Constructor
	 *
	 * @return void
	 */
	public function __construct()
	{
		$this->value = $this->getValue();

		add_option($this->name, $this->getDefaults());
	}

	/**
	 * Gets the option name
	 *
	 * @return string
	 */
	public function getName()
	{
		return $this->name;
	}

	/**
	 * Gets the option value
	 *
	 * @return mixed
	 */
	public function getValue()
	{
		return get_option($this->name, $this->getDefaults());
	}

	/**
	 * Sets the option value
	 *
	 * @param  mixed $value
	 * @return void
	 */
	public function setValue($value)
	{
		$this->value = $value;
	}

	/**
	 * Saves the option
	 *
	 * @return viod
	 */
	public function save()
	{
		update_option($this->name, $this->value);
	}

	/**
	 * Deletes the option
	 *
	 * @return void
	 */
	public function delete()
	{
		delete_option($this->name);
	}

	/**
	 * Gets the default settings
	 *
	 * @return mixed
	 */
	public function getDefaults()
	{
		//
	}
}
