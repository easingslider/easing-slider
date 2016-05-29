<?php

namespace EasingSlider\Foundation\Contracts\Admin\Ajax;

/**
 * Exit if accessed directly
 */
if ( ! defined('ABSPATH')) {
	exit;
}

interface Router
{
	/**
	 * Sets an ajax route action
	 *
	 * @param  string       $action
	 * @param  string|array $callback
	 * @param  boolean      $requiresAuth
	 * @return void
	 */
	public function setAction($action, $callback, $requiresAuth = true);
}
