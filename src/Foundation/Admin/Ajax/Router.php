<?php

namespace EasingSlider\Foundation\Admin\Ajax;

use EasingSlider\Foundation\Admin\Admin;

/**
 * Exit if accessed directly
 */
if ( ! defined('ABSPATH')) {
	exit;
}

abstract class Router
{
	/**
	 * Constructor
	 *
	 * @return void
	 */
	public function __construct()
	{
		$this->defineHooks();
	}

	/**
	 * Define hooks
	 *
	 * @return void
	 */
	abstract protected function defineHooks();

	/**
	 * Sets an ajax route action
	 *
	 * @param  string       $action
	 * @param  string|array $callback
	 * @param  boolean      $requiresAuth
	 * @return void
	 */
	public function setAction($action, $callback, $requiresAuth = true)
	{
		add_action("wp_ajax_{$action}", $callback);

		if (false === $requiresAuth) {
			add_action("wp_ajax_nopriv_{$action}", $callback);
		}
	}

	/**
	 * Provides a response
	 *
	 * @param  mixed $data
	 * @return void
	 */
	protected function response($data)
	{
		echo json_encode($data);
		die();
	}
}
