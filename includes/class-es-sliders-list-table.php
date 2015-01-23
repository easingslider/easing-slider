<?php

/**
 * Exit if accessed directly
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Ensure WP_List_Table class has been loaded
 */
if ( ! class_exists( 'WP_List_Table' ) ) {
	require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

/**
 * Our extension for WP_List_Table for listing sliders
 *
 * @uses   ES_Slider
 * @author Matthew Ruddy
 */
class ES_Sliders_List_Table extends WP_List_Table {

	/**
	 * Constructor
	 *
	 * @return void
	 */
	public function __construct() {

		parent::__construct( array(
			'singular' => 'slider',
			'plural'   => 'sliders',
			'ajax'     => false
		));

	}

	/**
	 * Get our list table columns
	 * 
	 * @return array
	 */
	public function get_columns() {

		// Set our columns
		$columns = array(
			'cb'          => '<input type="checkbox" />',
			'ID'          => __( 'ID', 'easingslider' ),
			'post_title'  => __( 'Title', 'easingslider' ),
			'post_author' => __( 'Author', 'easingslider' ),
			'post_date'   => __( 'Date', 'easingslider' ),
			'shortcode'   => __( 'Shortcode', 'easingslider' )
		);

		return apply_filters( 'easingslider_sliders_list_table_columns', $columns );

	}

	/**
	 * Get the columns that have been hidden by the current user
	 *
	 * @return array
	 */
	public function get_hidden_columns() {

		// Get the current screen
		$current_screen = get_current_screen();

		// Get the hidden columns
		$hidden_columns = (array) get_user_option( "manage{$current_screen->id}columnshidden" );

		return $hidden_columns;

	}
	
	/**
	 * Decide which columns to activate the sorting functionality on
	 * 
	 * @return array
	*/
	public function get_sortable_columns() {

		// Set our sortable columns
		$sortable_columns = array(
			'ID'          => array( 'ID',          false ),
			'post_title'  => array( 'post_title',  false ),
			'post_author' => array( 'post_author', false ),
			'post_date'   => array( 'post_date',   false )
		);

		return apply_filters( 'easingslider_sliders_list_table_sortable_columns', $sortable_columns );

	}

	/**
	 * Gets the number of list items per page
	 *
	 * @return int
	 */
	public function get_per_page() {

		// Get the current page
		$current_screen = get_current_screen();

		// Get the number of items per page
		$per_page = (int) get_user_option( 'sliders_per_page' );

		// Return default if false
		if ( ! $per_page ) {
			return 20;
		}

		return $per_page;

	}

	/**
	 * Prepare our list table items
	 *
	 * @return void
	 */
	public function prepare_items() {

		// Process any outstanding actions
		$this->process_action();

		// Get our variables
		$columns          = $this->get_columns();
		$hidden_columns   = $this->get_hidden_columns();
		$sortable_columns = $this->get_sortable_columns();

		// Get our pagination variables
		$per_page     = $this->get_per_page();
		$current_page = $this->get_pagenum();

		// Our posts query arguments
		$query_args = array(
			'posts_per_page' => $per_page,
		);

		// If paginated, ammend our query
		if ( isset( $_GET['paged'] ) ) {
			$query_args['paged'] = $_GET['paged'];
		}

		// If searching, ammend our query
		if ( isset( $_GET['s'] ) ) {
			$query_args['s'] = $_GET['s'];
		}

		// Add sort queries
		$query_args['orderby'] = ( ! empty( $_REQUEST['orderby'] ) ) ? $_REQUEST['orderby'] : 'ID';
		$query_args['order']   = ( ! empty( $_REQUEST['order'] ) )   ? $_REQUEST['order']   : 'asc';

		// Query our sliders
		$query = ES_Slider::query( $query_args );

		// Set our pagination arguments
		$this->set_pagination_args( array(
			'total_items' => $query->found_posts,
			'per_page'    => $per_page
		) );

		// Set our column headers
		$this->_column_headers = array( $columns, $hidden_columns, $sortable_columns );

		// Assign our table items
		$this->items = $query->posts;

	}

	/**
	 * Our bulk actions for this list
	 *
	 * @return array
	 */
	public function get_bulk_actions() {

		$actions = array(
			'duplicate' => __( 'Duplicate', 'easingslider' ),
			'delete'    => __( 'Delete', 'easingslider' )
		);

		return apply_filters( 'easingslider_sliders_list_table_bulk_actions', $actions );

	}

	/**
	 * Processes an action, or a bulk action if appropriate.
	 *
	 * @return void
	 */
	public function process_action() {

		// Get the current action
		$action = $this->current_action();

		// Generate the method string
		$method = "process_{$action}_action";

		// Bail if this method doesn't exist
		if ( ! method_exists( $this, $method ) ) {
			return;
		}

		// Do either a single action or a bulk action
		if ( isset( $_GET['id'] ) ) {

			// Bail if nonce is invalid
			if ( ! check_admin_referer( $action ) ) {
				return;
			}

			// Call the action
			call_user_func( array( $this, $method ), $_GET['id'] );

		}
		else if ( isset( $_GET['ids'] ) ) {

			// Bail if nonce is invalid
			if ( ! check_admin_referer( "bulk-{$this->_args['plural']}" ) ) {
				return;
			}

			// Call the action for each ID
			array_walk( $_GET['ids'], array( $this, $method ) );

		}

	}

	/**
	 * Processes a duplicate action
	 *
	 * @param  int $id The slider ID
	 * @return void
	 */
	public function process_duplicate_action( $id ) {

		// Get the slider
		$slider = ES_Slider::find( $id );

		// Unset the slider ID
		unset( $slider->ID );

		// Append "- Copy" to the slider title
		$slider->post_title = sprintf( __( "%s - Copy", 'easingslider' ), $slider->post_title );

		// Save the slider, thus creating a new one as no ID is present.
		$slider->save();

	}

	/**
	 * Processes a delete action
	 *
	 * @param  int $id The slider ID
	 * @return void
	 */
	public function process_delete_action( $id ) {

		// Delete the slider
		ES_Slider::delete( $id );

	}

	/**
	 * Our checkbox column method
	 * 
	 * @param  object  $item Our column item
	 * @return string
	 */
	public function column_cb( $item ) {

		return sprintf( '<input type="checkbox" name="ids[]" value="%s" />', $item->ID );

	}

	/**
	 * Our post title column method
	 * 
	 * @param  object  $item Our column item
	 * @return string
	 */
	public function column_post_title( $item ) {

		// Our title string
		$title = sprintf( '<strong><a class="row-title" href="?page=%s&edit=%s">%s</a></strong>', $_GET['page'], $item->ID, $item->post_title );

		// Add nonces to action URLs
		$duplicate_link = wp_nonce_url( "?page={$_GET['page']}&action=duplicate&id={$item->ID}", 'duplicate' );
		$delete_link    = wp_nonce_url( "?page={$_GET['page']}&action=delete&id={$item->ID}", 'delete' );

		// Our array of actions
		$actions = array(
			'edit'      => sprintf( '<a href="?page=%s&edit=%s">Edit</a>', $_GET['page'], $item->ID ),
			'duplicate' => sprintf( '<a href="%s">Duplicate</a>', $duplicate_link ),
			'delete'    => sprintf( '<a href="%s">Delete</a>', $delete_link ),
		);

		return sprintf( '%1$s %2$s', $title, $this->row_actions( $actions ) );

	}

	/**
	 * Our post author column method
	 * 
	 * @param  object $item Our column item
	 * @return string
	 */
	public function column_post_author( $item ) {

		$author = sprintf(
			'<a href="%s">%s</a>', 
			esc_url( add_query_arg( array( 'page' => $_GET['page'], 'author' => get_the_author_meta( 'ID', $item->post_author ) ) ) ),
			get_the_author_meta( 'display_name', $item->post_author )
		);

		return sprintf( '%1$s', $author );

	}

	/**
	 * Our post date column method
	 *
	 * @param  object $item Our column item
	 * @return string
	 */
	public function column_post_date( $item ) {

		// Get the time strings
		$post_time = get_post_time( 'G', true, $item->ID );
		$abbr_time = get_post_time( 'Y/m/d g:i:s A', true, $item->ID );

		// Calculate the time difference from now
		$time_diff = time() - $post_time;

		// Get the appropriate time string
		if ( $time_diff > 0 && $time_diff < DAY_IN_SECONDS ) {

			// Return a pretty text string
			$date = sprintf( __( '%s ago', 'easingslider' ), human_time_diff( $post_time ) );

		}
		else {

			// Return a date, month and year string
			$date = mysql2date( __( 'Y/m/d' ), $item->post_date );

		}

		return sprintf( '<abbr title="%1$s">%2$s</abbr><br />%3$s', $abbr_time, $date, __( 'Published', 'easingslider' ) );

	}

	/**
	 * Our shortcode column method
	 *
	 * @param  object $item Our column item
	 * @return string
	 */
	public function column_shortcode( $item ) {

		return sprintf( '<code>[easingslider id="%s"]</code>', $item->ID );

	}

	/**
	 * Our default column methods
	 * 
	 * @param  array  $item        Our column item
	 * @param  string $column_name The column name
	 * @return string
	 */
	public function column_default( $item, $column_name ) {

		switch ( $column_name ) {
			default:
				return $item->$column_name;
		}
		
	}

	/**
	 * Our custom "No items" text
	 *
	 * @return void
	 */
	public function no_items() {

		// That's all folks!
		_e( 'No sliders found.', 'easingslider' );

	}

}