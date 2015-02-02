<?php

/**
 * Exit if accessed directly
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Defines our plugin public facing functionality
 *
 * @uses   Easing_Slider
 * @author Matthew Ruddy
 */
class ES_Slider {

	/**
	 * The object post type
	 *
	 * @var string
	 */
	public static $object_type = 'easingslider';

	/**
	 * Constructor
	 *
	 * @return void
	 */
	public function __construct() {

		// Set our default postdata and metadata respectively
		$this->set( $this->get_postdata_defaults() );
		$this->set( $this->get_metadata_defaults() );

	}

	/**
	 * Sets an array of data on the slider
	 *
	 * @param  array|object $data The array of data
	 * @return ES_Slider
	 */
	public function set( $data ) {

		foreach ( $data as $key => $value ) {
			$this->$key = $value;
		}
		
		return $this;

	}

	/**
	 * Queries our sliders
	 *
	 * @param  array $args Any arguments to be merged with the posts query
	 * @return WP_Query
	 */
	public static function query( $args = array() ) {

		$sliders = array();

		// Our default query arguments
		$defaults = array(
			'post_status'    => 'publish',
			'post_type'      => self::$object_type,
			'orderby'        => 'ID',
			'order'          => 'asc',
			'posts_per_page' => -1
		);

		// Merge the query arguments
		$args = array_merge( (array) $defaults, (array) $args );

		// Execute the query
		$query = new WP_Query( $args );

		// Allow extensions to apply filters
		$query = apply_filters( 'easingslider_query_sliders', $query );

		return $query;

	}

	/**
	 * Gets all of the sliders
	 *
	 * @return array
	 */
	public static function all() {

		$sliders = array();

		// Query our sliders
		$query = self::query();

		// Loop through each post
		if ( $query->have_posts() ) {
			while( $query->have_posts() ) {

				$query->the_post();

				// Initiate a new slider
				$slider = new self;

				// Set our postdata and metadata
				$slider->set( get_object_vars( get_post( get_the_ID() ) ) );
				$slider->set( $slider->get_metadata() );

				// Add the slider to our sliders
				$sliders[] = $slider;

			}

			wp_reset_query();
		}

		// Allow extensions to apply filters
		$sliders = apply_filters( 'easingslider_all_sliders', $sliders );

		return $sliders;

	}

	/**
	 * Finds a slider
	 *
	 * @param  int $id The slider ID
	 * @return ES_Slider
	 */
	public static function find( $id ) {
		
		// Fetch our post
		$post = get_post( $id );

		// Bail if no post was found
		if ( ! $post ) {
			return false;
		}

		// Bail if not our post type
		if ( self::$object_type != $post->post_type ) {
			return false;
		}

		// Initiate a new slider
		$slider = new self;

		// Set our postdata and metadata
		$slider->set( get_object_vars( $post ) );
		$slider->set( $slider->get_metadata() );

		// Allow extensions to apply filters
		$slider = apply_filters( 'easingslider_find_slider', $slider );

		return $slider;

	}

	/**
	 * Gets the total number slides that exist
	 *
	 * @param  string $post_status The post status
	 * @return object
	 */
	public static function total( $post_status = 'publish' ) {

		$total_items = wp_count_posts( self::$object_type );

		return $total_items->$post_status;

	}

	/**
	 * Creates a new slider instance
	 *
	 * @return ES_Slider
	 */
	public static function create() {

		// Initiate a new slider
		$slider = new self;

		// Allow extensions to apply filters
		$slider = apply_filters( 'easingslider_create_slider', $slider );

		return $slider;
		
	}

	/**
	 * Deletes a slider
	 *
	 * @param  int $id The slider ID
	 * @return void
	 */
	public static function delete( $id ) {

		// Initiate a new slider
		$slider = new self;

		// Set the ID
		$slider->ID = $id;

		// Delete the post
		wp_delete_post( $id );

		// Allow extensions to do actions
		do_action( 'easingslider_delete_slider', $id );

	}

	/**
	 * Saves a slider
	 *
	 * @return int|false
	 */
	public function save() {

		// "Before" filter
		$slider = apply_filters( 'easingslider_pre_save_slider', $this );
		
		// Create or update the post
		if ( isset( $slider->ID ) ) {
			$slider->ID = wp_update_post( (array) $slider );
		}
		else {
			$slider->ID = wp_insert_post( (array) $slider );
		}

		// If an error has occurred, bail.
		if ( ! $slider->ID ) {
			return false;
		}

		// Create/update our metadata
		$slider->update_metadata();

		// Allow extensions to do actions
		do_action( 'easingslider_save_slider', $slider );

		return $slider;

	}

