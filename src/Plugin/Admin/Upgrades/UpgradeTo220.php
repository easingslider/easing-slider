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
	 * Possible option prefixes through v2.1.* Easing Slider "Lite" lifespan
	 *
	 * @var array
	 */
	protected $optionPrefixes = array('easingsliderlite', 'rivasliderlite');

	/**
	 * Upgrade Flag
	 *
	 * @var string
	 */
	protected $upgradeFlag = 'easingslider_upgraded_from_lite';

	/**
	 * Reference ID
	 *
	 * @var string
	 */
	protected $referenceId = 'easingslider_lite_slider_id';

	/**
	 * "Lite" Slider Meta Keys
	 *
	 * @var array
	 */
	protected $liteSliderMetaKeys = array(
		'slides'      => '_easingslider_slides',
		'general'     => '_easingslider_general',
		'dimensions'  => '_easingslider_dimensions',
		'transitions' => '_easingslider_transitions',
		'navigation'  => '_easingslider_navigation',
		'playback'    => '_easingslider_playback'
	);

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

		if ($this->liteVersionIsEligible()) {
			return true;
		}

		return false;
	}

	/**
	 * Checks if our "Easing Slider 'Lite'" version is eligible for an upgrade
	 *
	 * @return boolean
	 */
	protected function liteVersionIsEligible()
	{
		// Hijack version with old version option
		$version = $this->getLiteOption('version');

		// Do the comparison and run the upgrade if version is eligible
		if ($version) {
			return parent::isEligible($version);
		}

		return false;
	}

	/**
	 * Delets an Easing Slider "Lite" option
	 *
	 * @param  string $name
	 * @return void
	 */
	protected function deleteLiteOption($name)
	{
		foreach ($this->optionPrefixes as $prefix) {
			delete_option("{$prefix}_{$name}");
		}
	}

	/**
	 * Gets an Easing Slider "Lite" option
	 *
	 * @param  string $name
	 * @return mixed|false
	 */
	protected function getLiteOption($name)
	{
		foreach ($this->optionPrefixes as $prefix) {
			$value = get_option("{$prefix}_{$name}", false);

			if ($value) {
				return $value;
			}
		}

		return false;
	}

	/**
	 * Gets the "Easing Slider 'Lite'" slider
	 * 
	 * @return object|false
	 */
	protected function getLiteSlider()
	{
		return $this->getLiteOption('slideshow');
	}

	/**
	 * Creates the upgraded "Lite" slider
	 *
	 * @param  object $liteSlider
	 * @return int
	 */
	protected function createUpgradedSlider($liteSlider)
	{
		// Create the post
		$postId = wp_insert_post(array(
			'post_type' => 'easingslider',
			'post_title' => __('Your Slider', 'easingslider'),
			'post_status' => 'publish',
		));

		// Add post meta
		add_post_meta($postId, '_easingslider_slides', $this->transformLiteSlides($liteSlider->slides));
		add_post_meta($postId, '_easingslider_general', $liteSlider->general);
		add_post_meta($postId, '_easingslider_dimensions', $liteSlider->dimensions);
		add_post_meta($postId, '_easingslider_transitions', $liteSlider->transitions);
		add_post_meta($postId, '_easingslider_navigation', $liteSlider->navigation);
		add_post_meta($postId, '_easingslider_playback', $liteSlider->playback);

		return $postId;
	}

	/**
	 * Transforms our "Lite" slides into our new slide data structure
	 *
	 * @param  array $liteSlides
	 * @return array
	 */
	protected function transformLiteSlides($liteSlides)
	{
		global $wpdb;

		$slides = array();

		// Transform each slide
		foreach ($liteSlides as $liteSlide) {

			// Query the guid
			$attachmentQuery = $wpdb->prepare("SELECT ID FROM {$wpdb->posts} WHERE guid='%s'", $liteSlide->url);

			// Attempt to get the attachment of this image
			$attachmentId = $wpdb->get_var($attachmentQuery);

			// Populate the slide
			$slide = (object) array(
				'type'            => 'image',
				'id'              => absint($liteSlide->id),
				'attachment_id'   => absint($attachmentId),
				'alt'             => sanitize_text_field($liteSlide->alt),
				'link'            => ($liteSlide->link) ? 'custom' : 'none',
				'linkUrl'         => sanitize_text_field($liteSlide->link),
				'linkTargetBlank' => ('_blank' == $liteSlide->linkTarget) ? true : false,
				'title'           => sanitize_text_field($liteSlide->title),
				'url'             => null
			);

			// Add an image URL if we aren't using an attachment
			if ( ! $attachmentId) {
				$slide->url = sanitize_text_field($liteSlide->url);
			}

			// Add the slide
			$slides[] = $slide;

		}

		return $slides;
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
	 * Sets the reference ID
	 *
	 * @param int $id
	 * @return void
	 */
	protected function setReferenceId($id)
	{
		update_option($this->referenceId, $id);
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
	 * Upgrades the "Lite" slider
	 * 
	 * @return void
	 */
	protected function upgradeSlider()
	{
		$liteSlider = $this->getLiteSlider();

		if ($liteSlider) {
			$sliderId = $this->createUpgradedSlider($liteSlider);

			$this->setReferenceId($sliderId);

			$this->markAsUpgraded();
		}
	}

	/**
	 * Deletes all "Easing Slider 'Lite'" options that are no longer used
	 *
	 * @return void
	 */
	protected function cleanupOptions()
	{
		$this->deleteLiteOption('customizations');
		$this->deleteLiteOption('disable_welcome_panel');
		$this->deleteLiteOption('major_upgrade');
		$this->deleteLiteOption('settings');
		$this->deleteLiteOption('slideshow');
		$this->deleteLiteOption('version');
	}

	/**
	 * Executes the upgrade
	 *
	 * @return void
	 */
	public function upgrade()
	{
		$this->upgradeSlider();

		// $this->cleanupOptions(); // Temporarily disabling this to allow users time to revert.
	}
}