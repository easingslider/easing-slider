<?php

namespace EasingSlider\Plugin\Admin;

use EasingSlider\Foundation\Admin\PluginUpdaters\PluginUpdater;
use EasingSlider\Plugin\Contracts\Options\License;

/**
 * Exit if accessed directly
 */
if ( ! defined('ABSPATH')) {
	exit;
}

// Uncomment for testing
// set_site_transient('update_plugins', null);

class AddonUpdater extends PluginUpdater
{
	/**
	 * Constructor
	 *
	 * @param  \EasingSlider\Plugin\Contracts\Options\License $license
	 * @param  string                                         $name
	 * @param  string                                         $file
	 * @param  string                                         $version
	 * @return void
	 */
	public function __construct(License $license, $name, $file, $version)
	{
		parent::__construct($license, $name, $file, $version);
	}

	/**
	 * Gets the latest plugin version
	 *
	 * @return object|false
	 */
	protected function getLatestVersion()
	{
		$requestParams = array(
			'easingslider_action' => 'addon_version',
			'license'             => $this->license['key'],
			'name'                => $this->name,
			'slug'                => $this->pluginSlug,
			'url'                 => home_url()
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

		// Check for WordPress error(s)
		if (is_wp_error($response)) {
			return false;
		}

		// Get reponse body
		$data = json_decode(wp_remote_retrieve_body($response));

		// Bail if we got no response body
		if (is_null($data)) {
			return false;
		}

		// Unserialize sections
		if (isset($data->sections)) {
			$data->sections = maybe_unserialize($data->sections);
		}

		return $data;
	}
}
