<?php

namespace EasingSlider\Plugin\Admin\Upgrades;

use WP_Roles;
use WP_Query;
use EasingSlider\Foundation\Admin\Upgrades\Upgrade;
use EasingSlider\Plugin\Admin\Upgrades\SliderTransformers\v220 as SliderTransformer;

/**
 * Exit if accessed directly
 */
if ( ! defined('ABSPATH')) {
	exit;
}

class UpgradeTo300 extends Upgrade
{
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
	 * Gets the sliders
	 *
	 * @return array 
	 */
	protected function getSliders()
	{
		return get_posts(array(
			'post_type'      => 'easingslider',
			'post_status'    => 'all',
			'posts_per_page' => -1
		));
	}

	/**
	 * Upgrades a slider
	 *
	 * @param  int $id
	 * @return void
	 */
	protected function upgradeSlider($id)
	{
		$transformer = new SliderTransformer($id);

		// Get the transformed data
		$data = $transformer->transform();

		// Update the slider data
		update_post_meta($id, '_easingslider', $data);
	}

	/**
	 * Sets an option telling us that this user has upgraded from a previous version to v3.0.0.
	 * This allows us to conditionally display an notice providing information related to the upgrade.
	 *
	 * @return void
	 */
	public function setupUpgradeInfoNotice()
	{
		add_option('easingslider_upgraded_from_v2', true);
	}

	/**
	 * Upgrades the plugin capaiblities
	 * 
	 * @return void
	 */
	public function transferCapabilities()
	{
		global $wp_roles;

		// Check for roles
		if (class_exists('WP_Roles')) {
			if ( ! isset($wp_roles)) {
				$wp_roles = new WP_Roles();
			}
		}

		// If we have roles, map the capabilities
		if (is_object($wp_roles) && ! empty($wp_roles->roles)) {
			foreach ($wp_roles->roles as $role => $info) {
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
	 * Upgrades the plugin settings
	 *
	 * @return void
	 */
	public function upgradeSettings()
	{
		$settings = (object) get_option('easingslider_settings');

		// Only two settings option available in v3.0.0
		update_option('easingslider_settings', array(
			'load_in_footer' => ( ! empty($settings->load_assets) && 'footer' == $settings->load_assets) ? true : false,
			'remove_data'    => ( ! empty($settings->remove_data)) ? true : false
		));
	}

	/**
	 * Upgrades the plugin sliders
	 * 
	 * @return void
	 */
	public function upgradeSliders()
	{
		$sliders = $this->getSliders();

		/**
		 * We're actually using the same post type as previous versions of Easing Slider,
		 * so instead of creating entirely new sliders, we're upgrading the old ones to our new metadata format.
		 */
		foreach ($sliders as $slider) {
			$this->upgradeSlider($slider->ID);
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

		$this->transferCapabilities();

		$this->upgradeSettings();

		$this->upgradeSliders();
	}
}