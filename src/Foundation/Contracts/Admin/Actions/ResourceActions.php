<?php

namespace EasingSlider\Foundation\Contracts\Admin\Actions;

/**
 * Exit if accessed directly
 */
if ( ! defined('ABSPATH')) {
	exit;
}

interface ResourceActions
{
	/**
	 * Action for creating a resource
	 *
	 * @param  array $data
	 * @return void
	 */
	public function create($data = array());

	/**
	 * Action for updating a resource
	 *
	 * @param  array $data
	 * @return void
	 */
	public function update($data = array());

	/**
	 * Action for duplicating a resource
	 *
	 * @param  array $data
	 * @return void
	 */
	public function duplicate($data = array());

	/**
	 * Action for trash a resource
	 *
	 * @param  array $data
	 * @return void
	 */
	public function trash($data = array());

	/**
	 * Action for untrashing a resource
	 *
	 * @param  array $data
	 * @return void
	 */
	public function untrash($data = array());

	/**
	 * Action for deleting a resource
	 *
	 * @param  array $data
	 * @return void
	 */
	public function delete($data = array());
}
