<?php

namespace EasingSlider\Plugin\Admin\Upgrades;

use EasingSlider\Foundation\Admin\Upgrades\Upgrade;
use EasingSlider\Foundation\Contracts\Repositories\Repository;

/**
 * Exit if accessed directly
 */
if ( ! defined('ABSPATH')) {
	exit;
}

class ImportFromLite extends Upgrade
{
	/**
	 * Sliders
	 *
	 * @var \EasingSlider\Foundation\Contracts\Repositories\Repository
	 */
	protected $sliders;

	/**
	 * Force
	 *
	 * @var boolean
	 */
	protected $force = true;

	/**
	 * Constructor
	 *
	 * @param  \EasingSlider\Foundation\Contracts\Repositories\Repository $sliders
	 * @return void
	 */
	public function __construct(Repository $sliders)
	{
		$this->sliders = $sliders;
	}

	/**
	 * Sets a slider attribute, if the old slider value exists.
	 *
	 * @param  array  $slider
	 * @param  string $key
	 * @param  object $liteSlider
	 * @param  string $oldSection
	 * @param  string $oldKey
	 * @return array
	 */
	protected function setSliderAttribute($slider, $key, $liteSlider, $oldSection, $oldValue)
	{
		if (isset($liteSlider->{$oldSection}->{$oldValue})) {
			$slider[$key] = $liteSlider->{$oldSection}->{$oldValue};
		}

		return $slider;
	}

	/**
	 * Gets the "Easing Slider 'Lite'" slider
	 *
	 * @return object
	 */
	protected function getLiteSlider()
	{
		$prefixes = array('easingsliderlite', 'rivasliderlite');

		foreach ($prefixes as $prefix) {
			$liteSlider = get_option("{$prefix}_slideshow", false);

			if ($liteSlider) {
				return $liteSlider;
			}
		}

		return false;
	}

	/**
	 * Creates a new slider for our previous "Easing Slider 'Lite'" slider
	 *
	 * @return void
	 */
	protected function createLiteSlider()
	{
		$data = array();

		// Get the "Easing Slider 'Lite'" slider
		$liteSlider = $this->getLiteSlider();

		if ($liteSlider) {

			// Map linear attributes
			$data['post_title'] = __('Your Slider', 'easingslider');
			$data['type'] = 'media';
			$data['slides'] = $liteSlider->slides;
			$data['image_resizing'] = true;
			$data['auto_height'] = false;
			$data['lazy_loading'] = true;

			// Map dynamic attributes
			$data = $this->setSliderAttribute($data, 'randomize', $liteSlider, 'general', 'randomize');
			$data = $this->setSliderAttribute($data, 'width', $liteSlider, 'dimensions', 'width');
			$data = $this->setSliderAttribute($data, 'height', $liteSlider, 'dimensions', 'height');
			$data = $this->setSliderAttribute($data, 'full_width', $liteSlider, 'dimensions', 'full_width');
			$data = $this->setSliderAttribute($data, 'background_images', $liteSlider, 'dimensions', 'background_images');
			$data = $this->setSliderAttribute($data, 'transition_effect', $liteSlider, 'transitions', 'effect');
			$data = $this->setSliderAttribute($data, 'transition_duration', $liteSlider, 'transitions', 'duration');
			$data = $this->setSliderAttribute($data, 'arrows', $liteSlider, 'navigation', 'arrows');
			$data = $this->setSliderAttribute($data, 'arrows_hover', $liteSlider, 'navigation', 'arrows_hover');
			$data = $this->setSliderAttribute($data, 'arrows_position', $liteSlider, 'navigation', 'arrows_position');
			$data = $this->setSliderAttribute($data, 'pagination', $liteSlider, 'navigation', 'pagination');
			$data = $this->setSliderAttribute($data, 'pagination_hover', $liteSlider, 'navigation', 'pagination_hover');
			$data = $this->setSliderAttribute($data, 'pagination_position', $liteSlider, 'navigation', 'pagination_position');
			$data = $this->setSliderAttribute($data, 'pagination_location', $liteSlider, 'navigation', 'pagination_location');
			$data = $this->setSliderAttribute($data, 'playback_enabled', $liteSlider, 'playback', 'enabled');
			$data = $this->setSliderAttribute($data, 'playback_pause', $liteSlider, 'playback', 'pause');
			
			// Create the slider
			$slider = $this->sliders->create($data);

			// Save the Easing Slider "Lite" slider ID
			update_option('easingslider_lite_slider_id', $slider->ID);

			// Delete the old slider
			delete_option('easingsliderlite_slideshow');
			delete_option('rivasliderlite_slideshow');
		
		}
	}

	/**
	 * Executes the upgrade
	 *
	 * @return void
	 */
	public function upgrade()
	{
		$this->createLiteSlider();
	}
}