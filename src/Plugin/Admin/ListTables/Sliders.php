<?php

namespace EasingSlider\Plugin\Admin\ListTables;

use EasingSlider\Foundation\Admin\ListTables\ListTable;
use EasingSlider\Foundation\Contracts\Repositories\Repository;
use EasingSlider\Foundation\Contracts\Shortcodes\Shortcode;

/**
 * Exit if accessed directly
 */
if ( ! defined('ABSPATH')) {
	exit;
}

class Sliders extends ListTable
{
	/**
	 * Shortcode
	 *
	 * @var \EasingSlider\Foundation\Contracts\Shortcodes\Shortcode
	 */
	protected $shortcode;

	/**
	 * Sliders
	 *
	 * @var \EasingSlider\Foundation\Contracts\Repositories\Repository
	 */
	protected $sliders;

	/**
	 * Page Slug
	 *
	 * @var string|false
	 */
	protected $page;

	/**
	 * Constructor
	 *
	 * @param  \EasingSlider\Foundation\Contracts\Repositories\Repository $sliders
	 * @param  \EasingSlider\Foundation\Contracts\Shortcodes\Shortcode    $shortcode
	 * @return void
	 */
	public function __construct(Repository $sliders, Shortcode $shortcode)
	{
		$this->sliders = $sliders;
		$this->shortcode = $shortcode;

		// Get page
		$this->page = $this->getPage();

		// Call parent
		parent::__construct( array(
			'singular' => 'slider',
			'plural'   => 'sliders',
			'ajax'     => false
		));
	}

	/**
	 * Gets the page slug
	 *
	 * @return string|false
	 */
	protected function getPage()
	{
		if ( ! empty($_GET['page'])) {
			return $_GET['page'];
		}

		return false;
	}

	/**
	 * Show the search field
	 *
	 * @param string $text     Label for the search box
	 * @param string $input_id ID of the search box
	 * @return void
	 */
	public function search_box( $text, $input_id ) {

		?>
			<p class="search-box">
				<?php if ( ! empty( $_GET['status'] ) ) : ?>
					<input type="hidden" name="status" value="<?php echo esc_attr( $_GET['status'] ); ?>">
				<?php endif; ?>
				<label class="screen-reader-text" for="<?php echo "{$input_id}-search-input"; ?>"><?php echo $text; ?>:</label>
				<input type="search" id="<?php echo "{$input_id}-search-input"; ?>" name="s" value="<?php _admin_search_query(); ?>" />
				<?php submit_button( $text, 'button', false, false, array( 'ID' => 'search-submit' ) ); ?>
			</p>
		<?php

	}

	/**
	 * Get our list table views
	 *
	 * @return array
	 */
	public function get_views() {

		// Get current status
		$current_status = ( isset( $_GET['status'] ) ) ? $_GET['status'] : 'all';

		// Generate the counts
		$total_count   = $this->sliders->total();
		$publish_count = $this->sliders->total( 'publish' );
		$trash_count   = $this->sliders->total( 'trash' );

		// Populate the view links
		$views = array(
			'all'     => sprintf( '<a href="%s"%s>%s</a>', remove_query_arg( 'status' ), ( ! isset( $_GET['s'] ) && 'all' === $current_status || '' === $current_status ) ? ' class="current"' : '', __( 'All', 'easingslider' ) . '&nbsp;<span class="count">('. $total_count .')</span>' ),
			'publish' => sprintf( '<a href="%s"%s>%s</a>', add_query_arg( 'status', 'publish' ), 'publish' === $current_status ? ' class="current"' : '', __( 'Published', 'easingslider' ) . '&nbsp;<span class="count">('. $publish_count .')</span>' ),
		);

		// Add trashed view only if we have trashed sliders
		if ( $trash_count > 0 ) {
			$views['trash'] = sprintf( '<a href="%s"%s>%s</a>', add_query_arg( 'status', 'trash' ), 'trash' === $current_status ? ' class="current"' : '', __( 'Trashed', 'easingslider' ) . '&nbsp;<span class="count">('. $trash_count .')</span>' );
		}

		return apply_filters( 'easingslider_sliders_list_table_views', $views );		

	}

