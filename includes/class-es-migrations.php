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
 * @uses   Easing_Slider
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
	 * Updates the plugin to v2.2.
	 *
	 * @param  int $version The current plugin version
	 * @return void
	 */
	public function migrate_to_22( $version ) {

		/**
		 * Do the update if we have no version (just upgraded from Easing Slider "Lite" or "Pro"), or if we have a version number
		 * below v2.2 (possible from very early versions of the plugin).
		 */
		if ( ! $version OR version_compare( $version, '2.2', '<' ) ) {

			// Trigger activation
			Easing_Slider::do_activation();

		}

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