<?php

namespace EasingSlider\Plugin\Admin\Ajax;

/**
 * Exit if accessed directly
 */
if ( ! defined('ABSPATH')) {
	exit;
}

class AddonDeactivator
{
	/**
	 * Deactivates an addon
	 *
	 * @return array|false
	 */
	public function deactivate()
	{
		// Do security check
		check_ajax_referer('easingslider-deactivate-addon', 'nonce');

		// Deactivate addon (just a WordPress plugin)
		if (isset($_POST['plugin'])) {

			// Attempt deactivation (provides no response so assume it worked)
			deactivate_plugins($_POST['plugin']);

			return true;

		}

		return false;
	}
}