	/**
	 * Get our list table columns
	 * 
	 * @return array
	 */
	public function get_columns() {

		// Set our columns
		$columns = array(
			'cb'                => '<input type="checkbox" />',
			'ID'                => __( 'ID', 'easingslider' ),
			'post_title'        => __( 'Title', 'easingslider' ),
			'post_author'       => __( 'Author', 'easingslider' ),
			'post_date'         => __( 'Date', 'easingslider' ),
			'shortcode'         => __( 'Shortcode', 'easingslider' ),
			'template_function' => __( 'Template Function', 'easingslider' )
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

		// Process any outstanding bulk actions
		$this->process_bulk_action();

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

		// Add post status
		if ( isset( $_GET['status'] ) ) {
			$query_args['post_status'] = sanitize_key( $_GET['status'] );
		}

		// If paginated, ammend our query
		if ( isset( $_GET['paged'] ) ) {
			$query_args['paged'] = sanitize_key( $_GET['paged'] );
		}

		// If searching, ammend our query
		if ( isset( $_GET['s'] ) ) {
			$query_args['s'] = sanitize_title( $_GET['s'] );
		}

		// Add sort queries
		$query_args['orderby'] = ( ! empty( $_REQUEST['orderby'] ) ) ? $_REQUEST['orderby'] : 'ID';
		$query_args['order']   = ( ! empty( $_REQUEST['order'] ) )   ? $_REQUEST['order']   : 'asc';

		// Query our sliders
		$query = $this->sliders->queryPosts( $query_args );

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

		$actions = array();

		// Vary actions based on post status
		if ( isset( $_GET['status'] ) && 'trash' == $_GET['status'] ) {
			$actions['untrash'] = __( 'Restore', 'easingslider' );
			$actions['delete']  = __( 'Delete Permanently', 'easingslider' );
		}
		else {
			$actions['duplicate'] = __( 'Duplicate', 'easingslider' );
			$actions['trash']     = __( 'Move to Trash', 'easingslider' );
		}

		return apply_filters( 'easingslider_sliders_list_table_bulk_actions', $actions );

	}

	/**
	 * Processes a bulk action, or a bulk action if appropriate.
	 *
	 * @return void
	 */
	public function process_bulk_action() {

		// Get the current action
		$action = $this->current_action();

		// Continue if we have IDs and an action
		if ( $action && isset( $_GET['ids'] ) ) {

			// Get the IDs
			$ids = array_map( 'sanitize_key', $_GET['ids'] );

			// Process action in bulk
			foreach ( $ids as $id ) {
				switch ( $action ) {
					case 'duplicate':
						$this->sliders->duplicate( $id );
						break;

					case 'trash':
						$this->sliders->trash( $id );
						break;

					case 'untrash':
						$this->sliders->untrash( $id );
						break;

					case 'delete':
						$this->sliders->delete( $id );
						break;
				}
			}

		}

		/*
		// Continue if callback exists
		if ( function_exists( $callback ) ) {

			// Do either a single action or a bulk action
			if ( isset( $_GET['id'] ) ) {

				// Bail if nonce is invalid
				if ( ! check_admin_referer( $action ) ) {
					return;
				}

				// Call the action
				call_user_func( $callback, sanitize_key( $_GET['id'] ) );

			}
			elseif ( isset( $_GET['ids'] ) ) {

				// Bail if nonce is invalid
				if ( ! check_admin_referer( "bulk-{$this->_args['plural']}" ) ) {
					return;
				}

				// Sanatize IDs
				$ids = array_map( 'sanitize_key', $_GET['ids'] );

				// Call the action for each ID
				array_walk( $ids, $callback );

			}

		}
		*/

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

		$actions = array();

		// Vary actions based on post status
		if ( isset( $_GET['status'] ) && 'trash' === $_GET['status'] ) {

			// Title string
			$title = sprintf( '<strong>%s</strong>', $item->post_title );

			// Add nonces to action URLs
			$untrash_link = wp_nonce_url( "?page={$this->page}&easingslider_action=untrash_slider&id={$item->ID}&easingslider_notice=untrash_slider", 'untrash' );
			$delete_link  = wp_nonce_url( "?page={$this->page}&easingslider_action=delete_slider&id={$item->ID}&easingslider_notice=delete_slider", 'delete' );

			// Add our actions
			$actions['untrash'] = sprintf( '<a href="%s">Restore</a>', $untrash_link );
			$actions['delete']  = sprintf( '<a href="%s">Delete Permanently</a>', $delete_link );

		}
		else {

			// Title string
			$title = sprintf( '<strong><a class="row-title" href="?page=%s&edit=%s">%s</a></strong>', $this->page, $item->ID, $item->post_title );

			// Add nonces to action URLs
			$duplicate_link = wp_nonce_url( "?page={$this->page}&easingslider_action=duplicate_slider&id={$item->ID}&easingslider_notice=duplicate_slider", 'duplicate' );
			$trash_link     = wp_nonce_url( "?page={$this->page}&easingslider_action=trash_slider&id={$item->ID}&easingslider_notice=trash_slider", 'trash' );

			// Add our actions
			$actions['edit']      = sprintf( '<a href="?page=%s&edit=%s">Edit</a>', $this->page, $item->ID );
			$actions['duplicate'] = sprintf( '<a href="%s">Duplicate</a>', $duplicate_link );
			$actions['trash']     = sprintf( '<a href="%s">Trash</a>', $trash_link );

		}

		// Filter the actions
		$actions = apply_filters( 'easingslider_sliders_list_table_post_title_actions', $actions );

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
			esc_url( add_query_arg( array( 'page' => $this->page, 'author' => get_the_author_meta( 'ID', $item->post_author ) ) ) ),
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

		return sprintf( '%1$s<br><abbr title="%2$s">%3$s</abbr>', __( 'Published', 'easingslider' ), $abbr_time, $date );

	}

	/**
	 * Our shortcode column method
	 *
	 * @param  object $item Our column item
	 * @return string
	 */
	public function column_shortcode( $item ) {

		$shortcode = sprintf( esc_html( "[{$this->shortcode->tag()} id=\"%d\"]" ), $item->ID );

		return sprintf( '<input type="text" readonly="readonly" value="%s" />', $shortcode );

	}

	/**
	 * Our template function column method
	 *
	 * @param  object $item Our column item
	 * @return string
	 */
	public function column_template_function( $item ) {

		$template_function = sprintf( esc_html( '<?php if ( function_exists( \'easingslider\' ) ) { easingslider( %d ); } ?>' ), $item->ID );

		return sprintf( '<input type="text" readonly="readonly" value="%s" />', $template_function );

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
