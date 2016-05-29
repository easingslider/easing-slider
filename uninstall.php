<?php

// Exit if accessed directly
if ( ! defined('WP_UNINSTALL_PLUGIN')) {
	exit;
}

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
