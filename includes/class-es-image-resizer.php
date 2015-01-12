<?php

/**
 * Exit if accessed directly
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Our custom image resizing class (no Timthumb here!)
 *
 * @author Matthew Ruddy
 */
class ES_Image_Resizer {

	/**
	 * Resizes an image
	 *
	 * @param  string   $url    The image URL
	 * @param  int      $width  The desired width
	 * @param  int      $height The desired height
	 * @param  boolean  $crop   Toggles cropping
	 * @return array|WP_Error
	 */
	public function resize( $url, $width, $height, $crop = true ) {

		global $wpdb;

		// Return a WP_Error if an empty URL was provided
		if ( empty( $url ) ) {
			return new WP_Error( 'no_image_url', __( 'No image URL has been entered.', 'easingslider' ), $url );
		}
			
		/**
		 * Bail if this image isn't in the Media Library.
		 * 
		 * We only want to resize Media Library images, so we can be sure they get deleted correctly when appropriate.
		 */
		$query          = $wpdb->prepare( "SELECT * FROM $wpdb->posts WHERE guid='%s'", $url );
		$get_attachment = $wpdb->get_results( $query );

		if ( ! $get_attachment ) {
			return array( 'url' => $url, 'width' => $width, 'height' => $height );
		}

		// Get the image file path
		$file_path = parse_url( $url );
		$file_path = $_SERVER['DOCUMENT_ROOT'] . $file_path['path'];
		
		// Additional handling for multisite
		if ( is_multisite() ) {
			global $blog_id;
			$blog_details = get_blog_details( $blog_id );
			$file_path    = str_replace( $blog_details->path . 'files/', '/wp-content/blogs.dir/'. $blog_id .'/files/', $file_path );
		}

		// Destination width and height variables
		$dest_width  = apply_filters( 'easingslider_resize_image_width',  $width,  $get_attachment );
		$dest_height = apply_filters( 'easingslider_resize_image_height', $height, $get_attachment );

		// File name suffix (appended to original file name)
		$suffix = "{$dest_width}x{$dest_height}";

		// Some additional info about the image
		$info = pathinfo( $file_path );
		$dir  = $info['dirname'];
		$ext  = $info['extension'];
		$name = wp_basename( $file_path, ".$ext" );

		// Suffix applied to filename
		$suffix = "{$dest_width}x{$dest_height}";

		// Get the destination file name
		$dest_file_name = "{$dir}/{$name}-{$suffix}.{$ext}";

		// Execute the resizing if resized image doesn't already exist.
		if ( ! file_exists( $dest_file_name ) ) {

			// Load Wordpress Image Editor
			$editor = wp_get_image_editor( $file_path );

			// Bail if we encounter a WP_Error
			if ( is_wp_error( $editor ) ) {
				return array( 'url' => $url, 'width' => $width, 'height' => $height );
			}

			// Get the original image size
			$size        = $editor->get_size();
			$orig_width  = $size['width'];
			$orig_height = $size['height'];

			$src_x = $src_y = 0;
			$src_w = $orig_width;
			$src_h = $orig_height;

			// Handle cropping
			if ( $crop ) {

				$cmp_x = $orig_width / $dest_width;
				$cmp_y = $orig_height / $dest_height;

				// Calculate x or y coordinate, and width or height of source
				if ( $cmp_x > $cmp_y ) {
					$src_w = round( $orig_width / $cmp_x * $cmp_y );
					$src_x = round( ( $orig_width - ( $orig_width / $cmp_x * $cmp_y ) ) / 2 );
				}
				else if ( $cmp_y > $cmp_x ) {
					$src_h = round( $orig_height / $cmp_y * $cmp_x );
					$src_y = round( ( $orig_height - ( $orig_height / $cmp_y * $cmp_x ) ) / 2 );
				}

			}

			// Time to crop the image
			$editor->crop( $src_x, $src_y, $src_w, $src_h, $dest_width, $dest_height );

			// Now let's save the image
			$saved = $editor->save( $dest_file_name );

			// Get resized image information
			$resized_url    = str_replace( basename( $url ), basename( $saved['path'] ), $url );
			$resized_width  = $saved['width'];
			$resized_height = $saved['height'];
			$resized_type   = $saved['mime-type'];

			/**
			 * Add the resized dimensions to original image metadata
			 * 
			 * This ensures our resized images are deleted when the original image is deleted from the Media Library
			 */
			$metadata = wp_get_attachment_metadata( $get_attachment[0]->ID );
			if ( isset( $metadata['image_meta'] ) ) {
				$metadata['image_meta']['resized_images'][] = $resized_width .'x'. $resized_height;
				wp_update_attachment_metadata( $get_attachment[0]->ID, $metadata );
			}

			// Create the image array
			$resized_image = array(
				'url'    => $resized_url,
				'width'  => $resized_width,
				'height' => $resized_height,
				'type'   => $resized_type
			);

		}
		else {
			$resized_image = array(
				'url'    => str_replace( basename( $url ), basename( $dest_file_name ), $url ),
				'width'  => $dest_width,
				'height' => $dest_height,
				'type'   => $ext
			);
		}

		// And we're done!
		return $resized_image;

	}

	/**
	 * Swaps the image URL with the URL of our resized image, if resizing is enabled.
	 * 
	 * @param  string $image_url The current image URL
	 * @param  object $width     The desired image width
	 * @param  object $height    The desired image height
	 * @return object
	 */
	public function resized_image_url( $image_url, $width, $height ) {

		// Get plugin settings
		$settings = get_option( 'easingslider_settings' );

		// Check for resizing
		if ( ! empty( $settings->image_resizing ) ) {
				
			// Resize the image
			$resized_image = $this->resize( $image_url, $width, $height, true );

			// Check for errors
			if ( ! is_wp_error( $resized_image ) ) {
				$image_url = $resized_image['url'];
			}

		}

		return $image_url;

	}

	/**
	 * Deletes any resized images when the original image is deleted from the Wordpress Media Library.
	 *
	 * @param  int $post_id The attachment ID
	 * @return void
	 */
	public function delete_resized_attachments( $post_id ) {

		// Get attachment image metadata
		$metadata = wp_get_attachment_metadata( $post_id );

		// Bail if we've failed
		if ( ! $metadata ) {
			return;
		}

		// Bail if we don't have the parameters needed to continue
		if ( ! isset( $metadata['file'] ) OR ! isset( $metadata['image_meta']['resized_images'] ) ) {
			return;
		}

		// Establish variables
		$pathinfo       = pathinfo( $metadata['file'] );
		$resized_images = $metadata['image_meta']['resized_images'];

		// Get Wordpress uploads directory
		$wp_upload_dir = wp_upload_dir();
		$upload_dir = $wp_upload_dir['basedir'];

		// Bail if WordPress uploads directory doesn't exist
		if ( ! is_dir( $upload_dir ) ) {
			return;
		}

		// Delete the resized images
		foreach ( $resized_images as $dimensions ) {

			// Get the resized images filename
			$file = $upload_dir .'/'. $pathinfo['dirname'] .'/'. $pathinfo['filename'] .'-'. $dimensions .'.'. $pathinfo['extension'];

			// Delete the resized image
			@unlink( $file );

		}

	}

}