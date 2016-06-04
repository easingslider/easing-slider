<?php

namespace EasingSlider\Foundation\Admin\LicenseHandlers;

use EasingSlider\Foundation\Contracts\Admin\LicenseHandlers\LicenseHandler as LicenseHandlerContract;
use EasingSlider\Foundation\Contracts\Options\OptionArray;

/**
 * Exit if accessed directly
 */
if ( ! defined('ABSPATH')) {
	exit;
}

abstract class LicenseHandler implements LicenseHandlerContract
{
	/**
	 * License
	 *
	 * @var \EasingSlider\Foundation\Contracts\Options\OptionArray
	 */
	protected $license;

	/**
	 * Name
	 *
	 * @var string
	 */
	protected $name;

	/**
	 * File
	 *
	 * @var string
	 */
	protected $file;

	/**
	 * Version
	 *
	 * @var string
	 */
	protected $version;

	/**
	 * Constructor
	 *
	 * @param  \EasingSlider\Foundation\Contracts\Options\OptionArray $license
	 * @param  string                                                 $name
	 * @param  string                                                 $file
	 * @param  string                                                 $version
	 * @return void
	 */
	public function __construct(OptionArray $license, $name, $file, $version)
	{
		$this->name = $name;
		$this->file = $file;
		$this->version = $version;
		$this->license = $license;
	}

	/**
	 * Provides a success response
	 *
	 * @param  string $message
	 * @return object
	 */
	protected function successResponse($message)
	{
		return (object) array(
			'success' => true,
			'message' => $message
		);
	}

	/**
	 * Provides an error response
	 *
	 * @param  string $message
	 * @return object
	 */
	protected function errorResponse($message)
	{
		return (object) array(
			'error'   => true,
			'message' => $message
		);
	}

	/**
	 * Gets the API URL
	 *
	 * @return string
	 */
	protected function getApiUrl()
	{
		return easingslider_api_url();
	}

	/**
	 * Gets the license key
	 *
	 * @return string
	 */
	public function getKey()
	{
		return $this->license['key'];
	}

	/**
	 * Sets the license key
	 *
	 * @param  string $key
	 * @return void
	 */
	public function setKey($key)
	{
		$this->license['key'] = $key;
		$this->license->save();
	}

	/**
	 * Sets the license status
	 *
	 * @param  string $status
	 * @return void
	 */
	public function setStatus($status)
	{
		$this->license['status'] = $status;
		$this->license->save();
	}

	/**
	 * Checks if a license is valid
	 *
	 * @return boolean
	 */
	public function isValid()
	{
		//
	}

	/**
	 * Activates the license key
	 *
	 * @return object
	 */
	public function activate()
	{
		//
	}

	/**
	 * Deactivates the license key
	 *
	 * @return object
	 */
	public function deactivate()
	{
		//
	}
}