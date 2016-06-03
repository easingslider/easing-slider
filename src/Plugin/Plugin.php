<?php

namespace EasingSlider\Plugin;

use EasingSlider\Foundation\Plugin as BasePlugin;

/**
 * Exit if accessed directly
 */
if ( ! defined('ABSPATH')) {
	exit;
}

final class Plugin extends BasePlugin
{
	/**
	 * Activator
	 *
	 * @var \EasingSlider\Foundation\Contracts\Activation\Activator
	 */
	protected $activator;

	/**
	 * Admin
	 *
	 * @var \EasingSlider\Foundation\Contracts\Admin\Admin
	 */
	protected $admin;

	/**
	 * Assets
	 *
	 * @var \EasingSlider\Foundation\Contracts\Assets\Assets
	 */
	protected $assets;

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
	 * Shortcode
	 *
	 * @var \EasingSlider\Foundation\Contracts\Shortcodes\Shortcode
	 */
	protected $shortcode;

	/**
	 * Sliders
	 *
	 * @var \EasingSlider\Foundation\Contracts\Repositories\Repository
	 */
	protected $sliders;

	/**
	 * Template Loader
	 *
	 * @var \EasingSlider\Foundation\Contracts\TemplateLoaders\TemplateLoader
	 */
	protected $templateLoader;

	/**
	 * Uninstaller
	 *
	 * @var \EasingSlider\Foundation\Contracts\Uninstallation\Uninstaller
	 */
	protected $uninstaller;

	/**
	 * Version
	 *
	 * @var \EasingSlider\Plugin\Contracts\Options\Version
	 */
	protected $version;

	/**
	 * Widget
	 *
	 * @var \EasingSlider\Foundation\Contracts\Widgets\Widget
	 */
	protected $widget;

	/**
	 * Aliases
	 *
	 * @var array
	 */
	protected $aliases = array(
		'\EasingSlider\Foundation\Contracts\Activation\Activator'           => '\EasingSlider\Plugin\Activation\Activator',
		'\EasingSlider\Foundation\Contracts\Admin\Admin'                    => '\EasingSlider\Plugin\Admin\Admin',
		'\EasingSlider\Foundation\Contracts\Assets\Assets'                  => '\EasingSlider\Plugin\Assets\PublicAssets',
		'\EasingSlider\Foundation\Contracts\Capabilities\Capabilities'      => '\EasingSlider\Plugin\Capabilities\Capabilities',
		'\EasingSlider\Foundation\Contracts\Plugin'                         => '\EasingSlider\Plugin\Plugin',
		'\EasingSlider\Foundation\Contracts\Repositories\Repository'        => '\EasingSlider\Plugin\Repositories\Sliders',
		'\EasingSlider\Foundation\Contracts\Shortcodes\Shortcode'           => '\EasingSlider\Plugin\Shortcodes\Slider',
		'\EasingSlider\Foundation\Contracts\TemplateLoaders\TemplateLoader' => '\EasingSlider\Plugin\TemplateLoaders\TemplateLoader',
		'\EasingSlider\Foundation\Contracts\Uninstallation\Uninstaller'     => '\EasingSlider\Plugin\Uninstallation\Uninstaller',
		'\EasingSlider\Foundation\Contracts\Widgets\Widget'                 => '\EasingSlider\Plugin\Widgets\Slider',
		'\EasingSlider\Plugin\Contracts\Options\License'                    => '\EasingSlider\Plugin\Options\License',
		'\EasingSlider\Plugin\Contracts\Options\Settings'                   => '\EasingSlider\Plugin\Options\Settings',
		'\EasingSlider\Plugin\Contracts\Options\Version'                    => '\EasingSlider\Plugin\Options\Version'
	);

	/**
	 * Singletons
	 *
	 * @var array
	 */
	protected $singletons = array(
		'\EasingSlider\Foundation\Contracts\Activation\Activator',
		'\EasingSlider\Foundation\Contracts\Admin\Admin',
		'\EasingSlider\Foundation\Contracts\Assets\Assets',
		'\EasingSlider\Foundation\Contracts\Capabilities\Capabilities',
		'\EasingSlider\Foundation\Contracts\Plugin',
		'\EasingSlider\Foundation\Contracts\Repositories\Repository',
		'\EasingSlider\Foundation\Contracts\Shortcodes\Shortcode',
		'\EasingSlider\Foundation\Contracts\TemplateLoaders\TemplateLoader',
		'\EasingSlider\Foundation\Contracts\Uninstallation\Uninstaller',
		'\EasingSlider\Foundation\Contracts\Widgets\Widget',
		'\EasingSlider\Plugin\Contracts\Options\License',
		'\EasingSlider\Plugin\Contracts\Options\Settings',
		'\EasingSlider\Plugin\Contracts\Options\Version'
	);

	/**
	 * Boots the plugin
	 *
	 * @return void
	 */
	protected function boot()
	{
		$this->setl10n();

		$this->bootComponents();
	}

	/**
	 * Set localization
	 *
	 * @return void
	 */
	protected function setl10n()
	{
		// Load plugin textdomain
		load_plugin_textdomain('easingslider', false, dirname(plugin_basename(EASINGSLIDER_PLUGIN_FILE)) . '/languages/');
	}

	/**
	 * Boot components
	 *
	 * @return void
	 */
	protected function bootComponents()
	{
		$this->activator      = $this->make('\EasingSlider\Foundation\Contracts\Activation\Activator');
		$this->assets         = $this->make('\EasingSlider\Foundation\Contracts\Assets\Assets');
		$this->capabilities   = $this->make('\EasingSlider\Foundation\Contracts\Capabilities\Capabilities');
		$this->license        = $this->make('\EasingSlider\Plugin\Contracts\Options\License');
		$this->settings       = $this->make('\EasingSlider\Plugin\Contracts\Options\Settings');
		$this->shortcode      = $this->make('\EasingSlider\Foundation\Contracts\Shortcodes\Shortcode');
		$this->sliders        = $this->make('\EasingSlider\Foundation\Contracts\Repositories\Repository');
		$this->templateLoader = $this->make('\EasingSlider\Foundation\Contracts\TemplateLoaders\TemplateLoader');
		$this->uninstaller    = $this->make('\EasingSlider\Foundation\Contracts\Uninstallation\Uninstaller');
		$this->version        = $this->make('\EasingSlider\Plugin\Contracts\Options\Version');
		$this->widget         = $this->make('\EasingSlider\Foundation\Contracts\Widgets\Widget');

		if (is_admin()) {
			$this->admin = $this->make('\EasingSlider\Foundation\Contracts\Admin\Admin');
		}
	}
}
