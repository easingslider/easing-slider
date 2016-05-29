<?php

namespace EasingSlider\Plugin\Assets;

use EasingSlider\Foundation\Assets\Assets as BaseAssets;
use EasingSlider\Plugin\Contracts\Options\Settings;

/**
 * Exit if accessed directly
 */
if ( ! defined('ABSPATH')) {
	exit;
}

class PublicAssets extends BaseAssets
{
	/**
	 * Settings
	 *
	 * @var \EasingSlider\Plugin\Contracts\Options\Settings
	 */
	protected $settings;

	/**
	 * Constructor
	 *
	 * @param  \EasingSlider\Plugin\Options\Settings $settings
	 * @return void
	 */
	public function __construct(Settings $settings)
	{
		$this->settings = $settings;

		$this->defineHooks();
	}

	/**
	 * Define hooks
	 *
	 * @return void
	 */
	protected function defineHooks()
	{
		add_action('wp_enqueue_scripts', array($this, 'enqueue'));
	}

	/**
	 * Checks if we should load our assets in the footer, based on user settings
	 *
	 * @return boolean
	 */
	protected function loadInFooter()
	{
		if (isset($this->settings['load_in_footer'])) {
			if (true === $this->settings['load_in_footer'] && ! did_action('easingslider_render_slider')) {
				return true;
			}
		}

		return false;
	}

	/**
	 * Enqueues the assets
	 *
	 * @return void
	 */
	public function enqueue()
	{
		/**
		 * If we've set the asset loading to load in footer but we haven't displayed a slider on this page, let's bail.
		 * This prevents the assets from loading when they aren't needed.
		 *
		 * Otherwise, by default, assets will be loaded on every page in the header.
		 */
		if ($this->loadInFooter()) {
			add_action('wp_footer', array($this, 'enqueue'));
			return;
		}

		// Get the suffix
		$suffix = $this->getSuffix();

		// Enqueue our assets
		wp_enqueue_style('easingslider', EASINGSLIDER_ASSETS_URL ."css/public{$suffix}.css", false, EASINGSLIDER_VERSION);
		wp_enqueue_script('easingslider', EASINGSLIDER_ASSETS_URL ."js/public{$suffix}.js", array('jquery'), EASINGSLIDER_VERSION);

		// Trigger action for our extensions
		do_action('easingslider_enqueue_assets', $suffix);
	}
}
