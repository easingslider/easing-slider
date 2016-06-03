<?php
/**
 * Plugin Name: Easing Slider
 * Plugin URI: http://easingslider.com/
 * Description: A simple WordPress plugin for creating beautiful sliders.
 * Version: 3.0.1
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
define('EASINGSLIDER_VERSION', '3.0.1');
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
 * Autoload dependencies
 */
require_once('vendor/autoload.php');

/**
 * The main function responsible for returning the one true Easing_Slider instance to functions everywhere.
 *
 * Use this function like you would a global variable, except without needing
 * to declare the global.
 *
 * Example: <?php $easing_slider = Easing_Slider(); ?>
 *
 * @return Easing_Slider
 */
function Easing_Slider()
{
	return \EasingSlider\Plugin\Plugin::instance();
}

/**
 * Let's go!
 */
Easing_Slider();

/**
 * Activator
 *
 * @return void
 */
function easingslider_activate()
{	
	$activator = Easing_Slider()->activator();
	$activator->activate();
}

/**
 * Register activation hook
 */
register_activation_hook(__FILE__, 'easingslider_activate');