	/**
	 * Renders the slider
	 *
	 * @return void
	 */
	public function render() {

		// "Before" action
		$slider = apply_filters( 'easingslider_pre_display_slider', $this );

		// "Before" filter
		$html = apply_filters( 'easingslider_before_display_slider', '', $slider );

		// Start the output
		$html .= "<div class=\"". esc_attr( $slider->get_html_classes() ) ."\" data-options=\"". esc_attr( $slider->get_html_data() ) ."\" style=\"". esc_attr( $slider->get_html_styles() ) ."\">";

		// Inner "before" filter
		$html = apply_filters( 'easingslider_before_slider_content', $html, $slider );

			// Open the viewport
			$html .= "<div class=\"easingslider-viewport\" style=\"padding-top: ". ( 100 * $slider->calculate_aspect_ratio() ) ."% !important;\">";

				// Output the slides
				foreach ( $slider->slides as $slide ) {
					$html .= $slider->render_slide_html( $slide );
				}

			// Close the viewport
			$html .= "</div>";

			// Output the arrows
			if ( $slider->navigation->arrows ) {
				$html .= $slider->render_arrows_html();
			}

			// Output the pagination
			if ( $slider->navigation->pagination ) {
				$html .= $slider->render_pagination_html();
			}

		// Inner "after" filter
		$html = apply_filters( 'easingslider_after_slider_content', $html, $slider );

		// End the output
		$html .= "</div>";

		// "After" filter
		$html = apply_filters( 'easingslider_after_display_slider', $html, $slider );

		// Allow extensions to do actions
		do_action( 'easingslider_render_slider', $slider );

		return $html;

	}

	/**
	 * Registers all of our slider assets
	 *
	 * @return void
	 */
	public function register_assets() {

		// Get our directories
		$css_dir = plugin_dir_url( Easing_Slider::$file ) . 'css';
		$js_dir  = plugin_dir_url( Easing_Slider::$file ) . 'js';

		// Get file suffix
		$suffix  = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ? '' : '.min';

		// Register our stylesheets
		wp_register_style( 'easingslider', "{$css_dir}/easingslider{$suffix}.css", false, Easing_Slider::$version );

		// Register our javascripts
		wp_register_script( 'easingslider', "{$js_dir}/jquery.easingslider{$suffix}.js", array( 'jquery' ), Easing_Slider::$version );

	}

	/**
	 * Enqueues all of our slider assets
	 *
	 * @return void
	 */
	public function enqueue_assets() {

		// Get the settings
		$settings = get_option( 'easingslider_settings' );

		/**
		 * If we've set the asset loading to "Optimized" and we haven't displayed a slider on this page, let's bail.
		 * This prevents the assets from loading when they aren't needed.
		 *
		 * Otherwise, by default, assets will be loaded on every page ("Compatibility" mode).
		 */
		if ( 'footer' == $settings->load_assets && ! did_action( 'easingslider_render_slider' ) ) {
			add_action( 'wp_footer', array( $this, 'enqueue_assets' ) );
			return;
		}

		// Enqueue our assets
		wp_enqueue_style( 'easingslider' );
		wp_enqueue_script( 'easingslider' );

		// Trigger action for our extensions
		do_action( 'easingslider_enqueue_assets' );

	}

	/**
	 * Method for getting our post default keys and values
	 *
	 * @return array
	 */
	protected function get_postdata_defaults() {

		$postdata_defaults = array(
			'post_title'     => '',
			'post_status'    => 'publish',
			'post_type'      => self::$object_type
		);

		return apply_filters( 'easingslider_postdata_defaults', $postdata_defaults );

	}

	/**
	 * Method for getting our meta default keys and values
	 *
	 * @return array
	 */
	protected function get_metadata_defaults() {

		// Our array of meta default data
		$metadata_defaults = array(
			'slides'      => array(),
			'general'     => (object) array(
				'randomize'           => false
			),
			'dimensions'  => (object) array(
				'width'               => 640,
				'height'              => 400,
				'responsive'          => true
			),
			'transitions' => (object) array(
				'effect'              => 'fade',
				'duration'            => 400
			),
			'navigation'  => (object) array(
				'arrows'              => true,
				'arrows_hover'        => false,
				'arrows_position'     => 'inside',
				'pagination'          => true,
				'pagination_hover'    => false,
				'pagination_position' => 'inside',
				'pagination_location' => 'bottom-center'
			),
			'playback'    => (object) array(
				'enabled'             => true,
				'pause'               => 4000
			)
		);

		return apply_filters( 'easingslider_metadata_defaults', $metadata_defaults );

	}

