<?php

namespace EasingSlider\Plugin\Admin\Upgrades;

use WP_Roles;
use WP_Query;
use EasingSlider\Foundation\Admin\Upgrades\Upgrade;
use EasingSlider\Plugin\Contracts\Options\License;
use EasingSlider\Plugin\Contracts\Options\Settings;

/**
 * Exit if accessed directly
 */
if ( ! defined('ABSPATH')) {
	exit;
}

class UpgradeTo300 extends Upgrade
{
	/**
	 * Settings
	 *
	 * @var \EasingSlider\Plugin\Contracts\Options\Settings
	 */
	protected $settings;

	/**
	 * License
	 *
	 * @var \EasingSlider\Plugin\Contracts\Options\License
	 */
	protected $license;

	/**
	 * The version we're upgrading from (or greater)
	 *
	 * @var string
	 */
	protected $upgradeFrom = '2.2';

	/**
	 * The version we're upgrading too
	 *
	 * @var string
	 */
	protected $upgradeTo = '3.0.0';

	/**
	 * Constructor
	 *
	 * @param  \EasingSlider\Plugin\Contracts\Options\Settings $settings
	 * @param  \EasingSlider\Plugin\Contracts\Options\License  $license
	 * @return void
	 */
	public function __construct(Settings $settings, License $license)
	{
		$this->settings = $settings;
		$this->license = $license;
	}

	/**
	 * Sets an option telling us that this user has upgraded from a previous version to v3.0.0.
	 * This allows us to conditionally display an notice providing information related to the upgrade.
	 *
	 * @return void
	 */
	protected function setupUpgradeNotice()
	{
		add_option('easingslider_upgraded_from_v2', true);
	}

	/**
	 * Migrates the license key
	 *
	 * @return void
	 */
	protected function migrateLicense()
	{
		// Set the license key
		$this->license['key'] = get_option('easingslider_license_key');

		// Save it
		$this->license->save();

		// Delete old license key
		delete_option('easingslider_license_key');
	}

	/**
	 * Upgrades the plugin settings
	 *
	 * @return void
	 */
	protected function upgradeSettings()
	{
		// Get new settings array of defaults
		$settings = $this->settings->getDefaults();

		// Get the old settings
		$oldSettings = (array) get_option('easingslider_settings');

		// Convert "Load in Footer" option
		if (isset($oldSettings['load_assets']) && 'footer' == $oldSettings['load_assets']) {
			$settings['load_in_footer'] = true;
		}

		// Convert "Remove Data" option
		$settings['remove_data'] = $oldSettings['remove_data'];

		// Set the new settings
		$this->settings->setValue($settings);

		// Update the settings
		$this->settings->save();
	}

	/**
	 * Upgrades the plugin capaiblities
	 * 
	 * @return void
	 */
	protected function upgradeCapabilities()
	{
		global $wp_roles;

		// Check for roles
		if (class_exists('WP_Roles')) {
			if ( ! isset($wp_roles)) {
				$wp_roles = new WP_Roles();
			}
		}

		/**
		 * In this version (v2.3), we've also unprefixed the plugin capabilities.
		 * Let's reflect this.
		 */
		if (is_object($wp_roles) && ! empty($wp_roles->roles)) {
			foreach ($wp_roles->roles as $role => $info) {

				// Get the user role
				$userRole = get_role($role);

				// Map new capabilities
				if ($userRole->has_cap('easingslider_add_slider')) {
					$userRole->add_cap('easingslider_publish_sliders');
				}
				if ($userRole->has_cap('easingslider_discover_extensions')) {
					$userRole->add_cap('easingslider_manage_addons');
				}

				// Remove/cleanup all previous capabilities
				$userRole->remove_cap('easingslider_add_slider');
				$userRole->remove_cap('easingslider_delete_slider');
				$userRole->remove_cap('easingslider_duplicate_slider');
				$userRole->remove_cap('easingslider_edit_slider');
				$userRole->remove_cap('easingslider_edit_settings');
				$userRole->remove_cap('easingslider_discover_extensions');
				$userRole->remove_cap('easingslider_manage_extensions');

			}
		}
	}

