<?php

namespace EasingSlider\Plugin\TemplateLoaders;

use EasingSlider\Foundation\TemplateLoaders\TemplateLoader as BaseTemplateLoader;

/**
 * Exit if accessed directly
 */
if ( ! defined('ABSPATH')) {
	exit;
}

class TemplateLoader extends BaseTemplateLoader
{
	/**
	 * Returns the URL to the Easing Slider templates directory
	 *
	 * @return string
	 */
	public function getThemeTemplateDirName()
	{
		return apply_filters('easingslider_theme_template_dir_name', 'easingslider');
	}
}
