<?php

namespace EasingSlider\Plugin\Options;

use EasingSlider\Foundation\Options\OptionArray;
use EasingSlider\Plugin\Contracts\Options\Settings as SettingsContract;

/**
 * Exit if accessed directly
 */
if ( ! defined('ABSPATH')) {
	exit;
}

class Settings extends OptionArray implements SettingsContract
{
	/**
	 * Name
	 *
	 * @var string
	 */
	protected $name = 'easingslider_settings';

	/**
	 * Gets the default settings
	 *
	 * @return mixed
	 */
	public function getDefaults()
	{
		return apply_filters('easingslider_default_settings', array(
			'load_in_footer' => false,
			'remove_data'    => false
		));
	}
}
