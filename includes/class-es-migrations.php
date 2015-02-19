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
	 * Updates the plugin to v2.2.1.
	 *
	 * @param  int $version The current plugin version
	 * @return void
	 */
	public function migrate_to_221( $version ) {

		// Continue if upgrading from a lower version
		if ( version_compare( $version, '2.2.1', '<' ) ) {

			// Get the settings
			$settings = get_option( 'easingslider_settings' );

			// Bail if we have no settings or the setting we need
			if ( ! $settings OR ! isset( $settings->image_resizing ) ) {
				return;
			}

			// Get all sliders
			$sliders = ES_Slider::all();

			// Migrate the "Image Resizing" option
			foreach ( $sliders as $slider ) {
				$slider->dimensions->image_resizing = $settings->image_resizing;
				$slider->save();
			}

			// Unset the settings option
			unset( $settings->image_resizing );

			// Update the settings
			update_option( 'easingslider_settings', $settings );

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