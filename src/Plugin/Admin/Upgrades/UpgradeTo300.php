<?php

namespace EasingSlider\Plugin\Admin\Upgrades;

use WP_Roles;
use WP_Query;
use EasingSlider\Foundation\Admin\Upgrades\Upgrade;
use EasingSlider\Foundation\Contracts\Repositories\Repository;
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
	 * Sliders
	 *
	 * @var \EasingSlider\Foundation\Contracts\Repositories\Repository
	 */
	protected $sliders;

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
	 * Old Slider Meta Keys
	 *
	 * @var array
	 */
	protected $oldSliderMetaKeys = array(
		'slides'      => '_easingslider_slides',
		'general'     => '_easingslider_general',
		'dimensions'  => '_easingslider_dimensions',
		'transitions' => '_easingslider_transitions',
		'navigation'  => '_easingslider_navigation',
		'playback'    => '_easingslider_playback'
	);

	/**
	 * Constructor
	 *
	 * @param  \EasingSlider\Foundation\Contracts\Repositories\Repository $sliders
	 * @param  \EasingSlider\Plugin\Contracts\Options\Settings            $settings
	 * @param  \EasingSlider\Plugin\Contracts\Options\License             $license
	 * @return void
	 */
	public function __construct(Repository $sliders, Settings $settings, License $license)
	{
		$this->sliders = $sliders;
		$this->settings = $settings;
		$this->license = $license;
	}

	/**
	 * Sets a slider data attribute, only if the old slider value exists.
	 *
	 * @param  array  $slider
	 * @param  string $key
	 * @param  object $oldSlider
	 * @param  string $oldSection
	 * @param  string $oldKey
	 * @return array
	 */
	protected function setAttributeIfExists($slider, $key, $oldSlider, $oldSection, $oldValue)
	{
		if (isset($oldSlider->{$oldSection}->{$oldValue})) {
			$slider[$key] = $oldSlider->{$oldSection}->{$oldValue};
		}

		return $slider;
	}

	/**
	 * Transforms the old slider data into our new data structure
	 *
	 * @param  object $oldSlider
	 * @return array
	 */
	protected function transformOldSliderData($oldSlider)
	{
		$data = array();

		// Map linear values
		$data['post_title'] = get_the_title($oldSlider->ID);
		$data['type'] = 'media';
		$data['image_resizing'] = true;
		$data['auto_height'] = false;
		$data['lazy_loading'] = true;

		// Map dynamic values
		$data = $this->setAttributeIfExists($data, 'randomize', $oldSlider, 'general', 'randomize');
		$data = $this->setAttributeIfExists($data, 'width', $oldSlider, 'dimensions', 'width');
		$data = $this->setAttributeIfExists($data, 'height', $oldSlider, 'dimensions', 'height');
		$data = $this->setAttributeIfExists($data, 'full_width', $oldSlider, 'dimensions', 'full_width');
		$data = $this->setAttributeIfExists($data, 'background_images', $oldSlider, 'dimensions', 'background_images');
		$data = $this->setAttributeIfExists($data, 'transition_effect', $oldSlider, 'transitions', 'effect');
		$data = $this->setAttributeIfExists($data, 'transition_duration', $oldSlider, 'transitions', 'duration');
		$data = $this->setAttributeIfExists($data, 'arrows', $oldSlider, 'navigation', 'arrows');
		$data = $this->setAttributeIfExists($data, 'arrows_hover', $oldSlider, 'navigation', 'arrows_hover');
		$data = $this->setAttributeIfExists($data, 'arrows_position', $oldSlider, 'navigation', 'arrows_position');
		$data = $this->setAttributeIfExists($data, 'pagination', $oldSlider, 'navigation', 'pagination');
		$data = $this->setAttributeIfExists($data, 'pagination_hover', $oldSlider, 'navigation', 'pagination_hover');
		$data = $this->setAttributeIfExists($data, 'pagination_position', $oldSlider, 'navigation', 'pagination_position');
		$data = $this->setAttributeIfExists($data, 'pagination_location', $oldSlider, 'navigation', 'pagination_location');
		$data = $this->setAttributeIfExists($data, 'playback_enabled', $oldSlider, 'playback', 'enabled');
		$data = $this->setAttributeIfExists($data, 'playback_pause', $oldSlider, 'playback', 'pause');

		// Transform old slides
		$data['slides'] = $this->transformOldSlides($oldSlider->slides);

		return $data;
	}

	/**
	 * Transforms our old slides into our new slide data structure
	 *
	 * @param  array $oldSlides
	 * @return array
	 */
	protected function transformOldSlides($oldSlides)
	{
		global $wpdb;

		$slides = array();

		// Transform each slide
		foreach ($oldSlides as $oldSlide) {

			// Populate the slide
			$slide = (object) array(
				'type'            => 'image',
				'id'              => absint($oldSlide->id),
				'attachment_id'   => absint($oldSlide->attachment_id),
				'alt'             => $oldSlide->alt,
				'link'            => $oldSlide->link,
				'linkUrl'         => $oldSlide->linkUrl,
				'linkTargetBlank' => (true == $oldSlide->linkTargetBlank) ? true : false,
				'title'           => $oldSlide->title,
				'url'             => null
			);

			// Add an image URL if we aren't using an attachment
			if ( ! $oldSlide->attachment_id) {
				$slide->url = $oldSlide->url;
			}

			// Add the slide
			$slides[] = $slide;

		}

		return $slides;
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
				foreach ($this->oldSliderMetaKeys as $settingsKey => $metaKey) {
					$slider->{$settingsKey} = get_post_meta($id, $metaKey, true);
				}

				// Add to sliders
				$sliders[] = $slider;

			}
		}

		return $sliders;
	}

	/**
	 * Sets an option telling us that this user has upgraded from a previous version to v3.0.0.
	 * This allows us to conditionally display an notice providing information related to the upgrade.
	 *
	 * @return void
	 */
	protected function setupUpgradeInfoNotice()
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
	protected function transferCapabilities()
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
		// Get old sliders
		$oldSliders = $this->getOldSliders();

		/**
		 * We're actually using the same post type as previous versions of Easing Slider,
		 * so instead of creating entirely new sliders, we're upgrading the old ones to our new metadata format.
		 */
		foreach ($oldSliders as $oldSlider) {

			// Transform data
			$data = $this->transformOldSliderData($oldSlider);

			// Update the slider with new data
			$this->sliders->update($oldSlider->ID, $data);

			// Delete old slider meta data
			// foreach ($this->oldSliderMetaKeys as $metaKey) {
			// 	delete_post_meta($oldSlider->ID, $metaKey, true); // Temporarily disable this to allow users to revert back if they have issues.
			// }

		}
	}

	/**
	 * Executes the upgrade
	 *
	 * @return void
	 */
	public function upgrade()
	{
		$this->setupUpgradeInfoNotice();

		$this->migrateLicense();

		$this->transferCapabilities();

		$this->upgradeSettings();

		$this->upgradeSliders();
	}
}