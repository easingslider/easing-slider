<?php

/**
 * Exit if accessed directly
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Simple helper legacy functions for displaying sliders
 *
 * @param  int $id The legacy slider ID
 * @return void
 */
if ( ! function_exists( 'easing_slider' ) ) {
	function easing_slider() {
		echo do_shortcode( "[easingsliderlite]" );
	}
}
if ( ! function_exists( 'easingsliderlite' ) ) {
	function easingsliderlite() {
		echo do_shortcode( "[easingsliderlite]" );
	}
}
if ( ! function_exists( 'easingsliderpro' ) ) {
	function easingsliderpro( $id ) {
		echo do_shortcode( "[easingsliderpro id=\"{$id}\"]" );
	}
}
if ( ! function_exists( 'riva_slider_pro' ) ) {
	function riva_slider_pro( $id ) {
		echo do_shortcode( "[rivasliderpro id=\"{$id}\"]" );
	}
}

/**
 * This class ensures a smooth transition from "Lite" or "Pro" to our new unified "Easing Slider" plugin.
 *
 * Lots of horrible, messy legacy code here that I absolutely hate, but is necessary.
 *
 * @uses   ES_Slider
 * @author Matthew Ruddy
 */
class ES_Legacy {

	/**
	 * Gets a Easing Slider "Pro" legacy slider.
	 *
	 * @param  int $legacy_id The legacy slider ID
	 * @return ES_Slider|false
	 */
	public function get_pro_slider( $legacy_id ) {

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
	 * Creates a Easing Slider "Pro" legacy slider.
	 *
	 * @param  int    $legacy_id    The legacy slider ID
	 * @param  string $legacy_title The legacy slider title
	 * @return ES_Slider
	 */
	public function create_pro_slider( $legacy_id, $legacy_title = null ) {

		// If we don't have a pivot option already, create one. Does nothing otherwise.
		add_option( 'easingslider_pro_slider_pivots', array() );

		// Get our "pivot" array option
		$pivots = get_option( 'easingslider_pro_slider_pivots' );

		// Create our Easing Slider "Pro" legacy slider
		$pro_slider = ES_Slider::create();
		$pro_slider->post_title = easingslider_validate_data( $legacy_title );
		$pro_slider->save();

		// Populate the pivot
		$pivot = (object) array( 'legacy_id' => (int) $legacy_id, 'id' => (int) $pro_slider->ID );

		// Save to our pivots
		update_option( 'easingslider_pro_slider_pivots', array_merge( $pivots, array( $pivot ) ) );

		return $pro_slider;

	}

	/**
	 * Deletes the Easing Slider "Pro" related options when the slider itself is deleted.
	 *
	 * @param  int $id The slider ID
	 * @return void
	 */
	public function delete_pro_slider( $id ) {

		// Get the "pivot" array option
		$pivots = get_option( 'easingslider_pro_slider_pivots' );

		// Bail if we have no pivots
		if ( ! $pivots ) {
			return;
		}

		// Loop through and find a matching pivot
		foreach ( $pivots as $key => $pivot ) {
			if ( $id == $pivot->id ) {
				unset( $pivots[ $key ] );

				update_option( 'easingslider_pro_slider_pivots', $pivots );
			}
		}

		// If pivots are empty, delete the option altogether.
		if ( count( $pivots ) == 0 ) {
			delete_option( 'easingslider_pro_slider_pivots' );
		}

	}

	/**
	 * Renders our Easing Slider "Pro" shortcode
	 *
	 * @param  int $legacy_id The legacy slider ID
	 * @return string
	 */
	public function render_pro_slider( $legacy_id ) {

		// Get our "Pro" slider
		$pro_slider = $this->get_pro_slider( $legacy_id );

		// Bail if we don't have a slider
		if ( ! $pro_slider ) {
			return;
		}

		return $pro_slider->render();

	}

	/**
	 * Handle the functionality for our Easing Slider "Pro" shortcode
	 *
	 * @param  array $atts The shortcode attributes
	 * @return string
	 */
	public function do_pro_shortcode( $atts ) {

		// Extract shortcode attributes
		extract(
			shortcode_atts(
				array( 'id' => false ),
				$atts
			)
		);

		// Display error message if no ID has been entered
		if ( ! $id ) {
			return __( 'Looks like you\'ve forgotten to add a slideshow ID to this shortcode. Oh dear!', 'easingslider' );
		}

		return $this->render_pro_slider( $id );

	}

	/**
	 * Upgrades the plugin from Easing Slider "Pro" v1.0 and onwards (was known as Riva Slider "Pro" at this time).
	 *
	 * @return void
	 */
	public function pro_upgrade_from_100() {

		global $wpdb;

		// Bail if this has already been carried out
		if ( get_option( 'easingslider_upgraded_from_pro' ) ) {
			return;
		}

		// Bail if we don't have a version, telling us that the plugin wasn't installed and there is nothing to update.
		if ( ! get_option( 'riva_slider_pro_version' ) ) {
			return;
		}

		// Get the legacy sliders
		$legacy_sliders = get_option( 'riva_slider_pro_slideshows' );

		// Add the sliders, if we have any.
		if ( $legacy_sliders ) {
			foreach ( $legacy_sliders as $legacy_slider ) {

				// Create the Easing Slider "Pro" slider
				$slider = $this->create_pro_slider( $legacy_slider['id'], $legacy_slider['name'] );

				// Transfer the data
				$slider->general->randomize              = ( ! empty( $legacy_slider['random_order'] ) ) ? true : false;
				$slider->dimensions->width               = easingslider_validate_data( $legacy_slider['width'] );
				$slider->dimensions->height              = easingslider_validate_data( $legacy_slider['height'] );
				$slider->transitions->duration           = easingslider_validate_data( $legacy_slider['trans_time'] );
				$slider->navigation->arrows              = ( $legacy_slider['direction_nav'] == 'enable' ) ? true : false;
				$slider->navigation->arrows_hover        = easingslider_validate_data( $legacy_slider['direction_nav_hover'] );
				$slider->navigation->arrows_position     = easingslider_validate_data( $legacy_slider['direction_nav_position'] );
				$slider->navigation->pagination          = ( $legacy_slider['control_nav'] == 'enable' ) ? true : false;
				$slider->navigation->pagination_location = easingslider_validate_data( str_replace( '_', '-', $old_slideshow['control_nav_pos'] ) );

				// Add the slides
				foreach ( $legacy_slider['images'] as $key => $legacy_slide ) {

					// Query the guid
					$attachment_query = $wpdb->prepare( "SELECT ID FROM {$wpdb->posts} WHERE guid='%s'", $legacy_slide['image-url'] );

					// Attempt to get the attachment of this image
					$attachment_id = $wpdb->get_var( $attachment_query );

					// Populate the slide
					$slide = (object) array(
						'id'              => easingslider_validate_data( ( $key + 1 ) ),
						'attachment_id'   => easingslider_validate_data( $attachment_id ),
						'type'            => 'image',
						'alt'             => easingslider_validate_data( $legacy_slide['image-alt'] ),
						'aspectRatio'     => null,
						'link'            => 'custom',
						'linkUrl'         => ( 'webpage' == $legacy_slide['image-link'] ) ? $legacy_slide['webpage-url'] : $legacy_slide['video-url'],
						'linkTargetBlank' => false,
						'title'           => easingslider_validate_data( $legacy_slide['image-title'] )
					);

					// Add an image URL if we aren't using an attachment
					if ( ! $attachment_id ) {
						$slide->url = easingslider_validate_data( $legacy_slide['image-url'] );
					}

					// Validate & add the slide
					$slider->slides[] = easingslider_validate_data( $slide );

				}

				// Save the slider
				$slider->save();

			}
		}

		// Flag that this update has been done
		update_option( 'easingslider_upgraded_from_pro', true );

	}

	/**
	 * Upgrades the plugin from Easing Slider "Pro" v2.0 and onwards.
	 *
	 * @return void
	 */
	public function pro_upgrade_from_200() {

		global $wpdb;

		// Bail if this has already been carried out
		if ( get_option( 'easingslider_upgraded_from_pro' ) ) {
			return;
		}

		// Bail if we don't have a version, telling us that the plugin wasn't installed and there is nothing to update.
		if ( ! get_option( 'easingsliderpro_version' ) ) {
			return;
		}

		// Get the legacy sliders
		$legacy_sliders = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}easingsliderpro" );

		// Add the sliders, if we have any.
		if ( $legacy_sliders ) {
			foreach ( $legacy_sliders as $legacy_slider ) {

				// Create the Easing Slider "Pro" slider
				$slider = $this->create_pro_slider( $legacy_slider->id, $legacy_slider->name );

				// Decode the legacy slider's json
				$legacy_slider->general     = json_decode( $legacy_slider->general );
				$legacy_slider->dimensions  = json_decode( $legacy_slider->dimensions );
				$legacy_slider->transitions = json_decode( $legacy_slider->transitions );
				$legacy_slider->navigation  = json_decode( $legacy_slider->navigation );
				$legacy_slider->playback    = json_decode( $legacy_slider->playback );
				$legacy_slider->slides      = json_decode( $legacy_slider->slides );

				// Transfer the data
				$slider->general->randomize              = easingslider_validate_data( $legacy_slider->general->randomize );
				$slider->dimensions->width               = easingslider_validate_data( $legacy_slider->dimensions->width );
				$slider->dimensions->height              = easingslider_validate_data( $legacy_slider->dimensions->height );
				$slider->dimensions->responsive          = easingslider_validate_data( $legacy_slider->dimensions->responsive );
				$slider->transitions->effect             = easingslider_validate_data( $legacy_slider->transitions->effect );
				$slider->transitions->duration           = easingslider_validate_data( $legacy_slider->transitions->duration );
				$slider->navigation->arrows              = easingslider_validate_data( $legacy_slider->navigation->arrows );
				$slider->navigation->arrows_hover        = easingslider_validate_data( $legacy_slider->navigation->arrows_hover );
				$slider->navigation->arrows_position     = easingslider_validate_data( $legacy_slider->navigation->arrows_position );
				$slider->navigation->pagination          = easingslider_validate_data( $legacy_slider->navigation->pagination );
				$slider->navigation->pagination_hover    = easingslider_validate_data( $legacy_slider->navigation->pagination_hover );
				$slider->navigation->pagination_position = easingslider_validate_data( $legacy_slider->navigation->pagination_position );
				$slider->navigation->pagination_location = easingslider_validate_data( $legacy_slider->navigation->pagination_location );
				$slider->playback->enabled               = easingslider_validate_data( $legacy_slider->playback->enabled );
				$slider->playback->pause                 = easingslider_validate_data( $legacy_slider->playback->pause );

				// Add the slides
				foreach ( $legacy_slider->slides as $legacy_slide ) {

					// Query the guid
					$attachment_query = $wpdb->prepare( "SELECT ID FROM {$wpdb->posts} WHERE guid='%s'", $legacy_slide->url );

					// Attempt to get the attachment of this image
					$attachment_id = $wpdb->get_var( $attachment_query );

					// Populate the slide
					$slide = (object) array(
						'id'              => easingslider_validate_data( $legacy_slide->id ),
						'attachment_id'   => easingslider_validate_data( $attachment_id ),
						'type'            => 'image',
						'alt'             => easingslider_validate_data( $legacy_slide->alt ),
						'aspectRatio'     => null,
						'link'            => ( $legacy_slide->link ) ? 'custom' : 'none',
						'linkUrl'         => easingslider_validate_data( $legacy_slide->link ),
						'linkTargetBlank' => ( '_blank' == $legacy_slide->linkTarget ) ? '_blank': false,
						'title'           => easingslider_validate_data( $legacy_slide->title )
					);

					// Add an image URL if we aren't using an attachment
					if ( ! $attachment_id ) {
						$slide->url = easingslider_validate_data( $legacy_slide->url );
					}

					// Validate & add the slide
					$slider->slides[] = easingslider_validate_data( $slide );

				}

				// Save the slider
				$slider->save();

			}
		}

		// Get the settings
		$settings = get_option( 'easingslider_settings' );

		// Get the legacy settings
		$legacy_settings = get_option( "easingsliderpro_settings" );

		// Transfer the settings
		$settings->load_assets    = $legacy_settings['load_scripts'];
		$settings->image_resizing = $legacy_settings['resizing'];

		// Save the settings
		update_option( 'easingslider_settings', $settings );

		// Flag that this update has been done
		update_option( 'easingslider_upgraded_from_pro', true );

	}

