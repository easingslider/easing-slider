<?php

/**
 * Exit if accessed directly
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * This class ensures a smooth transition from "Lite" or "Pro" to our new unified "Easing Slider" customizer extension.
 *
 * Lots of horrible, messy legacy code here that I absolutely hate, but is necessary.
 *
 * @uses   ES_Slider
 * @author Matthew Ruddy
 */
class ES_Customizer_Legacy {

	/**
	 * Gets a Easing Slider "Pro" legacy slider.
	 *
	 * @param  int $legacy_id The legacy slider ID
	 * @return ES_Slider|false
	 */
	public function get_pro_slider( $legacy_id ) {

		// Bail if Easing Slider isn't active
		if ( ! class_exists( 'ES_Slider' ) ) {
			return false;
		}

		// Get our "pivot" array option
		$pivots = get_option( 'easingslider_pro_slider_pivots' );

		// Bail if we have no pivots
		if ( ! $pivots ) {
			return false;
		}

		// Loop through each pivot and return the match, if we have find one.
		foreach ( $pivots as $pivot ) {
			if ( $legacy_id == $pivot->legacy_id ) {
				return ES_Slider::find( $pivot->id );
			}
		}

		return false;

	}

	/**
	 * Upgrades the plugin from Easing Slider "Pro" v2.0 and onwards.
	 *
	 * @return void
	 */
	public function pro_upgrade_from_200() {

		global $wpdb;

		// Bail if this has already been carried out
		if ( get_option( 'easingslider-customizer_upgraded_from_pro' ) ) {
			return;
		}

		// Bail if we don't have a version, telling us that the plugin wasn't installed and there is nothing to upgrade.
		if ( ! get_option( 'easingsliderpro_version' ) ) {
			return;
		}

		// Get the legacy sliders
		$legacy_sliders = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}easingsliderpro" );

		// Add the sliders, if we have any.
		if ( $legacy_sliders ) {
			foreach ( $legacy_sliders as $legacy_slider ) {

				// Get the Easing Slider "Pro" slider
				$slider = $this->get_pro_slider( $legacy_slider->id, $legacy_slider->name );

				// Bail if we have no slider
				if ( ! $slider ) {
					return false;
				}

				// Add customizations if we have any
				if ( ! empty( $legacy_slider->customizations ) ) {

					// Decode the legacy slider's json
					$legacy_slider->customizations = json_decode( $legacy_slider->customizations );

					// Transfer the data
					$slider->customizations = $legacy_slider->customizations;

					// Make some ammendments to our properties (fixing some inconsistencies and mapping new images)
					$slider->customizations->shadow->enabled      = $slider->customizations->shadow->enable;
					$slider->customizations->shadow->image        = str_replace( 'easingsliderpro/images/slideshow_shadow.png',        'easing-slider/images/shadow.png',            $slider->customizations->shadow->image );
					$slider->customizations->arrows->next         = str_replace( 'easingsliderpro/images/slideshow_arrow_next.png',    'easing-slider/images/nav-arrow-next.png',    $slider->customizations->arrows->next );
					$slider->customizations->arrows->prev         = str_replace( 'easingsliderpro/images/slideshow_arrow_prev.png',    'easing-slider/images/nav-arrow-prev.png',    $slider->customizations->arrows->prev );
					$slider->customizations->pagination->active   = str_replace( 'easingsliderpro/images/slideshow_icon_active.png',   'easing-slider/images/nav-icon-active.png',   $slider->customizations->pagination->active );
					$slider->customizations->pagination->inactive = str_replace( 'easingsliderpro/images/slideshow_icon_inactive.png', 'easing-slider/images/nav-icon-inactive.png', $slider->customizations->pagination->inactive );

					// Remove properties that are no longer used
					unset( $slider->customizations->shadow->enable );
				
				}

				// Save the slider
				$slider->save();

			}
		}

