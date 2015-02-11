<?php

/**
 * Exit if accessed directly
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Defines & loads our customizations
 *
 * @author Matthew Ruddy
 */
class ES_Customizations {

	/**
	 * Returns the defaults for our slider "customizations" metadata
	 *
	 * @return object
	 */
	public function defaults() {

		$plugin_url = plugins_url( 'easing-slider/images' );

		return (object) array(
			'arrows'     => (object) array(
				'next'     => "{$plugin_url}/nav-arrow-next.png",
				'prev'     => "{$plugin_url}/nav-arrow-prev.png",
				'width'    => 30,
				'height'   => 30
			),
			'pagination' => (object) array(
				'inactive' => "{$plugin_url}/nav-icon-inactive.png",
				'active'   => "{$plugin_url}/nav-icon-active.png",
				'width'    => 15,
				'height'   => 15
			),
			'border'     => (object) array(
				'color'    => '#000000',
				'width'    => 0,
				'radius'   => 0
			),
			'shadow'     => (object) array(
				'enabled'  => false,
				'image'    => "{$plugin_url}/shadow.png"
			)
		);

	}

	/**
	 * Merges in our default slider "customizations" metadata
	 *
	 * @param  array $metadata_defaults The default metadata
	 * @return array
	 */
	public function merge_defaults( $metadata_defaults ) {

		$defaults = array( 'customizations' => $this->defaults() );

		return array_merge( $metadata_defaults, $defaults );

	}

	/**
	 * Displays the customization styling for a slider
	 *
	 * @param  string    $html   The slider HTML
	 * @param  ES_Slider $slider The slider object
	 * @return void
	 */
	public function display_styling( $html, $slider ) {

		// Get the defaults & customizations
		$defaults       = $this->defaults();
		$customizations = $slider->customizations;

		// Bail if we have no customizations
		if ( $defaults == $customizations ) {
			return $html;
		}

		// Open the output
		$html .= "<style type=\"text/css\">";

			// Next & Previous Arrows
			if ( $defaults->arrows != $customizations->arrows ) {

				// Width & height
				$html .= ".easingslider-{$slider->ID} .easingslider-arrows { ";
					if ( $defaults->arrows->width != $customizations->arrows->width ) {
						$html .= "width: {$customizations->arrows->width}px; ";
					}
					if ( $defaults->arrows->height != $customizations->arrows->height ) {
						$margin_top = ( $customizations->arrows->height / 2 );

						$html .= "height: {$customizations->arrows->height}px; ";
						$html .= "margin-top: {$margin_top}px; ";
					}
				$html .= "}";

				// "Next" image
				if ( $defaults->arrows->next != $customizations->arrows->next ) {
					$html .= ".easingslider-{$slider->ID} .easingslider-next { ";
						$html .= "background-image: url({$customizations->arrows->next}); ";
					$html .= "}";
				}

				// "Prev" image
				if ( $defaults->arrows->prev != $customizations->arrows->prev ) {
					$html .= ".easingslider-{$slider->ID} .easingslider-prev { ";
						$html .= "background-image: url({$customizations->arrows->prev}); ";
					$html .= "}";
				}

			}

			// Pagination
			if ( $defaults->pagination != $customizations->pagination ) {

				// Width & height, and "inactive" image
				$html .= ".easingslider-{$slider->ID} .easingslider-icon { ";
					if ( $defaults->pagination->width != $customizations->pagination->width ) {
						$html .= "width: {$customizations->pagination->width}px; ";
					}
					if ( $defaults->pagination->height != $customizations->pagination->height ) {
						$html .= "height: {$customizations->pagination->height}px; ";
					}
					if ( $defaults->pagination->inactive != $customizations->pagination->inactive ) {
						$html .= "background-image: url({$customizations->pagination->inactive}); ";
					}
				$html .= "}";

				// "Active" image
				if ( $defaults->pagination->active != $customizations->pagination->active ) {
					$html .= ".easingslider-{$slider->ID} .easingslider-icon.active { ";
						$html .= "background-image: url({$customizations->pagination->active}); ";
					$html .= "}";
				}

			}

			// Border
			if ( $defaults->border != $customizations->border ) {
				$html .= ".easingslider-{$slider->ID} { ";

					// Color
					if ( $defaults->border->color != $customizations->border->color ) {
						$html .= "border-color: {$customizations->border->color}; ";
					}

					// Width
					if ( $defaults->border->width != $customizations->border->width ) {
						$html .= "border-width: {$customizations->border->width}px; ";
						$html .= "border-style: solid; ";
					}

					// Radius
					if ( $defaults->border->radius != $customizations->border->radius ) {
						$html .= "-webkit-border-radius: {$customizations->border->radius}px; ";
						$html .= "-moz-border-radius: {$customizations->border->radius}px; ";
						$html .= "border-radius: {$customizations->border->radius}px; ";
					}

				$html .= "}";
			}

			// Drop Shadow (if enabled)
			if ( $customizations->shadow->enabled && ! empty( $customizations->shadow->image ) ) {
				$html .= ".easingslider-shadow-{$slider->ID} { ";

					// Make responsive or fixed
					if ( $slider->dimensions->responsive ) {
						$html .= "width: 100%; ";
						$html .= "max-width: {$slider->dimensions->width}px; ";
					}
					else {
						$html .= "width: {$slider->dimensions->width}px; ";
					}

				$html .= "}";
			}
			

		// Close the output
		$html .= "</style>";

		return $html;

	}

	/**
	 * Removes customizations from slider container data (it's not needed).
	 *
	 * @param  array $metadata The array of metadata
	 * @return array
	 */
	public function remove_data( $metadata ) {

		// Remove the data
		unset( $metadata['customizations'] );

		return $metadata;

	}

	/**
	 * Displays a drop shadow beneath the slider
	 *
	 * @param  string    $html   The slider HTML
	 * @param  ES_Slider $slider The slider object
	 * @return string
	 */
	public function drop_shadow( $html, $slider ) {

		// Get the customizations
		$customizations = $slider->customizations;

		/**
		 * If we are on the "customizer" page, we always want to render the shadow.
		 * This allow us to toggle it with our live preview. Can't be achieved as easily otherwise.
		 *
		 * We also add some CSS to ensure it is hidden if currently disabled, but still printed in the slider's HTML.
		 */
		if ( isset( $_GET['page'] ) && 'easingslider_manage_customizations' == $_GET['page'] ) {

			// No need for this if the shadow is already enabled
			if ( ! $customizations->shadow->enabled ) {
				$customizations->shadow->enabled = true;

				// Add the HTML
				$html .= "<style type=\"text/css\">";
					$html .= ".easingslider-shadow { ";
						$html .= "display: none; ";

						// Make responsive or fixed
						if ( $slider->dimensions->responsive ) {
							$html .= "width: 100%; ";
							$html .= "max-width: {$slider->dimensions->width}px; ";
						}
						else {
							$html .= "width: {$slider->dimensions->width}px; ";
						}
					$html .= "}";
				$html .= "</style>";
			}

		}
		
		// Render the shadow if enabled
		if ( $customizations->shadow->enabled && ! empty( $customizations->shadow->image ) ) {
			$html .= "<div class=\"easingslider-shadow easingslider-shadow-{$slider->ID}\">";
				$html .= "<img src=\"{$customizations->shadow->image}\" style=\"width: 100%;\" />";
			$html .= "</div>";
		}

		return $html;

	}

}