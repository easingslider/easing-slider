<?php

/**
 * Exit if accessed directly
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class for creating our toplevel admin menu.
 *
 * @author Matthew Ruddy
 */
class ES_Menu {

	/**
	 * Adds the plugin toplevel menu
	 *
	 * @return void
	 */
	public function add_toplevel_menu() {

		global $menu;

		// Default menu positioning
		$position = '100.1';

		// If enabled, relocate the plugin menus higher
		if ( apply_filters( 'easingslider_relocate_menus', __return_true() ) ) {

			for ( $position = '40.1'; $position <= '100.1'; $position += '0.1' ) {

				// Ensure there is a space before and after each position we are checking, leaving room for our separators.
				$before = $position - '0.1';
				$after  = $position + '0.1';

				// Do the checks for each position. These need to be strings, hence the quotation marks.
				if ( isset( $menu[ "$position" ] ) ) {
					continue;
				}
				if ( isset( $menu[ "$before" ] ) ) {
					continue;
				}
				if ( isset( $menu[ "$after" ] ) ) {
					continue;
				}

				// If we've successfully gotten this far, break the loop. We've found the position we need.
				break;

			}

		}

		// Add toplevel menu
		add_menu_page(
			__( 'Sliders', 'easingslider' ),
			__( 'Sliders', 'easingslider' ),
			'easingslider_edit_sliders',
			'easingslider_edit_sliders',
			null,
			'dashicons-images-alt',
			"$position"
		);

		// Do action allowing extension to add their own toplevel menus
		do_action( 'easingslider_add_toplevel_menu', $position );

		// Add the menu separators if menus have been relocated (they are by default). Quotations marks ensure these are strings.
		if ( apply_filters( 'easingslider_relocate_menus', __return_true() ) ) {
			$this->add_menu_separator( "$before" );
			$this->add_menu_separator( "$after" );
		}

	}
	
	/**
	 * Create a separator in the admin menus, above and below our plugin menus
	 *
	 * @param  string $position The menu position to insert the separator
	 * @return void
	 */
	protected function add_menu_separator( $position = '40.1' ) {


		global $menu;

		$index = 0;
		foreach ( $menu as $offset => $section ) {

			if ( 'separator' == substr( $section[2], 0, 9 ) ) {
				$index++;
			}

			if ( $offset >= $position ) {

				// Quotation marks ensures the position is a string. Integers won't work if we are using decimal values.
				$menu[ "$position" ] = array( '', 'read', "separator{$index}", '', 'wp-menu-separator' );
				break;

			}
			
		}
		ksort( $menu );

	}

}