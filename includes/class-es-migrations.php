<?php

/**
 * Exit if accessed directly
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Defines the version migrations.
 * 
 * All of our update procedures can be found here.
 *
 * @author Matthew Ruddy
 */
class ES_Migrations {

	/**
	 * Do migrations
	 *
	 * @return void
	 */
	public function do_migrations() {

		// Get the plugin version
		$version = get_option( 'easingslider_version' );

		// Hook additional functionality
		do_action( 'easingslider_update_plugin', $version );

	}

	/**
	 * Updates the plugin version
	 *
	 * @return void
	 */
	public function update_version() {

		update_option( 'easingslider_version', Easing_Slider::$version );

	}

}