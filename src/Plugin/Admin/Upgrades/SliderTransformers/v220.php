<?php

namespace EasingSlider\Plugin\Admin\Upgrades\SliderTransformers;

/**
 * Exit if accessed directly
 */
if ( ! defined('ABSPATH')) {
	exit;
}

class v220
{
	/**
	 * ID
	 *
	 * @var int
	 */
	protected $id;

	/**
	 * Constructor
	 *
	 * @param int $id
	 * @return void
	 */
	public function __construct($id)
	{
		$this->id = $id;
	}

	/**
	 * Gets the slider
	 *
	 * @return object|false
	 */
	protected function getSlider()
	{
		$slider = get_post($this->id);
		$slider->slides = get_post_meta($this->id, '_easingslider_slides', true);
		$slider->general = get_post_meta($this->id, '_easingslider_general', true);
		$slider->dimensions = get_post_meta($this->id, '_easingslider_dimensions', true);
		$slider->transitions = get_post_meta($this->id, '_easingslider_transitions', true);
		$slider->navigation = get_post_meta($this->id, '_easingslider_navigation', true);
		$slider->playback = get_post_meta($this->id, '_easingslider_playback', true);

		return $slider;
	}

	/**
	 * Sets a slider data attribute, only if the old slider value exists.
	 *
	 * @param  array  $data
	 * @param  string $key
	 * @param  object $slider
	 * @param  string $section
	 * @param  string $attribute
	 * @return array
	 */
	protected function transformAttributeIfExists($data, $key, $slider, $section, $attribute)
	{
		if (isset($slider->{$section}->{$attribute})) {
			$data[$key] = $slider->{$section}->{$attribute};
		}

		return $data;
	}

	/**
	 * Transforms our slides
	 *
	 * @param  object $slider
	 * @return array
	 */
	protected function transformSlides($slider)
	{
		$newSlides = array();

		if ( ! empty($slider->slides)) {
			foreach ($slider->slides as $slide) {
				$newSlides[] = (object) array(
					'type'            => 'image',
					'id'              => absint($slide->id),
					'attachment_id'   => ( ! empty($slide->attachment_id)) ? absint($slide->attachment_id) : null,
					'alt'             => ( ! empty($slide->alt)) ? sanitize_text_field($slide->alt) : '',
					'link'            => ( ! empty($slide->link)) ? 'custom' : 'none',
					'linkUrl'         => ( ! empty($slide->link)) ? sanitize_text_field($slide->link) : '',
					'linkTargetBlank' => ( ! empty($slide->linkTargetBlank)) ? true : false,
					'title'           => ( ! empty($slide->title)) ? sanitize_text_field($slide->title) : '',
					'url'             => ( ! $slide->attachment_id && ! empty($slide->url)) ? sanitize_text_field($slide->url) : null
				);
			}
		}

		return $newSlides;
	}

	/**
	 * Transforms the data
	 *
	 * @return array
	 */
	public function transform()
	{
		$data = array();

		// Get the slider
		$slider = $this->getSlider($this->id);

		// Transform settings if we have a slider
		if ($slider) {
			$data['type'] = 'media';
			$data['image_resizing'] = true;
			$data['auto_height'] = false;
			$data['lazy_loading'] = true;
			$data['slides'] = $this->transformSlides($slider);
			$data = $this->transformAttributeIfExists($data, 'randomize', $slider, 'general', 'randomize');
			$data = $this->transformAttributeIfExists($data, 'width', $slider, 'dimensions', 'width');
			$data = $this->transformAttributeIfExists($data, 'height', $slider, 'dimensions', 'height');
			$data = $this->transformAttributeIfExists($data, 'full_width', $slider, 'dimensions', 'full_width');
			$data = $this->transformAttributeIfExists($data, 'background_images', $slider, 'dimensions', 'background_images');
			$data = $this->transformAttributeIfExists($data, 'transition_effect', $slider, 'transitions', 'effect');
			$data = $this->transformAttributeIfExists($data, 'transition_duration', $slider, 'transitions', 'duration');
			$data = $this->transformAttributeIfExists($data, 'arrows', $slider, 'navigation', 'arrows');
			$data = $this->transformAttributeIfExists($data, 'arrows_hover', $slider, 'navigation', 'arrows_hover');
			$data = $this->transformAttributeIfExists($data, 'arrows_position', $slider, 'navigation', 'arrows_position');
			$data = $this->transformAttributeIfExists($data, 'pagination', $slider, 'navigation', 'pagination');
			$data = $this->transformAttributeIfExists($data, 'pagination_hover', $slider, 'navigation', 'pagination_hover');
			$data = $this->transformAttributeIfExists($data, 'pagination_position', $slider, 'navigation', 'pagination_position');
			$data = $this->transformAttributeIfExists($data, 'pagination_location', $slider, 'navigation', 'pagination_location');
			$data = $this->transformAttributeIfExists($data, 'playback_enabled', $slider, 'playback', 'enabled');
			$data = $this->transformAttributeIfExists($data, 'playback_pause', $slider, 'playback', 'pause');
		}

		return $data;
	}
}
