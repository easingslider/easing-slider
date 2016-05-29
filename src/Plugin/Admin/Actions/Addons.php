<?php

namespace EasingSlider\Plugin\Admin\Actions;

use EasingSlider\Foundation\Admin\Actions\Actions;
use EasingSlider\Foundation\Contracts\Admin\LicenseHandlers\LicenseHandler;
use EasingSlider\Foundation\Contracts\Admin\Notices\NoticeHandler;
use EasingSlider\Plugin\Admin\Validators\License as LicenseValidator;

/**
 * Exit if accessed directly
 */
if ( ! defined('ABSPATH')) {
	exit;
}

class Addons extends Actions
{
	/**
	 * License Handler
	 *
	 * @var \EasingSlider\Foundation\Contracts\Admin\LicenseHandlers\LicenseHandler
	 */
	protected $licenseHandler;

	/**
	 * Constructor
	 *
	 * @param  \EasingSlider\Foundation\Contracts\Admin\Notices\NoticeHandler          $notices
	 * @param  \EasingSlider\Foundation\Contracts\Admin\LicenseHandlers\LicenseHandler $license
	 * @return void
	 */
	public function __construct(NoticeHandler $notices, LicenseHandler $licenseHandler)
	{
		$this->licenseHandler = $licenseHandler;

		parent::__construct($notices);
	}

	/**
	 * Defines our actions
	 *
	 * @return void
	 */
	protected function defineActions()
	{
		add_action('easingslider_activate_license', array($this, 'activateLicense'));
		add_action('easingslider_deactivate_license', array($this, 'deactivateLicense'));
	}

	/**
	 * Gets a new validator instance
	 *
	 * @return array
	 */
	protected function validator()
	{
		return new LicenseValidator();
	}

	/**
	 * Activates a license key
	 *
	 * @param  array $data
	 * @return void
	 */
	public function activateLicense($data = array())
	{
		// Check that license key has been entered
		if ( ! empty($data['license_key'])) {

			// Set the license key
			$this->licenseHandler->setKey(trim($data['license_key']));

			// Activate the license
			$response = $this->licenseHandler->activate();

			// Show response
			if ($response) {
				if ( ! empty($response->success)) {

					// Set the license status
					$this->licenseHandler->setStatus('valid');

					// Clear addons cache
					delete_site_transient('easingslider_addons');

					// Tell the user we've activated the license
					$this->notices->success('license_activated', $response->message);

				} else {

					// Tell the user we've had an error
					$this->notices->error('license_activation_failed', $response->message);

				}
			}

		} else {

			// Tell user to enter a license key
			$this->notices->error('enter_license', __('Please enter a license key.', 'easingslider'));

		}
	}

	/**
	 * Deactivates a license key
	 *
	 * @param  array $data
	 * @return void
	 */
	public function deactivateLicense($data = array())
	{
		// Deactivate the license
		$response = $this->licenseHandler->deactivate();

		// Show response
		if ( ! empty($response->success)) {

			// Reset the license key & status
			$this->licenseHandler->setKey('');
			$this->licenseHandler->setStatus('');

			// Clear addons cache
			delete_site_transient('easingslider_addons');

			// Tell the user the license has been deactivated
			$this->notices->success('license_deactivated', $response->message);

		} else {

			// Tell the user we've had an error
			$this->notices->error('license_deactivation_failed', $response->message);

		}
	}
}
