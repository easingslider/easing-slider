<?php

namespace EasingSlider\Foundation\Contracts\Repositories;

/**
 * Exit if accessed directly
 */
if ( ! defined('ABSPATH')) {
	exit;
}

interface Repository
{
	/**
	 * Get all models
	 *
	 * @return array
	 */
	public function all();

	/**
	 * Finds a model
	 *
	 * @param  int $id
	 * @return \EasingSlider\Foundation\Models\ModelContract
	 */
	public function find($id);

	/**
	 * Stores a new model in the database
	 *
	 * @param  array $data
	 * @return int|false
	 */
	public function create($data = array());

	/**
	 * Updates a model in the databse
	 *
	 * @param  int   $id
	 * @param  array $data
	 * @return int|false
	 */
	public function update($id, $data = array());

	/**
	 * Duplicates a model
	 *
	 * @param  int $id
	 * @return int|false
	 */
	public function duplicate($id);

	/**
	 * Trashes a model 
	 *
	 * @param  int   $id
	 * @return int|false
	 */
	public function trash($id);

	/**
	 * Untrashes a model
	 *
	 * @param  int   $id
	 * @return object|false
	 */
	public function untrash($id);

	/**
	 * Deletes a model permanently
	 *
	 * @param  int   $id
	 * @return int|false
	 */
	public function delete($id);

	/**
	 * Gets the total model count
	 *
	 * @param  string $status
	 * @return int
	 */
	public function total($status = 'publish');

	/**
	 * Makes a new model instance
	 *
	 * @param  array $data
	 * @return \EasingSlider\Foundation\Models\ModelContract
	 */
	public function make($data = array());
}
