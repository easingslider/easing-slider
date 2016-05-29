<?php

namespace EasingSlider\Foundation\Admin\Validators;

use EasingSlider\Foundation\Contracts\Admin\Validators\Validator as ValidatorContract;

/**
 * Exit if accessed directly
 */
if ( ! defined('ABSPATH')) {
	exit;
}

abstract class Validator implements ValidatorContract
{
	/**
	 * Rules
	 *
	 * @var array
	 */
	protected $rules;

	/**
	 * Constructor
	 *
	 * @return void
	 */
	public function __construct()
	{
		$this->rules = $this->getRules();
	}

	/**
	 * Gets the validation rule for the provided key
	 *
	 * @param  string $key
	 * @return string|false
	 */
	protected function getRule($key)
	{
		if (isset($this->rules[$key])) {
			return $this->rules[$key];
		}

		return false;
	}

	/**
	 * Validates a specific value
	 *
	 * @param  string $rule
	 * @param  mixed  $value
	 * @return mixed
	 */
	public function validateValue($rule, $value)
	{
		switch ($rule) {
			case 'integer' :
				return $this->validateInteger($value);
				break;
				
			case 'boolean' :
				return $this->validateBoolean($value);
				break;

			case 'string' :
				return $this->validateString($value);
				break;

			case 'array' :
				return array_map(array($this, 'validateString'), $value);
				break;

			case 'decode_json_array' :
				return $this->decodeJsonArray($value);
				break;
		}

		return $value;
	}

	/**
	 * Validate integer
	 *
	 * @param  string $value
	 * @return int
	 */
	public function validateInteger($value)
	{
		return intval($value);
	}

	/**
	 * Validate boolean
	 *
	 * @param  string $value
	 * @return boolean
	 */
	protected function validateBoolean($value)
	{
		return filter_var($value, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
	}

	/**
	 * Validate string
	 *
	 * @param  string $value
	 * @return string
	 */
	protected function validateString($value)
	{
		return sanitize_text_field($value);
	}

	/**
	 * Decodes a JSON array
	 *
	 * @param  array $value
	 * @return array
	 */
	protected function decodeJsonArray($value)
	{
		if ( ! is_array($value)) {
			return array();
		}

		return array_map('json_decode', array_map('stripslashes', $value));
	}

	/**
	 * Validates the provided data
	 *
	 * @param  array $data
	 * @return array
	 */
	public function validate($data)
	{
		// Validate each data item
		foreach ($data as $key => $value) {

			// Get the validation rule
			$rule = $this->getRule($key);

			// Validate the value, if we have a rule
			if ($rule) {
				$data[$key] = $this->validateValue($rule, $value);
			}

		}

		return $data;
	}

	/**
	 * Gets the validation rules
	 *
	 * @return array
	 */
	abstract protected function getRules();
}
