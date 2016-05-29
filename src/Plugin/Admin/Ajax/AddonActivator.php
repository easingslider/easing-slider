<?php

namespace EasingSlider\Plugin\Admin\Ajax;

/**
 * Exit if accessed directly
 */
if ( ! defined('ABSPATH')) {
	exit;
}

class AddonActivator
{
	/**
	 * Activates an addon
	 *
	 * @return array|false
	 */
	public function activate()
	{
		// Do security check
		check_ajax_referer('easingslider-activate-addon', 'nonce');

		// Activate addon (just a WordPress plugin)
		if (isset($_POST['plugin'])) {

			// Attempt activation
			$activation = activate_plugin($_POST['plugin']);

			// Handle errors
			if (is_wp_error($activation)) {
				return array('error' => $activation->get_error_message());
			}

			return true;

		}

		return false;
	}
}
