<?php

namespace EasingSlider\Plugin\Admin\Actions;

use EasingSlider\Foundation\Admin\Actions\Processor as BaseProcessor;
use EasingSlider\Plugin\Admin\Actions\SettingsActions;
use EasingSlider\Plugin\Admin\Actions\SliderActions;

/**
 * Exit if accessed directly
 */
if ( ! defined('ABSPATH')) {
	exit;
}

class Processor extends BaseProcessor
{
	/**
	 * Boot
	 *
	 * @return void
	 */
	protected function boot()
	{
		$this->actions['addons']   = $this->plugin->make('\EasingSlider\Plugin\Admin\Actions\Addons');
		$this->actions['settings'] = $this->plugin->make('\EasingSlider\Plugin\Admin\Actions\Settings');
		$this->actions['sliders']  = $this->plugin->make('\EasingSlider\Plugin\Admin\Actions\Sliders');
	}
}
