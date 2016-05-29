<?php

namespace EasingSlider\Plugin\Admin\Panels;

use EasingSlider\Foundation\Admin\Panels\Panel;
use EasingSlider\Foundation\Contracts\Repositories\Repository;

/**
 * Exit if accessed directly
 */
if ( ! defined('ABSPATH')) {
	exit;
}

class EditSlider extends Panel
{
	/**
	 * Slider
	 *
	 * @var \EasingSlider\Foundation\Contracts\Models\Model
	 */
	protected $slider;

	/**
	 * Sliders
	 *
	 * @var \EasingSlider\Foundation\Contracts\Repositories\Repository
	 */
	protected $sliders;

	/**
	 * Constructor
	 *
	 * @param  \EasingSlider\Foundation\Contracts\Repositories\Repository $sliders
	 * @return void
	 */
	public function __construct(Repository $sliders)
	{
		$this->sliders = $sliders;

		$this->slider = $this->getSlider();
	}

	/**
	 * Get the slider
	 *
	 * @return \EasingSlider\Foundation\Contracts\Models\Model
	 */
	protected function getSlider()
	{
		return $this->sliders->find($this->getID());
	}

	/**
	 * Gets the possible transitions for a slider
	 *
	 * @return array
	 */
	protected function getTransitions()
	{
		return apply_filters('easingslider_admin_slider_transitions', array(
			'slide' => __('Slide', 'easingslider'),
			'fade'  => __('Fade', 'easingslider')
		));
	}

	/**
	 * Gets the possible content types for a slider
	 *
	 * @return array
	 */
	protected function getTypes()
	{
		return apply_filters('easingslider_admin_slider_types', array(
			'media' => __('Media', 'easingslider')
		));
	}

	/**
	 * Displays the panel
	 *
	 * @return void
	 */
	public function display()
	{
		$this->showView('edit-slider', array(
			'page'        => $this->getPage(),
			'slider'      => $this->slider,
			'types'       => $this->getTypes(),
			'transitions' => $this->getTransitions()
		));
	}
}