	/**
	 * Method for getting our metadata
	 *
	 * @return array
	 */
	protected function get_metadata() {
	
		$metadata = array();
	
		// Get the defaults
		$metadata_defaults = $this->get_metadata_defaults();

		// Loop through and merge in actual metadata
		foreach ( $metadata_defaults as $default_key => $default_metadata ) {
			$actual_metadata = get_post_meta( $this->ID, "_easingslider_{$default_key}", true );

			// Merge in the data
			if ( ! empty( $actual_metadata ) ) {
				$metadata[ $default_key ] = array_merge( (array) $default_metadata, (array) $actual_metadata ); 

				// Convert back to object if necessary
				if ( is_object( $default_metadata ) ) {
					$metadata[ $default_key ] = (object) $metadata[ $default_key ];
				}
			}
		}

		return $metadata;

	}

	/**
	 * Method for updating our metadata
	 *
	 * @return void
	 */
	protected function update_metadata() {

		$metadata = $this->get_metadata_defaults();

		// Loop through and attempt to get postmeta
		foreach ( $metadata as $key => $value ) {
			update_post_meta( $this->ID, "_easingslider_{$key}", $this->$key );
		}

	}

	/**
	 * Returns the possible transitions
	 *
	 * @return array
	 */
	public function get_transitions() {

		return apply_filters( 'easingslider_transitions', array(
			'slide' => __( 'Slide', 'easingslider' ),
			'fade'  => __( 'Fade', 'easingslider' )
		) );
		
	}

	/**
	 * Returns HTML classes
	 *
	 * @return string
	 */
	protected function get_html_classes() {

		$classes = "easingslider easingslider-{$this->ID} use-{$this->transitions->effect}";

		return apply_filters( 'easingslider_get_html_classes', $classes, $this );

	}

	/**
	 * Returns HTML styling
	 *
	 * @return string
	 */
	protected function get_html_styles() {

		if ( $this->dimensions->responsive ) {
			$styles = "max-width: {$this->dimensions->width}px !important; max-height: {$this->dimensions->height}px !important;";
		}
		else {
			$styles = "width: {$this->dimensions->width}px !important; height: {$this->dimensions->height}px !important;";
		}

		return apply_filters( 'easingslider_get_html_styles', $styles, $this );

	}

	/**
	 * Returns HTML data options attribute
	 *
	 * @return string
	 */
	protected function get_html_data() {

		$data = $this->get_metadata();

		return json_encode( apply_filters( 'easingslider_get_html_data', $data, $this ) );
		
	}

	/**
	 * Renders HTML for a slide
	 *
	 * @param  object $slide The slide object
	 * @return string
	 */
	protected function render_slide_html( $slide ) {

		// Animation duration styling. This is horrible, but necessary to have varied animation durations
		$animation_duration = "-webkit-animation-duration: {$this->transitions->duration}ms; -moz-animation-duration: {$this->transitions->duration}ms; -ms-animation-duration: {$this->transitions->duration}ms; -o-animation-duration: {$this->transitions->duration}ms; animation-duration: {$this->transitions->duration}ms;";

		/**
		 * We initially "display: none" our slides to prevent a brief flash of unstyled HTML when loading the CSS & Javascript in the footer.
		 *
		 * The Javascript will set slides to "display: block".
		 */
		$html = "<div class=\"easingslider-slide easingslider-{$slide->type}-slide\" style=\"{$animation_duration}\">";

			$html = apply_filters( 'easingslider_before_display_slide', $html, $slide, $this );
		
			$html = apply_filters( "easingslider_display_{$slide->type}_slide", $html, $slide, $this );

			$html = apply_filters( 'easingslider_after_display_slide', $html, $slide, $this );

		$html .= "</div>";

		return apply_filters( 'easingslider_get_html_slide', $html, $slide, $this );

	}

