<?php

/**
 * Exit if accessed directly
 */
if ( ! defined('ABSPATH')) {
	exit;
}

if ( ! function_exists('easingslider_is_admin')) {
	/**
	 * Checks if the current page is one of our admin pages
	 *
	 * @return boolean
	 */
	function easingslider_is_admin()
	{
		if (function_exists('get_current_screen')) {

			// Get the current scren
			$currentScreen = get_current_screen();

			// Check current screen ID contains 'easingslider'
			if (isset($currentScreen->id) && false !== strpos($currentScreen->id, 'easingslider')) {
				return true;
			}

		}

		return false;
	}
}

if ( ! function_exists('easingslider_get_admin_page')) {
	/**
	 * Gets the current admin page slug
	 *
	 * @return string|false
	 */
	function easingslider_get_admin_page()
	{
		if (isset($_GET['page'])) {
			return sanitize_key($_GET['page']);
		}

		return false;
	}
}

if ( ! function_exists('easingslider_get_admin_page_id')) {
	/**
	 * Gets the current admin page ID
	 *
	 * @return int|false
	 */
	function easingslider_get_admin_page_id()
	{
		if (isset($_GET['edit'])) {
			return absint($_GET['edit']);
		}

		return false;
	}
}

if ( ! function_exists('easingslider_get_admin_base_url')) {
	/**
	 * Gets the admin base url
	 *
	 * @return string|false
	 */
	function easingslider_get_admin_base_url()
	{
		$baseUrl = parse_url(self_admin_url(), PHP_URL_PATH) . 'admin.php';

		// Get parameters
		$page = easingslider_get_admin_page();
		$pageID = easingslider_get_admin_page_id();

		// Bail if we don't have a page
		if ( ! $page) {
			return false;
		}

		// Get the URL with page parameter
		$baseUrl = add_query_arg('page', $page, $baseUrl);

		// Add optional page ID
		if ($pageID) {
			$baseUrl = add_query_arg('edit', $pageID, $baseUrl);
		}

		return esc_url(str_replace('/wp-admin/', '', $baseUrl));
	}
}

if ( ! function_exists('easingslider_locate_template')) {
	/**
	 * Copy of WordPress's `load_template` function, with alterations to allow for data insertion.
	 *
	 * @param array     $data           The data to be inserted.
	 * @param string    $_template_file Path to template file.
	 * @param bool      $require_once   Whether to require_once or require. Default true.
	 */
	function easingslider_load_template($data, $_template_file, $require_once = true)
	{
		global $posts, $post, $wp_did_header, $wp_query, $wp_rewrite, $wpdb, $wp_version, $wp, $id, $comment, $user_ID;

		if (is_array($data)) {
			extract($data);
		}

		if (is_array($wp_query->query_vars)) {
			extract($wp_query->query_vars, EXTR_SKIP);
		}

		if (isset($s)) {
			$s = esc_attr($s);
		}

		if ($require_once) {
			require_once($_template_file);
		} else {
			require($_template_file);
		}
	}
}
