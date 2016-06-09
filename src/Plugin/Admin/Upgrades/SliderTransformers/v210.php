<?php

namespace EasingSlider\Plugin\Admin\Upgrades\SliderTransformers;

/**
 * Exit if accessed directly
 */
if ( ! defined('ABSPATH')) {
	exit;
}

class v210
{
	/**
	 * Gets the slider
	 *
	 * @return object|false
	 */
	protected function getSlider()
	{
		return get_option('easingsliderlite_slideshow', false);
	}

	/**
	 * Gets an image attachment ID, if it exists in the Media Library
	 *
	 * @param  object $slide
	 * @return int|false
	 */
	protected function getAttachmentId($slide)
	{
		global $wpdb;

		if ( ! empty($slide->url)) {

			$attachmentQuery = $wpdb->prepare("SELECT ID FROM {$wpdb->posts} WHERE guid='%s'", $slide->url);
			$attachmentId = $wpdb->get_var($attachmentQuery);

			return $attachmentId;

		}

		return false;
	}

	/**
	 * Transforms an attribute
	 *
	 * @param  object $slider
	 * @param  string $attribute
	 * @return object
	 */
	protected function transformAttribute($slider, $attribute)
	{
		if (isset($slider->{$attribute})) {
			return $slider->{$attribute};
		} else {
			return (object) array();
		}
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
				$attachmentId = $this->getAttachmentId($slide);

				$newSlides[] = (object) array(
					'type'            => 'image',
					'id'              => absint($slide->id),
					'attachment_id'   => ( ! empty($attachmentId)) ? absint($attachmentId) : null,
					'alt'             => ( ! empty($slide->alt)) ? sanitize_text_field($slide->alt) : '',
					'link'            => ( ! empty($slide->link)) ? 'custom' : 'none',
					'linkUrl'         => ( ! empty($slide->link)) ? sanitize_text_field($slide->link) : '',
					'linkTargetBlank' => ( ! empty($slide->linkTarget) && '_blank' == $slide->linkTarget) ? true : false,
					'title'           => ( ! empty($slide->title)) ? sanitize_text_field($slide->title) : '',
					'url'             => ( ! $attachmentId && ! empty($slide->url)) ? sanitize_text_field($slide->url) : null
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
		$slider = $this->getSlider();

		// Transform settings if we have a slider
		if ($slider) {
			$data['slides'] = $this->transformSlides($slider, 'slides');
			$data['general'] = $this->transformAttribute($slider, 'general');
			$data['dimensions'] = $this->transformAttribute($slider, 'dimensions');
			$data['transitions'] = $this->transformAttribute($slider, 'transitions');
			$data['navigation'] = $this->transformAttribute($slider, 'navigation');
			$data['playback'] = $this->transformAttribute($slider, 'playback');
		}

		return $data;
	}
}