	/**
	 * Gets the Easing Slider "Lite" legacy slider.
	 *
	 * @return ES_Slider|false
	 */
	public function get_lite_slider() {

		// Get the ID of our "Lite" slider
		$lite_id = get_option( 'easingslider_lite_slider_id' );

		// Get the Easing Slider "Lite" slider
		$lite_slider = ES_Slider::find( $lite_id );

		return $lite_slider;

	}

	/**
	 * Creates the Easing Slider "Lite" legacy slider.
	 *
	 * @return ES_Slider
	 */
	public function create_lite_slider() {

		// Create the slider
		$lite_slider = ES_Slider::create();
		$lite_slider->post_title = 'Easing Slider "Lite"';
		$lite_slider->save();

		// Save the Easing Slider "Lite" slider ID
		update_option( 'easingslider_lite_slider_id', $lite_slider->ID );
		
		return $lite_slider;

	}

	/**
	 * Deletes the Easing Slider "Lite" related options when the slider itself is deleted.
	 *
	 * @param  int $id The slider ID
	 * @return void
	 */
	public function delete_lite_slider( $id ) {

		// If we've deleted the Easing Slider "Lite" slider, delete its options.
		if ( $id == get_option( 'easingslider_lite_slider_id' ) ) {
			delete_option( 'easingslider_lite_slider_id' );
		}

	}

