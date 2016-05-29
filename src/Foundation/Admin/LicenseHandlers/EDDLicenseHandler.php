<?php

namespace EasingSlider\Foundation\Admin\LicenseHandlers;

use EasingSlider\Foundation\Admin\LicenseHandlers\LicenseHandler;

/**
 * Exit if accessed directly
 */
if ( ! defined('ABSPATH')) {
	exit;
}

class EDDLicenseHandler extends LicenseHandler
{
	/**
	 * Provides a response object
	 *
	 * @param  object|WP_Error $response
	 * @return object
	 */
	protected function response($response)
	{
		// Check for WordPress errors
		if (is_wp_error($response)) {
			return $this->errorResponse(
				$response->get_error_message()
			);
		}

		// Get response body
		$data = json_decode(wp_remote_retrieve_body($response));

		// Check we received a response body
		if (is_null($data)) {
			return $this->errorResponse(
				__('Failed to connect to API. No response provided. Please contact support.', 'easingslider')
			);
		}
		
		// Check for errors
		if (isset($data->error)) {
			return $this->errorResponse(
				$this->getErrorMessage($data)
			);
		}

		// Check for success
		if (isset($data->success)) {
			return $this->successResponse(
				$this->getSuccessMessage($data)
			);
		}
	}

	/**
	 * Gets the appropriate success message
	 * 
	 * @param  object $data
	 * @return string
	 */
	protected function getSuccessMessage($data)
	{
		switch ($data->license) {
			case 'valid':
				$message = __('License has been activated successfully.', 'easingslider');
				break;

			case 'deactivated':
				$message = __('License has been deactivated successfully.', 'easingslider');
				break;
		}

		return apply_filters('easingslider_edd_license_handler_success_response', $message, $data);
	}

	/**
	 * Gets the appropriate success message
	 * 
	 * @param  object $data
	 * @return string
	 */
	protected function getErrorMessage($data)
	{
		switch ($data->error) {
			case 'expired':
				$message = sprintf(__('Your license key has expired on %s. Please renew your license key.', 'easingslider'), date_i18n(get_option('date_format'), strtotime($data->expires)));
				break;

			case 'invalid_item_id':
				$message = __('An invalid item ID has been provided. Please contact support.', 'easingslider');
				break;

			case 'item_name_mismatch':
				$message = __('The license key entered is not a valid license key for this plugin.', 'easingslider');
				break;

			case 'license_not_activable':
				$message = __('The license key provided is a "bundle" license key. Bundle license keys cannot be activated.', 'easingslider');
				break;

			case 'missing':
				$message = __('The license key you have entered does not exist.', 'easingslider');
				break;

			case 'no_activations_left':
				$message = sprinft(__('Your license key has reached its activation limit of %d. Please upgrade your license key to get more activations.', 'easingslider'), $data->max_sites);
				break;

			case 'revoked':
				$message = __('Your license key has been revoked. Please contact support.', 'easingslider');
				break;

			default:
				$message = __('An unknown error occurred. Please contact support.', 'easingslider');
				break;
		}

		return apply_filters('easingslider_edd_license_handler_error_response', $message, $data);
	}

	/**
	 * Checks if a license is valid
	 *
	 * @return boolean
	 */
	public function isValid()
	{
		$requestParams = array(
			'edd_action' => 'check_license',
			'license'    => $this->getKey(),
			'item_name'  => urlencode($this->name)
		);

		// Get the request URL
		$requestUrl = add_query_arg($requestParams, $this->getApiUrl());

		// Get response from API
		$response = wp_remote_get(
			$requestUrl,
			array(
				'timeout'   => 15,
				'sslverify' => false
			)
		);

		// Check for errors
		if (is_wp_error($response)) {
			return false;
		}

		// Get license data
		$data = json_decode(wp_remote_retrieve_body($response));

		// Check license is valid
		if ($data && 'valid' == $data->license) {
			return true;
		}

		return false;
	}

	/**
	 * Activates the license key
	 *
	 * @return object
	 */
	public function activate()
	{
		$requestParams = array(
			'edd_action' => 'activate_license',
			'license'    => $this->getKey(),
			'item_name'  => urlencode($this->name),
			'url'        => home_url()
		);

		// Get response from API
		$response = wp_remote_post(
			$this->getApiUrl(),
			array(
				'timeout'   => 15,
				'sslverify' => false,
				'body'      => $requestParams
			)
		);

		return $this->response($response);
	}

	/**
	 * Deactivates the license key
	 *
	 * @return object
	 */
	public function deactivate()
	{
		$requestParams = array(
			'edd_action' => 'deactivate_license',
			'license'    => $this->getKey(),
			'item_name'  => urlencode($this->name),
			'url'        => home_url()
		);

		// Get response from API
		$response = wp_remote_post(
			$this->getApiUrl(),
			array(
				'timeout'   => 15,
				'sslverify' => false,
				'body'      => $requestParams
			)
		);

		return $this->response($response);
	}
}