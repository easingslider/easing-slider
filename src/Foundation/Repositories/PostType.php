<?php

namespace EasingSlider\Foundation\Repositories;

use EasingSlider\Foundation\Contracts\Repositories\Repository as RepositoryContract;
use stdClass;
use WP_Post;
use WP_Query;

/**
 * Exit if accessed directly
 */
if ( ! defined('ABSPATH')) {
	exit;
}

abstract class PostType implements RepositoryContract
{
	/**
	 * Post Type
	 *
	 * @var string
	 */
	protected $postType;

	/**
	 * Meta Key
	 *
	 * @var string
	 */
	protected $metaKey;

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
	protected function defineHooks()
	{
		add_action('init', array($this, 'registerPostType'));
	}

	/**
	 * Get all models
	 *
	 * @param  string $status
	 * @return array
	 */
	public function all($status = 'all')
	{
		$models = array();

		// Get our posts
		$posts = $this->queryPosts(array('post_status' => $status))->get_posts();

		// Create a new model instance for each post and add to results
		foreach ($posts as $post) {
			$models[] = $this->make($post);
		}

		return $models;
	}

	/**
	 * Finds a model
	 *
	 * @param  int $id
	 * @return \EasingSlider\Foundation\Models\ModelContract|false
	 */
	public function find($id)
	{
		$post = get_post($id, ARRAY_A);
		$postMeta = $this->getPostMeta($id);

		// Bail if no post or metadata were found
		if ( ! $post || ! $postMeta || 'trash' == $post['post_status']) {
			return false;
		}

		// Bail if not our post type
		if ($post['post_type'] != $this->postType) {
			return false;
		}
		
		// Initiate a model instance with data
		$model = $this->make(array_merge((array) $post, (array) $postMeta));

		return $model;
	}

	/**
	 * Stores a new model in the database
	 *
	 * @param  array $data
	 * @return \EasingSlider\Foundation\Models\ModelContract|false
	 */
	public function create($data = array())
	{
		// Merge data with defaults
		$data = array_merge($this->defaultPostData(), $data);

		// Create the post
		$id = wp_insert_post($this->purgeMetaData($data));

		// Bail on failure
		if ( ! $id) {
			return false;
		}

		// Add the metadata
		$this->addPostMeta($id, $this->purgePostData($data));

		return $this->find($id);
	}

	/**
	 * Updates a model in the databse
	 *
	 * @param  int   $id
	 * @param  array $data
	 * @return \EasingSlider\Foundation\Models\ModelContract|false
	 */
	public function update($id, $data = array())
	{
		// Update the post
		$id = wp_update_post(array_merge(array('ID' => $id), $this->purgeMetaData($data)));

		// Bail on failure
		if ( ! $id) {
			return false;
		}

		// Update the metadata
		$this->updatePostMeta($id, $this->purgePostData($data));

		return $this->find($id);
	}

	/**
	 * Duplicates a model
	 *
	 * @param  int $id
	 * @return \EasingSlider\Foundation\Models\ModelContract|false
	 */
	public function duplicate($id)
	{
		// Get the model
		$model = $this->find($id);

		// Bail if it doesn't exist
		if ( ! $model) {
			return false;
		}

		// Get the attributes
		$attributes = $model->getAttributes();

		// Remove ID
		unset($attributes['ID']);

		// Create & return the new model
		return $this->create($attributes);
	}

	/**
	 * Trashes a model 
	 *
	 * @param  int   $id
	 * @return \EasingSlider\Foundation\Models\ModelContract|false
	 */
	public function trash($id)
	{
		// Trash the post
		$id = wp_trash_post($id);

		// Bail on failure
		if ( ! $id) {
			return false;
		}

		return $this->find($id);
	}

	/**
	 * Untrashes a model
	 *
	 * @param  int   $id
	 * @return \EasingSlider\Foundation\Models\ModelContract|false
	 */
	public function untrash($id)
	{
		// Untrash the post
		$id = wp_untrash_post($id);

		// Bail on failure
		if ( ! $id) {
			return false;
		}

		return $this->find($id);
	}

	/**
	 * Deletes a model permanently
	 *
	 * @param  int   $id
	 * @return boolean
	 */
	public function delete($id)
	{
		// Delete the post
		$post = wp_delete_post($id);

		// Bail if failed
		if ( ! $post) {
			return false;
		}

		// Delete the metadata
		$this->deletePostMeta($id);

		return true;
	}

	/**
	 * Gets the total model count
	 *
	 * @param  string $status
	 * @return int
	 */
	public function total($status = 'publish')
	{
		$postCount = wp_count_posts($this->postType);

		if ( ! empty($postCount->$status)) {
			return $postCount->$status;
		} else {
			return 0;
		}
	}