		// Flag that this upgrade has been done
		update_option( 'easingslider-customizer_upgraded_from_pro', true );

	}

	/**
	 * Gets the Easing Slider "Lite" legacy slider.
	 *
	 * @return ES_Slider|false
	 */
	public function get_lite_slider() {

		// Bail if Easing Slider isn't active
		if ( ! class_exists( 'ES_Slider' ) ) {
			return false;
		}

		// Get the ID of our "Lite" slider
		$lite_id = get_option( 'easingslider_lite_slider_id' );

		// Get the Easing Slider "Lite" slider
		$lite_slider = ES_Slider::find( $lite_id );

		return $lite_slider;

	}

	/**
	 * Upgrades the plugin from Easing Slider "Lite" v1.0 and onwards.
	 *
	 * @return void
	 */
	public function lite_upgrade_from_100() {

		// Bail if this has already been carried out
		if ( get_option( 'easingslider-customizer_upgraded_from_lite' ) ) {
			return;
		}

		/**
		 * This version of the plugin had no "version" option, so we have to improvise.
		 * 
		 * To do this, we will cycle through the options used to store the images from #1 to #10.
		 * If we find an image, we will assume the plugin settings exist and will continue with the upgrade.
		 */
		$legacy_images = array( 'sImg1', 'sImg2', 'sImg3', 'sImg4', 'sImg5', 'sImg6', 'sImg7', 'sImg8', 'sImg9', 'sImg10' );

		// Loop through each image option, and continue if we find a match.
		foreach ( $legacy_images as $legacy_image ) {

			// Continue if we find an image, telling us that the plugin was being used
			if ( get_option( $legacy_image ) ) {

				// Get the Easing Slider "Lite" slider
				$slider = $this->get_lite_slider();

				// Enable shadow
				$slider->customizations->shadow->enabled = true;

				// Save the slider
				$slider->save();

				// Flag that this upgrade has been done
				update_option( 'easingslider-customizer_upgraded_from_lite', true );
				
				// We've done our update. Time to bail!
				return;
		
			}

		}

	}

	/**
	 * Upgrades the plugin from Easing Slider "Lite" v2.0 and onwards.
	 *
	 * @return void
	 */
	public function lite_upgrade_from_200() {

		global $wpdb;

		// Bail if this has already been carried out
		if ( get_option( 'easingslider-customizer_upgraded_from_lite' ) ) {
			return;
		}

		/**
		 * In my previous attempts to change the plugin name from "Easing Slider" to "Riva Slider",
		 * I made the catastrophic mistake of changing the plugin directory name and options, etc, which
		 * created dozens of issues and prompted the name change reversal.
		 *
		 * As a result, it's possible options may exist under the prefix "rivaslider" or "easingslider".
		 * Let's accommodate for both.
		 */
		$plugin_slug = ( get_option( 'easingsliderlite_version' ) ) ? 'easingsliderlite' : 'rivasliderlite';

		// Get the legacy slider customizations
		$legacy_customizations = get_option( "{$plugin_slug}_customizations" );

		// Bail if we have no customizations
		if ( ! $legacy_customizations ) {
			return;
		}

		// Get the Easing Slider "Lite" slider
		$slider = $this->get_lite_slider();

		// Bail if we have no slider
		if ( ! $slider ) {
			return false;
		}

		// Transfer the data
		$slider->customizations = $legacy_customizations;

		// Make some ammendments to our properties (fixing some inconsistencies and mapping new images)
		$slider->customizations->shadow->enabled      = $slider->customizations->shadow->enable;
		$slider->customizations->shadow->image        = str_replace( 'images/slideshow_shadow.png',        'images/shadow.png',            $slider->customizations->shadow->image );
		$slider->customizations->arrows->next         = str_replace( 'images/slideshow_arrow_next.png',    'images/nav-arrow-next.png',    $slider->customizations->arrows->next );
		$slider->customizations->arrows->prev         = str_replace( 'images/slideshow_arrow_prev.png',    'images/nav-arrow-prev.png',    $slider->customizations->arrows->prev );
		$slider->customizations->pagination->active   = str_replace( 'images/slideshow_icon_active.png',   'images/nav-icon-active.png',   $slider->customizations->pagination->active );
		$slider->customizations->pagination->inactive = str_replace( 'images/slideshow_icon_inactive.png', 'images/nav-icon-inactive.png', $slider->customizations->pagination->inactive );

		// Remove properties that are no longer used
		unset( $slider->customizations->shadow->enable );

		// Save the slider
		$slider->save();

		// Flag that this upgrade has been done
		update_option( 'easingslider-customizer_upgraded_from_lite', true );

	}

	/**
	 * Deletes all the legacy related options.
	 *
	 * @return void
	 */
	public function remove_options() {

		delete_option( 'easingslider-customizer_upgraded_from_lite' );
		delete_option( 'easingslider-customizer_upgraded_from_pro' );

	}

}