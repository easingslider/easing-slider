<?php

namespace EasingSlider\Foundation\Admin\ListTables;

use EasingSlider\Foundation\Contracts\Admin\ListTables\ListTable as ListTableContract;
use WP_List_Table;

/**
 * Exit if accessed directly
 */
if ( ! defined('ABSPATH')) {
	exit;
}

/**
 * Ensure WP_List_Table class has been loaded
 */
if ( ! class_exists( 'WP_List_Table' ) ) {
	require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

abstract class ListTable extends WP_List_Table implements ListTableContract
{
	//
}
