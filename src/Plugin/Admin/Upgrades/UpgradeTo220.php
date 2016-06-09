<?php

namespace EasingSlider\Plugin\Admin\Upgrades;

use EasingSlider\Foundation\Admin\Upgrades\Upgrade;
use EasingSlider\Plugin\Admin\Upgrades\SliderTransformers\v210 as SliderTransformer;

/**
 * Exit if accessed directly
 */
if ( ! defined('ABSPATH')) {
	exit;
}

class UpgradeTo220 extends Upgrade
{
	/**
	 * The version we're upgrading from (or greater)
	 *
	 * @var string
	 */
	protected $upgradeFrom = '2.1';

	/**
	 * The version we're upgrading too
	 *
	 * @var string
	 */
	protected $upgradeTo = '2.2';

	/**
	 * Upgrade Flag
	 *
	 * @var string
	 */
	protected $upgradeFlag = 'easingslider_upgraded_from_lite';

	/**
	 * Lite Slider ID
	 *
	 * @var string
	 */
	protected $liteSliderId = 'easingslider_lite_slider_id';

	/**
	 * Checks if the provided version is eligible for an upgrade
	 * 
	 * We've hijacked this method here as our data structures were vastly different
	 * previously and not compatible with our new upgrade sequence
	 *
	 * @param  string $version
	 * @return boolean
	 */
	public function isEligible($version)
	{
		if ($this->hasAlreadyUpgraded()) {
			return false;
		}

		if ($this->versionIsEligible()) {
			return true;
		}

		return false;
	}

	/**
	 * Checks if our "Easing Slider 'Lite'" version is eligible for an upgrade
	 *
	 * @return boolean
	 */
	protected function versionIsEligible()
	{
		// Hijack version with old version option
		$version = get_option('easingsliderlite_version');

		// Do the comparison and run the upgrade if version is eligible
		if ($version) {
			return parent::isEligible($version);
		}

		return false;
	}

	/**
	 * Creates the new upgraded "Lite" slider
	 *
	 * @return int
	 */
	protected function createNewSlider()
	{
		$transformer = new SliderTransformer();

		// Get the transformed data
		$data = $transformer->transform();

		// Create the post (aka. slider)
		$postId = wp_insert_post(array(
			'post_type'   => 'easingslider',
			'post_title'  => __('Easing Slider "Lite"', 'easingslider'),
			'post_status' => 'publish',
		));

		// Add post metadata
		foreach ($data as $key => $value) {
			add_post_meta($postId, "_easingslider_{$key}", $value);
		}

		return $postId;
	}

	/**
	 * Sets the Lite Slider ID (used to enable our old shortcode)
	 *
	 * @param  int $id
	 * @return void
	 */
	protected function setLiteSliderId($id)
	{
		update_option($this->liteSliderId, $id);
	}

	/**
	 * Marks the upgrade as complete
	 * 
	 * @return void
	 */
	protected function markAsUpgraded()
	{
		update_option($this->upgradeFlag, true);
	}

	/**
	 * Checks if the upgrade has already been performed
	 * 
	 * @return boolean
	 */
	protected function hasAlreadyUpgraded()
	{
		return get_option($this->upgradeFlag, false);
	}

	/**
	 * Upgrades the "Lite" slider
	 * 
	 * @return void
	 */
	public function upgradeSlider()
	{
		// Create the new upgraded slider
		$sliderId = $this->createNewSlider();

		// Set the reference ID so we can continue to use the `[easingsliderlite]` shortcode
		$this->setLiteSliderId($sliderId);

		// Mark the upgrade as complete so it doesn't occur again
		$this->markAsUpgraded();
	}

	/**
	 * Executes the upgrade
	 *
	 * @return void
	 */
	public function upgrade()
	{
		$this->upgradeSlider();
	}
}