	/**
	 * Renders HTML for the arrows
	 *
	 * @return string
	 */
	protected function render_arrows_html() {

		$html  = "<div class=\"easingslider-arrows easingslider-next {$this->navigation->arrows_position}\" style=\"display: none;\"></div>";
		$html .= "<div class=\"easingslider-arrows easingslider-prev {$this->navigation->arrows_position}\" style=\"display: none;\"></div>";

		return apply_filters( 'easingslider_get_html_arrows', $html, $this );

	}

	/**
	 * Renders HTML for the pagination
	 *
	 * @return string
	 */
	protected function render_pagination_html() {

		$html = "<div class=\"easingslider-pagination {$this->navigation->pagination_position} {$this->navigation->pagination_location}\" style=\"display: none;\">";

			// Create an icon for each slide
			foreach ( $this->slides as $slide ) {
				$html .= "<div class=\"easingslider-icon\"></div>";
			}
	
		$html .= "</div>";

		return apply_filters( 'easingslider_get_html_pagination', $html, $this );
		
	}

	/**
	 * Calculates the aspect ratio
	 *
	 * @return string
	 */
	protected function calculate_aspect_ratio() {

		$aspect_ratio = ( $this->dimensions->height / $this->dimensions->width );

		return $aspect_ratio;
		
	}

	/**
	 * Randomizes the slides if set to do so
	 *
	 * @param  ES_Slider $slider The slider object
	 * @return ES_Slider
	 */
	public function maybe_randomize( $slider ) {

		if ( $slider->general->randomize ) {
			shuffle( $slider->slides );
		}

		return $slider;

	}

	/**
	 * Sets a default "(no title)" if no slider title was provided
	 *
	 * @param  ES_Slider $slider The slider object
	 * @return ES_Slider
	 */
	public function no_title( $slider ) {

		if ( empty( $slider->post_title ) ) {
			$slider->post_title = __( '(no title)', 'easingslider' );
		}

		return $slider;

	}

	/**
	 * Removes unneccessary attributes from HTML data
	 *
	 * @param  object $data The slider data
	 * @return object
	 */
	public function cleanup_data( $data ) {

		unset( $data['slides'] );

		return $data;

	}

	/**
	 * Adds the HTML for the preloader
	 *
	 * @param  string $html   The slider HTML
	 * @param  object $slider The slider object
	 * @return string
	 */
	public function add_preload( $html, $slider ) {

		// Add the HTML
		$html .= "<div class=\"easingslider-preload\"></div>";

		return $html;

	}

	/**
	 * Adds the HTML for an image
	 *
	 * @param  string $html   The slider HTML
	 * @param  object $slide  The slide object
	 * @param  object $slider The slider object
	 * @return string
	 */
	public function add_image( $html, $slide, $slider ) {

		// Get the image URL
		$image_url = ( $slide->attachment_id ) ? wp_get_attachment_url( $slide->attachment_id ) : $slide->url;

		// Filter for modifying the image URL (needed for resizing, etc).
		$image_url = apply_filters( 'easingslider_modify_image_url', $image_url, $slider->dimensions->width, $slider->dimensions->height );

		// Add the HTML
		$html .= "<img src=\"{$image_url}\" title=\"{$slide->title}\" alt=\"{$slide->alt}\" class=\"easingslider-image\" />";

		return $html;

	}

	/**
	 * Opens a link
	 *
	 * @param  string $html   The slider HTML
	 * @param  object $slide  The slide object
	 * @param  object $slider The slider object
	 * @return string
	 */
	public function open_link( $html, $slide, $slider ) {

		if ( ! empty( $slide->link ) && 'none' != $slide->link ) {

			// The link attributes string
			$attributes_string = "href=\"{$slide->linkUrl}\" target=\"{$slide->linkTargetBlank}\" class=\"easingslider-link\"";

			// Apply filters to the string
			$attributes_string = apply_filters( 'easingslider_open_link_html_attributes', $attributes_string, $slide, $slider );

			// Add the HTML
			$html .= "<a {$attributes_string}>";

		}

		return $html;

	}

	/**
	 * Closes a link
	 *
	 * @param  string $html   The slider HTML
	 * @param  object $slide  The slide object
	 * @param  object $slider The slider object
	 * @return string
	 */
	public function close_link( $html, $slide, $slider ) {

		if ( ! empty( $slide->link ) && 'none' != $slide->link ) {
			$html .= "</a>";
		}

		return $html;

	}

}