	/**
	 * Makes a new model instance
	 *
	 * @param  array $data
	 * @return \EasingSlider\Foundation\Models\ModelContract
	 */
	public function make($data = array())
	{
		$model = $this->newModel();
		$model->fill(array_merge($this->defaultPostData(), (array) $data));

		return $model;
	}

	/**
	 * Queries the post type
	 *
	 * @param  array $arguments
	 * @return \WP_Query
	 */
	public function queryPosts($arguments = array())
	{
		return new WP_Query(array_merge($this->defaultQueryArguments(), (array) $arguments));
	}

	/**
	 * Registers our post type
	 *
	 * @return void
	 */
	public function registerPostType()
	{
		register_post_type($this->postType, array(
			'query_var'           => false,
			'rewrite'             => false,
			'public'              => true,
			'exclude_from_search' => true,
			'publicly_queryable'  => false,
			'show_in_nav_menus'   => false,
			'show_ui'             => false,
			'labels'              => array('name' => $this->getPostTypeLabel())
		));
	}

	/**
	 * Gets the default query arguments
	 *
	 * @return array
	 */
	protected function defaultQueryArguments()
	{
		return array(
			'post_status'    => 'publish',
			'post_type'      => $this->postType,
			'orderby'        => 'ID',
			'order'          => 'asc',
			'posts_per_page' => -1
		);
	}

	/**
	 * Gets the default post data
	 *
	 * @return array
	 */
	protected function defaultPostData()
	{
		return array(
			'post_title'  => '(no title)',
			'post_status' => 'publish',
			'post_type'   => $this->postType,
		);
	}

	/**
	 * Gets the whitelist of post data attributes
	 *
	 * @return array
	 */
	protected function getPostDataWhitelist()
	{
		$whitelist = array();

		// Get a new post instance
		$post = new WP_Post(new stdClass);

		// Add columns as whitelisted attributes
		foreach ($post->to_array() as $key => $value) {
			$whitelist[] = $key;
		}

		return $whitelist;
	}

	/**
	 * Gets the whitelist of metadata attributes
	 *
	 * @return array
	 */
	protected function getMetaDataWhitelist()
	{
		$whitelist = array();

		// Get a new model instance
		$modelAttributes = $this->newModel()->getAttributes();

		// Add attributes to whitelist
		foreach ($modelAttributes as $key => $value) {
			$whitelist[] = $key;
		}

		return $whitelist;
	}

	/**
	 * Purges any post related data from the input data provided
	 *
	 * @param  array $data
	 * @return array
	 */
	protected function purgePostData($data)
	{
		return $this->purgeRedundantData($data, $this->getMetaDataWhitelist());
	}

	/**
	 * Purges any metadata from the input data provided
	 * 
	 * @param  array $data
	 * @return array
	 */
	protected function purgeMetaData($data)
	{
		return $this->purgeRedundantData($data, $this->getPostDataWhitelist());
	}

	/**
	 * Purges redundant data from the provided input data
	 *
	 * @param  array $data
	 * @param  array $whitelist
	 * @return array
	 */
	protected function purgeRedundantData($data, $whitelist)
	{
		foreach ($data as $key => $value) {
			if ( ! in_array($key, $whitelist)) {
				unset($data[$key]);
			}
		}

		return $data;
	}

	/**
	 * Gets the metadata for a post
	 *
	 * @param  int $id
	 * @return array
	 */
	protected function getPostMeta($id)
	{
		return get_post_meta($id, $this->metaKey, true);
	}

	/**
	 * Adds the metadata to a post
	 *
	 * @param  int   $id
	 * @param  array $data
	 * @return int
	 */
	protected function addPostMeta($id, $data = array())
	{
		return add_post_meta($id, $this->metaKey, $data);
	}

	/**
	 * Updates the metadata for a post
	 *
	 * @param  int   $id
	 * @param  array $data
	 * @return int
	 */
	protected function updatePostMeta($id, $data = array())
	{
		return update_post_meta($id, $this->metaKey, $data);
	}

	/**
	 * Deletes the metadata for a post
	 *
	 * @param int $id
	 * @return void
	 */
	protected function deletePostMeta($id)
	{
		return delete_post_meta($id, $this->metaKey);
	}

	/**
	 * Gets the post type label
	 *
	 * @return string
	 */
	abstract protected function getPostTypeLabel();

	/**
	 * Gets a new model instance
	 *
	 * @return \EasingSlider\Foundation\Models\ModelContract
	 */
	abstract protected function newModel();
}
