<?php

namespace EasingSlider\Plugin\Uninstallation;

use EasingSlider\Foundation\Contracts\Capabilities\Capabilities;
use EasingSlider\Foundation\Contracts\Repositories\Repository;
use EasingSlider\Foundation\Uninstallation\Uninstaller as BaseUninstaller;
use EasingSlider\Plugin\Contracts\Options\License;
use EasingSlider\Plugin\Contracts\Options\Settings;
use EasingSlider\Plugin\Contracts\Options\Version;

/**
 * Exit if accessed directly
 */
if ( ! defined('ABSPATH')) {
	exit;
}

class Uninstaller extends BaseUninstaller
{
	/**
	 * Capabilities
	 *
	 * @var \EasingSlider\Foundation\Contracts\Capabilities\Capabilities
	 */
	protected $capabilities;

	/**
	 * License
	 *
	 * @var \EasingSlider\Plugin\Contracts\Options\License
	 */
	protected $license;

	/**
	 * Settings
	 *
	 * @var \EasingSlider\Plugin\Contracts\Options\Settings
	 */
	protected $settings;

	/**
	 * Sliders
	 *
	 * @var \EasingSlider\Foundation\Contracts\Repositories\Repository
	 */
	protected $sliders;

	/**
	 * Version
	 *
	 * @var \EasingSlider\Plugin\Contracts\Options\Version
	 */
	protected $version;

	/**
	 * Constructor
	 *
	 * @param  \EasingSlider\Foundation\Contracts\Capabilities\Capabilities $capabilities
	 * @param  \EasingSlider\Plugin\Contracts\Options\License               $license
	 * @param  \EasingSlider\Plugin\Contracts\Options\Settings              $settings
	 * @param  \EasingSlider\Foundation\Contracts\Repositories\Repository   $sliders
	 * @param  \EasingSlider\Plugin\Contracts\Options\Version               $version
	 * @return void
	 */
	public function __construct(
		Capabilities $capabilities,
		License $license,
		Settings $settings,
		Repository $sliders,
		Version $version
	)
	{
		$this->capabilities = $capabilities;
		$this->license = $license;
		$this->settings = $settings;
		$this->sliders = $sliders;
		$this->version = $version;
	}

	/**
	 * Executes uninstall
	 *
	 * @return void
	 */
	public function uninstall()
	{
		$this->removeSliders();

		$this->removeSettings();

		$this->removeCapabilities();

		$this->removeLicense();

		$this->removeVersion();

		$this->removeTransients();
	}

	/**
	 * Removes all sliders
	 *
	 * @return void
	 */
	protected function removeSliders()
	{
		$sliders = $this->sliders->all();

		foreach ($sliders as $slider) {
			$this->sliders->delete($slider->ID);
		}
	}

	/**
	 * Removes the plugin settings
	 *
	 * @return void
	 */
	protected function removeSettings()
	{
		$this->settings->delete();
	}

	/**
	 * Removes the plugin capabilities
	 *
	 * @return void
	 */
	protected function removeCapabilities()
	{
		$this->capabilities->removeFromRoles();
	}

	/**
	 * Removes the plugin license
	 *
	 * @return void
	 */
	protected function removeLicense()
	{
		$this->license->delete();
	}

	/**
	 * Removes the plugin version
	 *
	 * @return void
	 */
	protected function removeVersion()
	{
		$this->version->delete();
	}

	/**
	 * Removes any cached transients
	 *
	 * @return void
	 */
	protected function removeTransients()
	{
		delete_site_transient('easingslider_addons');
	}
}
