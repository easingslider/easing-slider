<?php

namespace EasingSlider\Plugin\Admin\Actions;

use EasingSlider\Foundation\Admin\Actions\ResourceActions;
use EasingSlider\Plugin\Admin\Validators\Slider as SliderValidator;

/**
 * Exit if accessed directly
 */
if ( ! defined('ABSPATH')) {
	exit;
}

class Sliders extends ResourceActions
{
	/**
	 * Action Suffix
	 *
	 * @var string
	 */
	protected $actionSuffix = 'slider';

	/**
	 * Gets the permissions for our security checks
	 *
	 * @return array
	 */
	protected function getPermissions()
	{
		return apply_filters('easingslider_admin_slider_permissions', array(
			'create'    => 'easingslider_publish_sliders',
			'update'    => 'easingslider_edit_sliders',
			'duplicate' => 'easingslider_duplicate_sliders',
			'trash'     => 'easingslider_delete_sliders',
			'untrash'   => 'easingslider_delete_sliders',
			'delete'    => 'easingslider_delete_sliders'
		));
	}

	/**
	 * Gets the messages for our notices
	 *
	 * @return array
	 */
	protected function getMessages()
	{
		return apply_filters('easingslider_admin_slider_messages', array(
			'created'    => __('Slider has been published successfully.', 'easingslider'),
			'updated'    => __('Slider has been saved successfully.', 'easingslider'),
			'duplicated' => __('Slider(s) duplicated successfully', 'easingslider'),
			'trashed'    => __('Slider(s) moved to the Trash.', 'easingslider'),
			'untrashed'  => __('Slider(s) restored successfully.', 'easingslider'),
			'deleted'    => __('Slider(s) deleted permanently.', 'easingslider')
		));
	}

	/**
	 * Redirects to the resource editor after creation
	 *
	 * @param  int $id
	 * @return void
	 */
	protected function creationRedirect($id)
	{
		$redirectUrl = admin_url(sprintf('admin.php?page=easingslider&edit=%d&easingslider_notice=publish_slider', $id));

		wp_safe_redirect($redirectUrl);
		exit();
	}

	/**
	 * Gets a new validator instance
	 *
	 * @return array
	 */
	protected function validator()
	{
		return new SliderValidator();
	}
}
