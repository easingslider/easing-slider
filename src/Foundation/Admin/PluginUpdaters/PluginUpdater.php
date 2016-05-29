<?php

namespace EasingSlider\Foundation\Admin\PluginUpdaters;

use EasingSlider\Foundation\Contracts\Options\OptionArray;
use stdClass;

/**
 * Exit if accessed directly
 */
if ( ! defined('ABSPATH')) {
	exit;
}

// Uncomment for testing
// set_site_transient('update_plugins', null);

abstract class PluginUpdater
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
	 * Plugin Name
	 *
	 * @var string
	 */
	protected $pluginName;

	/**
	 * Plugin Slug
	 *
	 * @var string
	 */
	protected $pluginSlug;

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
		$this->license = $license;
		$this->name = $name;
		$this->file = $file;
		$this->version = $version;

		$this->pluginName = plugin_basename($this->file);
		$this->pluginSlug = basename($this->pluginName, '.php');

		$this->defineHooks();
	}

	/**
	 * Define hooks
	 *
	 * @return void
	 */
	protected function defineHooks()
	{
		add_filter('pre_set_site_transient_update_plugins', array($this, 'checkForUpdate'));
		add_filter('plugins_api', array($this, 'getPluginDetails'), 10, 3);
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
	 * Check for Updates at the defined API endpoint and modify the update array.
	 *
	 * This function dives into the update API just when WordPress creates its update array,
	 * then adds a custom API call and injects the custom plugin data retrieved from the API.
	 * It is reassembled from parts of the native WordPress plugin update code.
	 * See wp-includes/update.php line 121 for the original wp_update_plugins() function.
	 *
	 * @param  array $transientData
	 * @return array
	 */
	public function checkForUpdate($transientData)
	{
		global $pagenow;

		// Create transient data object if it isn't already one
		if ( ! is_object($transientData)) {
			$transientData = new stdClass;
		}

		// If on plugins page
		if ('plugins.php' == $pagenow && is_multisite()) {
			return $transientData;
		}

		// If we have transient data for this plugin, continue checking for an update
		if (empty($transientData->response) || empty($transientData->response[$this->pluginName])) {

			// Get the version info
			$versionInfo = $this->getLatestVersion();

			// Merge version info with other plugin info
			if (false !== $versionInfo && is_object($versionInfo) && isset($versionInfo->new_version)) {

				// If we have a new version, add update info
				if (version_compare($this->version, $versionInfo->new_version, '<')) {
					$transientData->response[$this->pluginName] = $versionInfo;
				}

				// Set last check datetime
				$transientData->last_checked = time();
				$transientData->checked[$this->pluginName] = $this->version;

			}

		}

		return $transientData;
	}

	/**
     * Updates information on the "View version x.x details" page with custom data.
     *
     * @param  mixed  $data
     * @param  string $action
     * @param  object $args
     * @return object $data
     */
	public function getPluginDetails($data, $action, $args)
	{
		// Bail if not getting plugin information
		if ('plugin_information' != $action) {
			return $data;
		}

		// Check plugin slug/basename matches
        if ( ! isset($args->slug) || ($args->slug != $this->pluginSlug) ) {
            return $data;
        }

        // Get latest version information
        $versionInfo = $this->getLatestVersion();

        // Check we got a response, and if so, replace data with ours
        if ($versionInfo) {
        	$data = $versionInfo;
        }

		return $data;
	}

	/**
	 * Gets the latest plugin version
	 *
	 * @return object
	 */
	abstract protected function getLatestVersion();
}
