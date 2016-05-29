<?php

namespace EasingSlider\Plugin\Repositories;

use EasingSlider\Foundation\Repositories\PostType;
use EasingSlider\Plugin\Models\Slider;

/**
 * Exit if accessed directly
 */
if ( ! defined('ABSPATH')) {
	exit;
}

class Sliders extends PostType
{
	/**
	 * Post Type
	 *
	 * @var string
	 */
	protected $postType = 'easingslider';

	/**
	 * Meta Key
	 *
	 * @var string
	 */
	protected $metaKey = '_easingslider';

	/**
	 * Gets the post type label
	 *
	 * @return string
	 */
	protected function getPostTypeLabel()
	{
		return __('Sliders', 'easingslider');
	}

	/**
	 * Gets a new model instance
	 *
	 * @return \EasingSlider\Foundation\Models\ModelContract
	 */
	protected function newModel()
	{
		return new Slider();
	}
}
