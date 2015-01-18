<?php

/**
 * Exit if accessed directly
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Alias for displaying a slider shortcode
 *
 * @uses   ES_Shortcode
 * 
 * @param  int|array $args The slider arguments, or numerical ID.
 * @return void
 */
if ( ! function_exists( 'easingslider' ) ) {
    function easingslider( $args = array() ) {

        // Continue if we have our shortcode class
        if ( class_exists( 'ES_Shortcode' ) ) {

            // Handle if we've passed just an ID
            if ( is_int( $args ) ) {
                $args = array( 'id' => $args );
            }

            // Create a new shortcode instance
            $shortcode = new ES_Shortcode();

            // Do the shortcode
            echo $shortcode->render( $args );

        }

    }
}

/**
 * Registers an extension, settings up it's licensing and updater.
 *
 * @uses   ES_Update_Manager
 * 
 * @param  string $name    The plugin name
 * @param  string $file    The plugin file
 * @param  int    $version The plugin version
 * @return ES_Update_Manager
 */
if ( ! function_exists( 'easingslider_register_extension' ) ) {
    function easingslider_register_extension( $name, $file, $version ) {

        // Setup updates and licensing
        if ( class_exists( 'ES_Update_Manager' ) ) {
            return new ES_Update_Manager( $name, $file, $version );
        }
        
    }
}

/**
 * Helpful function for requiring a "partial" template file, or alternatively loading an action.
 * Often used for allowing extensions to hook into core plugin panels/views.
 *
 * @param  string $name     The file/action name
 * @param  mixed  $settings The data to pass to the file/action
 * @return void
 */
if ( ! function_exists( 'easingslider_partial_or_action' ) ) {
	function easingslider_partial_or_action( $name, $settings = false ) {

        // Get the filepath
        $file_path = plugin_dir_path( dirname( __FILE__ ) ) . "partials/{$name}.php";

        // Load the file, or trigger an action if no file is found.
        if ( file_exists( $file_path ) ) {
            require $file_path;
        }
        else {
            do_action( "easingslider_{$name}", $settings );
        }

	}
}

/**
 * Queues an admin message to be displayed
 *
 * @param  string $text The message text
 * @param  string $type The message type (success, error, etc).
 * @return void
 */
if ( ! function_exists( 'easingslider_admin_notice' ) ) {
    function easingslider_show_notice( $text, $type = 'updated' ) {

        // Add slashes to text
        $text = addslashes( $text );

        // Queue the message
        $message = "<div class='message $type'><p>$text</p></div>";
        add_action( 'admin_notices', create_function( '', 'echo "'. $message .'";' ) );
        
    }
}

/**
 * Validates the data in an array for database insertion
 *
 * @param  mixed $data The data to validate
 * @return mixed
 */
if ( ! function_exists( 'easingslider_validate_data' ) ) {
    function easingslider_validate_data( $data ) {

        // Stripslashes
        $data = stripslashes_deep( $data );

        // Handle the various data types
        if ( is_object( $data ) OR is_array( $data ) ) {
            foreach ( $data as $key => &$value ) {
                $value = easingslider_validate_data( $value );
            }
        }
        else if ( is_string( $data ) && ( ! is_object( json_decode( $data ) ) OR ! is_array( json_decode( $data ) ) ) ) {

            // Bail if just an empty string
            if ( $data == '' ) {
                return $data;
            }

            // Ensure integers are integers, and booleans are booleans
            if ( ! is_null( ( $int = filter_var( $data, FILTER_VALIDATE_INT, FILTER_NULL_ON_FAILURE ) ) ) ) {
                $data = $int;
            }
            else if ( ! is_null( ( $bool = filter_var( $data, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE ) ) ) ) {
                $data = $bool;
            }

        }

        return $data;

    }
}

/**
 * Generates a HTML attributes string, filtering for good measure.
 *
 * @param  string $slug The slug, used in the filter
 * @param  array  $atts The html attributes array
 * @return string
 */
if ( ! function_exists( 'easingslider_html_attributes_string' ) ) {
    function easingslider_html_attributes_string( $slug, $atts = array(), $output = '' ) {

        // Filter the attributes
        $atts = apply_filters( "easingslider_{$slug}_html_attributes", $atts );

        // Generate the string
        foreach ( $atts as $key => $value ) {
            if ( ! $value ) {
                $output .= "{$key}=\"{$value}\" ";
            }
        }

        return $output;
        
    }
}

/**
 * Displays admin footer text, thanking the user for using Easing Slider :)
 *
 * @param  string $footer_text The current footer text
 * @return string
 */
if ( ! function_exists( 'easingslider_admin_footer_text' ) ) {
    function easingslider_admin_footer_text( $footer_text ) {

        global $current_screen;

        // Only display custom text on Easing Slider pages
        if ( isset( $current_screen->id ) && false !== strpos( $current_screen->id, 'easingslider' ) ) {
            return sprintf( __( 'Please rate <strong>Easing Slider</strong> <a href="%1$s" target="_blank">&#9733;&#9733;&#9733;&#9733;&#9733;</a> on <a href="%1$s" target="_blank">WordPress.org</a> to help us keep this plugin free.  Thank you from the Easing Slider team!', 'easingslider' ), __( 'http://wordpress.org/support/view/plugin-reviews/easing-slider?filter=5', 'easingslider' ) );
        } else {
            return $footer_text;
        }

    }
    add_filter( 'admin_footer_text', 'easingslider_admin_footer_text' );
}
