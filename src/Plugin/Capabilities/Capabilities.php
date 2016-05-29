<?php

namespace EasingSlider\Plugin\Capabilities;

use EasingSlider\Foundation\Capabilities\Capabilities as BaseCapabilities;

/**
 * Exit if accessed directly
 */
if ( ! defined('ABSPATH')) {
	exit;
}

class Capabilities extends BaseCapabilities
{
	/**
	 * Gets the capabilities
	 *
	 * @return array
	 */
	protected function getCapabilities()
	{
		return apply_filters('easingslider_capabilities', array(
			'easingslider_publish_sliders',
			'easingslider_edit_sliders',
			'easingslider_duplicate_sliders',
			'easingslider_delete_sliders',
			'easingslider_manage_settings',
			'easingslider_manage_addons'
		));
	}
}
