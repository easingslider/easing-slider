<?php

namespace EasingSlider\Plugin\Admin\Panels;

use EasingSlider\Foundation\Admin\Panels\Panel;
use EasingSlider\Plugin\Contracts\Options\License;
use EasingSlider\Plugin\Slider;

/**
 * Exit if accessed directly
 */
if ( ! defined('ABSPATH')) {
	exit;
}

// Uncomment for testing
// delete_site_transient('easingslider_addons');

class Addons extends Panel
{
	/**
	 * License
	 *
	 * @var \EasingSlider\Plugin\Contracts\Options\License
	 */
	protected $license;

	/**
	 * Constructor
	 *
	 * @param  \EasingSlider\Plugin\Contracts\Options\License $license
	 * @return void
	 */
	public function __construct(License $license)
	{
		$this->license = $license;
	}

	/**
	 * Gets the available addons
	 *
	 * @return array|false
	 */
	protected function getAddons()
	{
		$addons = get_site_transient('easingslider_addons');

		if (false === $addons) {

			// Get the request parameters
			$requestParams = array(
				'easingslider_action' => 'get_addons',
				'name'                => EASINGSLIDER_NAME,
				'license'             => $this->license['key'],
				'url'                 => home_url()
			);

			// Get the addons from API
			$response = wp_remote_post(
				$this->getApiUrl(),
				array(
					'timeout' => 15,
					'sslverify' => false,
					'body'      => $requestParams
				)
			);

			// Check for errors in response
			if (is_wp_error($response)) {
				return false;
			}

			// Get addons from the response body
			$addons = json_decode(wp_remote_retrieve_body($response));

			// Check for errors in body
			if (is_wp_error($addons)) {
				return false;
			}

			// Cache for use later
			set_site_transient('easingslider_addons', $addons, 3600);

		}

		return $addons;
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
	 * Displays the panel
	 *
	 * @return void
	 */
	public function display()
	{
		$this->showView('view-addons', array(
			'addons'           => $this->getAddons(),
			'addonsLink'       => 'http://easingslider.com/addons',
			'installedPlugins' => get_plugins(),
			'license'          => $this->license,
			'page'             => $this->getPage(),
			'purchaseLink'     => 'http://easingslider.com/purchase'
		));
	}
}
