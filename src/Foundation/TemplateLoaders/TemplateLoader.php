<?php

namespace EasingSlider\Foundation\TemplateLoaders;

use EasingSlider\Foundation\Contracts\TemplateLoaders\TemplateLoader as TemplateLoaderContract;

/**
 * Exit if accessed directly
 */
if ( ! defined('ABSPATH')) {
	exit;
}

abstract class TemplateLoader implements TemplateLoaderContract
{
	/**
	 * Returns the path to the Easing Slider templates directory
	 *
	 * @return string
	 */
	public function getTemplatesDir()
	{
		return EASINGSLIDER_TEMPLATES_DIR;
	}

	/**
	 * Returns the URL to the Easing Slider templates directory
	 *
	 * @return string
	 */
	public function getTemplatesUrl()
	{
		return EASINGSLIDER_TEMPLATES_URL;
	}

	/**
	 * Retrieves a template part. Taken from bbPress and modified to suit.
	 *
	 * @param  array   $data   The data to be inserted
	 * @param  string  $slug   The template slug
	 * @param  string  $name   The template name
	 * @param  boolean $load   Whether to load the template or return it
	 * @return string
	 */
	public function getTemplatePart($data, $slug, $name = null, $load = true) {

		// Execute code for this part
		do_action("get_template_part_{$slug}", $slug, $name);

		// Array of possible templates
		$templates = array();

		// Append template name to slug
		if (isset($name)) {
			$templates[] = "{$slug}-{$name}.php";
		}

		// Add to possible templates
		$templates[] = "{$slug}.php";

		// Allow template parts to be filtered
		$templates = apply_filters('easingslider_get_template_part', $templates, $slug, $name);

		// Return the part that is found
		return $this->locateTemplate($data, $templates, $load, false);

	}

	/**
	 * Retrieve the name of the highest priority template file that exists.
	 *
	 * Searches in the STYLESHEETPATH before TEMPLATEPATH so that themes which
	 * inherit from a parent theme can just overload one file. If the template is
	 * not found in either of those, it looks in the theme-compat folder last.
	 *
	 * Taken from bbPress.
	 *
	 * @param  array        $data          Data inserted into the template.
	 * @param  string|array $templateNames Template file(s) to search for, in order.
	 * @param  bool         $load          If true the template file will be loaded if it is found.
	 * @param  bool         $requireOnce   Whether to require_once or require. Default true. Has no effect if $load is false.
	 * @return string
	 */
	public function locateTemplate($data, $templateNames, $load = false, $requireOnce = true)
	{
		// No file found yet
		$located = false;

		// Try to find a template file
		foreach ((array) $templateNames as $templateName) {

			// Continue if template is empty
			if (empty($templateName)) {
				continue;
			}

			// Trim off any slashes from the template name
			$templateName = ltrim($templateName, '/');

			// Try locating this template file by looping through the template paths
			foreach ($this->getThemeTemplatePaths() as $templatePath) {
				if (file_exists($templatePath . $templateName)) {
					$located = $templatePath . $templateName;
					break;
				}
			}

			// Bail if we've located a template
			if ($located) {
				break;
			}

		}

		/**
		 * Load the template if desired.
		 * 
		 * Using our own `easingslider_load_template` function here to accommodate data insertion.
		 */
		if ((true == $load) && ! empty($located)) {
			easingslider_load_template($data, $located, $requireOnce);
		}

		return $located;
	}

	/**
	 * Returns a list of paths to check for template locations. Taken and modified from AffiliateWP.
	 *
	 * @return mixed|void
	 */
	public function getThemeTemplatePaths() {

		$templateDir = $this->getThemeTemplateDirName();

		$filePaths = array(
			1   => trailingslashit(get_stylesheet_directory()) . $templateDir,
			10  => trailingslashit(get_template_directory()) . $templateDir,
			100 => $this->getTemplatesDir()
		);

		$filePaths = apply_filters('easingslider_template_paths', $filePaths);

		// Sort the file paths based on priority
		ksort($filePaths, SORT_NUMERIC);

		return array_map('trailingslashit', $filePaths);

	}

	/**
	 * Returns the URL to the Easing Slider templates directory
	 *
	 * @return string
	 */
	public function getThemeTemplateDirName()
	{
		//
	}
}
