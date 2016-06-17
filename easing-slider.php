<?php
/**
 * Plugin Name: Easing Slider
 * Plugin URI: http://easingslider.com/
 * Description: A simple WordPress plugin for creating beautiful sliders.
 * Version: 3.0.8
 * Author: Matthew Ruddy
 * Author URI: http://matthewruddy.com
 * License: GPL2
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Domain Path: /languages
 * Text Domain: easingslider
 * 
 * @package Easing Slider
 * @author Matthew Ruddy
 */

/**
 * Exit if accessed directly
 */
if ( ! defined('ABSPATH')) {
	exit;
}

/**
 * Define constants
 */
define('EASINGSLIDER_VERSION', '3.0.8');
define('EASINGSLIDER_NAME', 'Easing Slider');
define('EASINGSLIDER_API_URL', 'http://easingslider.com/');
define('EASINGSLIDER_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('EASINGSLIDER_PLUGIN_URL', plugin_dir_url(__FILE__));
define('EASINGSLIDER_PLUGIN_FILE', __FILE__);
define('EASINGSLIDER_ASSETS_DIR', EASINGSLIDER_PLUGIN_DIR .'assets/');
define('EASINGSLIDER_ASSETS_URL', EASINGSLIDER_PLUGIN_URL .'assets/');
define('EASINGSLIDER_RESOURCES_DIR', EASINGSLIDER_PLUGIN_DIR .'resources/');
define('EASINGSLIDER_RESOURCES_URL', EASINGSLIDER_PLUGIN_URL .'resources/');
define('EASINGSLIDER_TEMPLATES_DIR', EASINGSLIDER_PLUGIN_DIR .'templates/');
define('EASINGSLIDER_TEMPLATES_URL', EASINGSLIDER_PLUGIN_URL .'templates/');

/**
 * Checks requirements to ensure we have the minimum PHP and WordPress versions required,
 * and if we do, boots the plugin.
 *
 * @return void
 */
function easingslider_boot()
{
	global $wp_version;

	// Load deactivation function is it hasn't been loaded already
	if ( ! function_exists('deactivate_plugins')) {
		require_once(ABSPATH . 'wp-admin/includes/plugin.php');
	}

	// Deactivate the plugin if using less than PHP 5.3.
	if (version_compare(PHP_VERSION, '5.3.0', '<')) {
		deactivate_plugins(plugin_basename(EASINGSLIDER_PLUGIN_FILE));
		wp_die(sprintf(__('Sorry, but your version of PHP (%s) is not supported by Easing Slider. PHP 5.3 or greater is required. We recommend contacting your hosting provider to have your PHP version upgraded. Easing Slider has been deactivated. <a href="%s">Return to the Dashboard.</a>', 'easingslider'), PHP_VERSION, admin_url()));
		exit();
	}

	// Deactivate the plugin if the WordPress version is below the minimum required.
	if (version_compare($wp_version, '4.5', '<')) {
		deactivate_plugins(plugin_basename(EASINGSLIDER_PLUGIN_FILE));
		wp_die(sprintf(__('Sorry, but your version of WordPress, <strong>%s</strong>, is not supported by Easing Slider. Easing Slider has been deactivated. <a href="%s">Return to the Dashboard.</a>', 'easingslider'), $wp_version, admin_url()));
		exit();
	}

	// Everything is good to go, let's load our dependencies
	require_once('vendor/autoload.php');

	// Now let's boot the plugin
	Easing_Slider();
}

/**
 * Let's go!
 */
easingslider_boot();
