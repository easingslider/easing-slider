<?php

namespace EasingSlider\Plugin\Admin\Panels;

use EasingSlider\Foundation\Admin\Panels\Panels as BasePanels;

/**
 * Exit if accessed directly
 */
if ( ! defined('ABSPATH')) {
	exit;
}

class Panels extends BasePanels
{
	/**
	 * Boot
	 *
	 * @return void
	 */
	protected function boot()
	{
		$this->panels['addons']        = $this->plugin->make('\EasingSlider\Plugin\Admin\Panels\Addons');
		$this->panels['all_sliders']   = $this->plugin->make('\EasingSlider\Plugin\Admin\Panels\AllSliders');
		$this->panels['create_slider'] = $this->plugin->make('\EasingSlider\Plugin\Admin\Panels\CreateSlider');
		$this->panels['edit_slider']   = $this->plugin->make('\EasingSlider\Plugin\Admin\Panels\EditSlider');
		$this->panels['list_sliders']  = $this->plugin->make('\EasingSlider\Plugin\Admin\Panels\ListSliders');
		$this->panels['settings']      = $this->plugin->make('\EasingSlider\Plugin\Admin\Panels\Settings');
	}

	/**
	 * Gets our custom admin footer text
	 *
	 * @return string
	 */
	public function getFooterText()
	{
		return sprintf(
			__('Please rate <strong>Easing Slider</strong> <a href="%1$s" target="_blank">&#9733;&#9733;&#9733;&#9733;&#9733;</a> on <a href="%1$s" target="_blank">WordPress.org</a> to help us keep this plugin free.  Thank you from the Easing Slider team!', 'easingslider'),
			__('http://wordpress.org/support/view/plugin-reviews/easing-slider?filter=5', 'easingslider')
		);
	}
}