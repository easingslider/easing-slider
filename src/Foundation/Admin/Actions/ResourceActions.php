<?php

namespace EasingSlider\Foundation\Admin\Actions;

use EasingSlider\Foundation\Admin\Actions\Actions;
use EasingSlider\Foundation\Contracts\Admin\Actions\ResourceActions as ResourceActionsContract;
use EasingSlider\Foundation\Contracts\Admin\Notices\NoticeHandler;
use EasingSlider\Foundation\Contracts\Repositories\Repository;

/**
 * Exit if accessed directly
 */
if ( ! defined('ABSPATH')) {
	exit;
}

abstract class ResourceActions extends Actions implements ResourceActionsContract
{
	/**
	 * Repository
	 *
	 * @var \EasingSlider\Foundation\Contracts\Repositories\Repository
	 */
	protected $repository;

	/**
	 * Action Suffix
	 *
	 * @var string
	 */
	protected $actionSuffix;

	/**
	 * Permissions
	 *
	 * @var array
	 */
	protected $permissions;

	/**
	 * Messages
	 *
	 * @var array
	 */
	protected $messages;

	/**
	 * Constructor
	 * 
	 * @param  \EasingSlider\Foundation\Contracts\Repositories\Repository     $repository
	 * @param  \EasingSlider\Foundation\Contracts\Admin\Notices\NoticeHandler $notices
	 * @return void
	 */
	public function __construct(Repository $repository, NoticeHandler $notices)
	{
		$this->repository = $repository;

		$this->permissions = $this->getPermissions();
		$this->messages = $this->getMessages();

		parent::__construct($notices);
	}

	/**
	 * Defines our actions
	 *
	 * @return void
	 */
	protected function defineActions()
	{
		add_action("easingslider_create_{$this->actionSuffix}", array($this, 'create'));
		add_action("easingslider_update_{$this->actionSuffix}", array($this, 'update'));
		add_action("easingslider_duplicate_{$this->actionSuffix}", array($this, 'duplicate'));
		add_action("easingslider_trash_{$this->actionSuffix}", array($this, 'trash'));
		add_action("easingslider_untrash_{$this->actionSuffix}", array($this, 'untrash'));
		add_action("easingslider_delete_{$this->actionSuffix}", array($this, 'delete'));
	}

	/**
	 * Does necessary security checks
	 *
	 * @param  string $referer
	 * @return boolean
	 */
	protected function securityChecks($referer)
	{
		// Checks the admin referer, then do a permission check.
		if (check_admin_referer($referer) && current_user_can($this->permissions[$referer])) {
			return true;
		}

		return false;
	}

	/**
	 * Action for creating a resource
	 *
	 * @param  array $data
	 * @return void
	 */
	public function create($data = array())
	{
		if ($this->securityChecks('create')) {

			// Validate the data
			$data = $this->validator()->validate($data);

			// Create the model
			$model = $this->repository->create($data);

			// Redirect to the editor after creating the new resource
			$this->creationRedirect($model->ID);

		}
	}

	/**
	 * Action for updating a resource
	 *
	 * @param  array $data
	 * @return void
	 */
	public function update($data = array())
	{
		if ($this->securityChecks('update')) {

			// Validate the data
			$data = $this->validator()->validate($data);

			// Update the model
			$this->repository->update($data['id'], $data);

			// Tell the user
			$this->notices->success("{$this->actionSuffix}_updated", $this->messages['updated']);

		}
	}

	/**
	 * Action for duplicating a resource
	 *
	 * @param  array $data
	 * @return void
	 */
	public function duplicate($data = array())
	{
		if ($this->securityChecks('duplicate')) {

			// Validate the data
			$data = $this->validator()->validate($data);

			// Duplicate the model
			$this->repository->duplicate($data['id']);

			// Tell the user
			$this->notices->success("{$this->actionSuffix}_duplicated", $this->messages['duplicated']);

		}
	}

	/**
	 * Action for trash a resource
	 *
	 * @param  array $data
	 * @return void
	 */
	public function trash($data = array())
	{
		if ($this->securityChecks('trash')) {

			// Validate the data
			$data = $this->validator()->validate($data);

			// Trash the model
			$this->repository->trash($data['id']);

			// Tell the user
			$this->notices->success("{$this->actionSuffix}_trashed", $this->messages['trashed']);

		}
	}

	/**
	 * Action for untrashing a resource
	 *
	 * @param  array $data
	 * @return void
	 */
	public function untrash($data = array())
	{
		if ($this->securityChecks('untrash')) {

			// Validate the data
			$data = $this->validator()->validate($data);

			// Untrash the model
			$this->repository->untrash($data['id']);

			// Tell the user
			$this->notices->success("{$this->actionSuffix}_untrashed", $this->messages['untrashed']);

		}
	}

	/**
	 * Action for deleting a resource
	 *
	 * @param  array $data
	 * @return void
	 */
	public function delete($data = array())
	{
		if ($this->securityChecks('delete')) {

			// Validate the data
			$data = $this->validator()->validate($data);

			// Delete the model
			$this->repository->delete($data['id']);

			// Tell the user
			$this->notices->success("{$this->actionSuffix}_deleted", $this->messages['deleted']);

		}
	}
	/**
	 * Gets the permissions for our security checks
	 *
	 * @return array
	 */
	abstract protected function getPermissions();

	/**
	 * Gets the messages for our notices
	 *
	 * @return array
	 */
	abstract protected function getMessages();

	/**
	 * Redirects to the resource editor after creation
	 *
	 * @param  int $id
	 * @return void
	 */
	abstract protected function creationRedirect($id);
}