	/**
	 * Renders our Easing Slider "Lite" shortcode
	 *
	 * @return string
	 */
	public function render_lite_slider() {

		// Get our "Lite" slider
		$lite_slider = $this->get_lite_slider();

		// Bail if we don't have a slider
		if ( ! $lite_slider ) {
			return;
		}

		return $lite_slider->render();

	}

	/**
	 * Handle the functionality for our Easing Slider "Lite" shortcode
	 *
	 * @return string
	 */
	public function do_lite_shortcode() {

		return $this->render_lite_slider();

	}

	/**
	 * This method handles shortcodes from v1 of Easing Slider, which used the same shortcode as we do now.
	 *
	 * To work around it, this method will check for no attributes on the shortcode, as we previously didn't support them.
	 * If this is true, it will search for an Easing Slider "Lite" slider and display it if found.
	 *
	 * Quite messy, but that version of the plugin was some of my first ever PHP, so bare with me :)
	 *
	 * @param  array $atts The shortcode attributes
	 * @return void
	 */
	public function handle_lite_shortcode( $atts ) {

		// Bail if attributes aren't empty
		if ( ! empty( $atts['id'] ) ) {
			return;
		}

		// Get our "Lite" slider
		$lite_slider = $this->get_lite_slider();

		// Bail if we don't have one
		if ( ! $lite_slider ) {
			return;
		}

		// Render our "Lite" slider
		echo $lite_slider->render();

	}

