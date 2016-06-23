<?php

// Exit if accessed directly
if ( ! defined('WP_UNINSTALL_PLUGIN')) {
	exit;
}

// Don't run installation on PHP version less than v5.3. Will cause errors.
if (version_compare(PHP_VERSION, '5.3.0', '>=')) {

	// Load Easing Slider file
	include_once('easing-slider.php');

	// Get plugin instance
	$plugin = Easing_Slider();

	// Get settings
	$settings = $plugin->settings();

	// Run removal, if enabled by the user.
	if (true === $settings['remove_data']) {
		$uninstaller = $plugin->uninstaller();
		$uninstaller->uninstall();
	}

}