	/**
	 * Upgrades the plugin sliders
	 * 
	 * @return void
	 */
	protected function upgradeSliders()
	{
		// Get sliders
		$sliders = Easing_Slider()->sliders();

		// Get old sliders
		$oldSliders = $this->getOldSliders();

		/**
		 * We're actually using the same post type as previous versions of Easing Slider,
		 * so instead of creating entirely new sliders, we're upgrading the old ones to our new metadata format.
		 */
		foreach ($oldSliders as $oldSlider) {

			// New data
			$data = array();

			// Map linear values
			$data['post_title'] = get_the_title($oldSlider->ID);
			$data['type'] = 'media';
			$data['slides'] = $oldSlider->slides;
			$data['responsive'] = true;
			$data['image_resizing'] = true;
			$data['auto_height'] = false;
			$data['lazy_loading'] = true;

			// Map dynamic values
			$data = $this->setSliderAttribute($data, 'randomize', $oldSlider, 'general', 'randomize');
			$data = $this->setSliderAttribute($data, 'width', $oldSlider, 'dimensions', 'width');
			$data = $this->setSliderAttribute($data, 'height', $oldSlider, 'dimensions', 'height');
			$data = $this->setSliderAttribute($data, 'full_width', $oldSlider, 'dimensions', 'full_width');
			$data = $this->setSliderAttribute($data, 'background_images', $oldSlider, 'dimensions', 'background_images');
			$data = $this->setSliderAttribute($data, 'transition_effect', $oldSlider, 'transitions', 'effect');
			$data = $this->setSliderAttribute($data, 'transition_duration', $oldSlider, 'transitions', 'duration');
			$data = $this->setSliderAttribute($data, 'arrows', $oldSlider, 'navigation', 'arrows');
			$data = $this->setSliderAttribute($data, 'arrows_hover', $oldSlider, 'navigation', 'arrows_hover');
			$data = $this->setSliderAttribute($data, 'arrows_position', $oldSlider, 'navigation', 'arrows_position');
			$data = $this->setSliderAttribute($data, 'pagination', $oldSlider, 'navigation', 'pagination');
			$data = $this->setSliderAttribute($data, 'pagination_hover', $oldSlider, 'navigation', 'pagination_hover');
			$data = $this->setSliderAttribute($data, 'pagination_position', $oldSlider, 'navigation', 'pagination_position');
			$data = $this->setSliderAttribute($data, 'pagination_location', $oldSlider, 'navigation', 'pagination_location');
			$data = $this->setSliderAttribute($data, 'playback_enabled', $oldSlider, 'playback', 'enabled');
			$data = $this->setSliderAttribute($data, 'playback_pause', $oldSlider, 'playback', 'pause');

			// Update the slider with new data
			$sliders->update($oldSlider->ID, $data);

			// Delete old slider meta
			delete_post_meta($oldSlider->ID, '_easingslider_slides', true);
			delete_post_meta($oldSlider->ID, '_easingslider_general', true);
			delete_post_meta($oldSlider->ID, '_easingslider_dimensions', true);
			delete_post_meta($oldSlider->ID, '_easingslider_transitions', true);
			delete_post_meta($oldSlider->ID, '_easingslider_navigation', true);
			delete_post_meta($oldSlider->ID, '_easingslider_playback', true);

		}
	}

	/**
	 * Sets a slider attribute, if the old slider value exists.
	 *
	 * @param  array  $slider
	 * @param  string $key
	 * @param  object $oldSlider
	 * @param  string $oldSection
	 * @param  string $oldKey
	 * @return array
	 */
	protected function setSliderAttribute($slider, $key, $oldSlider, $oldSection, $oldValue)
	{
		if (isset($oldSlider->{$oldSection}->{$oldValue})) {
			$slider[$key] = $oldSlider->{$oldSection}->{$oldValue};
		}

		return $slider;
	}

	/**
	 * Gets the old sliders
	 * 
	 * @return array
	 */
	protected function getOldSliders()
	{
		// Get sliders
		$sliders = array();

		// Query posts
		$wpQuery = new WP_Query(array('post_type' => 'easingslider'));

		// Loop through each post
		if ($wpQuery->have_posts()) {
			while($wpQuery->have_posts()) {

				$wpQuery->the_post();

				// Get post ID
				$id = get_the_ID();

				// Get slider
				$slider = (object) array(
					'ID' => $id
				);

				// Add metadata
				$slider->slides = get_post_meta($id, '_easingslider_slides', true);
				$slider->general = get_post_meta($id, '_easingslider_general', true);
				$slider->dimensions = get_post_meta($id, '_easingslider_dimensions', true);
				$slider->transitions = get_post_meta($id, '_easingslider_transitions', true);
				$slider->navigation = get_post_meta($id, '_easingslider_navigation', true);
				$slider->playback = get_post_meta($id, '_easingslider_playback', true);

				// Add to sliders
				$sliders[] = $slider;

			}
		}

		return $sliders;
	}

	/**
	 * Executes the upgrade
	 *
	 * @return void
	 */
	public function upgrade()
	{
		$this->setupUpgradeNotice();

		$this->migrateLicense();

		$this->upgradeCapabilities();

		$this->upgradeSettings();

		$this->upgradeSliders();
	}
}