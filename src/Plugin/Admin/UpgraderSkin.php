<?php

namespace EasingSlider\Plugin\Admin;

use WP_Upgrader_Skin;

/**
 * Exit if accessed directly
 */
if ( ! defined('ABSPATH')) {
	exit;
}

class UpgraderSkin extends WP_Upgrader_Skin
{
	/**
	 * Primary class constructor.
	 *
	 * @param  array $args Empty array of args (we will use defaults).
	 * @return void
	 */
	public function __construct($args = array())
	{
		parent::__construct();
	}

	/**
	 * Set the upgrader object and store it as a property in the parent class.
	 *
	 * @param  object $upgrader The upgrader object (passed by reference).
	 * @return void
	 */
	public function set_upgrader(&$upgrader)
	{
		if (is_object($upgrader)) {
			$this->upgrader =& $upgrader;
		}
	}

	/**
	 * Set the upgrader result and store it as a property in the parent class.
	 *
	 * @param  object $result The result of the install process.
	 * @return void
	 */
	public function set_result($result)
	{
		$this->result = $result;
	}

	/**
	 * Empty out the header of its HTML content and only check to see if it has
	 * been performed or not.
	 *
	 * @return void
	 */
	public function header()
	{
		//
	}

	/**
	 * Empty out the footer of its HTML contents.
	 *
	 * @return void
	 */
	public function footer()
	{
		//
	}

	/**
	 * Instead of outputting HTML for errors, json_encode the errors and send them
	 * back to the Ajax script for processing.
	 *
	 * @param  array $errors Array of errors with the install process.
	 * @return void
	 */
	public function error($errors)
	{
		if ( ! empty($errors)) {
			echo json_encode(
				array(
					'errors' => $errors,
					'message' => __('There was an error installing the addon. Please try again.', 'easingslider')
				)
			);
			die;
		}
	}

	/**
	 * Empty out the feedback method to prevent outputting HTML strings as the install
	 * is progressing.
	 *
	 * @param  string $string The feedback string.
	 * @return void
	 */
	public function feedback($string)
	{
		//
	}
}