	/**
	 * Upgrades the plugin from Easing Slider "Lite" v1.0 and onwards.
	 *
	 * @return void
	 */
	public function lite_upgrade_from_100() {

		// Bail if this has already been carried out
		if ( get_option( 'easingslider_upgraded_from_lite' ) ) {
			return;
		}

		/**
		 * This version of the plugin had no "version" option, so we have to improvise.
		 * 
		 * To do this, we will cycle through the options used to store the images from #1 to #10.
		 * If we find an image, we will assume the plugin settings exist and will continue with the update.
		 */
		$legacy_images = array( 'sImg1', 'sImg2', 'sImg3', 'sImg4', 'sImg5', 'sImg6', 'sImg7', 'sImg8', 'sImg9', 'sImg10' );

		// Loop through each image option, and continue if we find a match.
		foreach ( $legacy_images as $legacy_image ) {

			// Continue if we find an image, telling us that the plugin was being used
			if ( get_option( $legacy_image ) ) {

				// Create the Easing Slider "Lite" slider
				$slider = $this->create_lite_slider();

				// Transfer the data
				$slider->dimensions->width      = easingslider_validate_data( get_option( 'width' ) );
				$slider->dimensions->height     = easingslider_validate_data( get_option( 'height' ) );
				$slider->dimensions->responsive = false;
				$slider->transitions->effect    = ( get_option( 'transition' ) == 'fade' ) ? 'fade' : 'slide';
				$slider->transitions->duration  = easingslider_validate_data( get_option( 'transpeed' ) );
				$slider->navigation->arrows     = ( get_option( 'buttons' ) == '' ) ? false : true;
				$slider->navigation->pagination = ( get_option( 'sPagination' ) == '' ) ? false : true;
				$slider->playback->pause        = easingslider_validate_data( get_option( 'interval' ) );

				// Add the slides. We used to have a maximum of ten, hence the "for" loop.
				for ( $i = 1; $i <= 10; $i++ ) {

					// Get the image
					$image = get_option( "sImg{$i}" );

					// Bail if the image doesn't exist
					if ( empty( $image ) ) {
						continue;
					}

					// Push the slide data
					$slider->slides[] = (object) array(
						'id'              => $i,
						'attachment_id'   => null,
						'type'            => 'image',
						'alt'             => '',
						'aspectRatio'     => null,
						'link'            => ( get_option( "sImgLink{$i}" ) == '' ) ? 'none' : 'custom',
						'linkUrl'         => easingslider_validate_data( get_option( "sImgLink{$i}" ) ),
						'linkTargetBlank' => false,
						'title'           => '',
						'url'             => easingslider_validate_data( $image )
					);

				}

				// Save the slider
				$slider->save();

				// Flag that this update has been done
				update_option( 'easingslider_upgraded_from_lite', true );
				
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
		if ( get_option( 'easingslider_upgraded_from_lite' ) ) {
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

		// Bail if we don't have a version, telling us that the plugin wasn't installed and there is nothing to update.
		if ( ! get_option( "{$plugin_slug}_version" ) ) {
			return;
		}

		// Create the Easing Slider "Lite" slider
		$slider = $this->create_lite_slider();

		// Get the legacy slider
		$legacy_slider = get_option( "{$plugin_slug}_slideshow" );

		// Transfer the data
		$slider->general->randomize              = easingslider_validate_data( $legacy_slider->general->randomize );
		$slider->dimensions->width               = easingslider_validate_data( $legacy_slider->dimensions->width );
		$slider->dimensions->height              = easingslider_validate_data( $legacy_slider->dimensions->height );
		$slider->dimensions->responsive          = easingslider_validate_data( $legacy_slider->dimensions->responsive );
		$slider->transitions->effect             = easingslider_validate_data( $legacy_slider->transitions->effect );
		$slider->transitions->duration           = easingslider_validate_data( $legacy_slider->transitions->duration );
		$slider->navigation->arrows              = easingslider_validate_data( $legacy_slider->navigation->arrows );
		$slider->navigation->arrows_hover        = easingslider_validate_data( $legacy_slider->navigation->arrows_hover );
		$slider->navigation->arrows_position     = easingslider_validate_data( $legacy_slider->navigation->arrows_position );
		$slider->navigation->pagination          = easingslider_validate_data( $legacy_slider->navigation->pagination );
		$slider->navigation->pagination_hover    = easingslider_validate_data( $legacy_slider->navigation->pagination_hover );
		$slider->navigation->pagination_position = easingslider_validate_data( $legacy_slider->navigation->pagination_position );
		$slider->navigation->pagination_location = easingslider_validate_data( $legacy_slider->navigation->pagination_location );
		$slider->playback->enabled               = easingslider_validate_data( $legacy_slider->playback->enabled );
		$slider->playback->pause                 = easingslider_validate_data( $legacy_slider->playback->pause );

		// Add the slides
		foreach ( $legacy_slider->slides as $legacy_slide ) {

			// Query the guid
			$attachment_query = $wpdb->prepare( "SELECT ID FROM {$wpdb->posts} WHERE guid='%s'", $legacy_slide->url );

			// Attempt to get the attachment of this image
			$attachment_id = $wpdb->get_var( $attachment_query );

			// Populate the slide
			$slide = (object) array(
				'id'              => easingslider_validate_data( $legacy_slide->id ),
				'attachment_id'   => easingslider_validate_data( $attachment_id ),
				'type'            => 'image',
				'alt'             => easingslider_validate_data( $legacy_slide->alt ),
				'aspectRatio'     => null,
				'link'            => ( $legacy_slide->link ) ? 'custom' : 'none',
				'linkUrl'         => easingslider_validate_data( $legacy_slide->link ),
				'linkTargetBlank' => ( '_blank' == $legacy_slide->linkTarget ) ? '_blank': false,
				'title'           => easingslider_validate_data( $legacy_slide->title )
			);

			// Add an image URL if we aren't using an attachment
			if ( ! $attachment_id ) {
				$slide->url = easingslider_validate_data( $legacy_slide->url );
			}

			// Validate & add the slide
			$slider->slides[] = easingslider_validate_data( $slide );

		}

		// Save the slider
		$slider->save();

		// Get the settings
		$settings = get_option( 'easingslider_settings' );

		// Get the legacy settings
		$legacy_settings = get_option( "{$plugin_slug}_settings" );

		// Transfer the settings
		$settings->load_assets    = $legacy_settings['load_scripts'];
		$settings->image_resizing = $legacy_settings['resizing'];

		// Save the settings
		update_option( 'easingslider_settings', $settings );

		// Flag that this update has been done
		update_option( 'easingslider_upgraded_from_lite', true );

	}

	/**
	 * Redirects all users upgrading from legacy versions to the "What's New" page after activation
	 *
	 * @return void
	 */
	public function redirect_to_whats_new() {

		// Check for previous versions
		if ( get_option( 'riva_slider_pro_version' ) OR get_option( 'easingsliderpro_version' ) OR get_option( 'rivasliderlite_version' ) OR get_option( 'easingsliderlite_version' ) ) {

			// Redirect to "What's New"
			wp_safe_redirect( admin_url( 'index.php?page=easingslider-about' ) );
			exit;
			
		}

	}

	/**
	 * Deletes all the legacy related options.
	 *
	 * @return void
	 */
	public function remove_options() {

		delete_option( 'easingslider_lite_slider_id' );
		delete_option( 'easingslider_pro_slider_pivots' );
		delete_option( 'easingslider_upgraded_from_lite' );
		delete_option( 'easingslider_upgraded_from_pro' );

	}

}