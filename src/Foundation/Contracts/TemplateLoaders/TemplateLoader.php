<?php

namespace EasingSlider\Foundation\Contracts\TemplateLoaders;

/**
 * Exit if accessed directly
 */
if ( ! defined('ABSPATH')) {
	exit;
}

interface TemplateLoader
{
	/**
	 * Retrieves a template part. Taken from bbPress and modified to suit.
	 *
	 * @param  array   $data   The data to be inserted
	 * @param  string  $slug   The template slug
	 * @param  string  $name   The template name
	 * @param  boolean $load   Whether to load the template or return it
	 * @return string
	 */
	public function getTemplatePart($data, $slug, $name = null, $load = true);
}
