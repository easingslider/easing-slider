<?php

namespace EasingSlider\Foundation\Admin\Notices;

use EasingSlider\Foundation\Admin\Notices\ErrorNotice;
use EasingSlider\Foundation\Admin\Notices\InfoNotice;
use EasingSlider\Foundation\Admin\Notices\SuccessNotice;
use EasingSlider\Foundation\Contracts\Admin\Notices\NoticeHandler as NoticeHandlerContract;
use EasingSlider\Foundation\Contracts\Admin\Notices\Notice;

/**
 * Exit if accessed directly
 */
if ( ! defined('ABSPATH')) {
	exit;
}

class NoticeHandler implements NoticeHandlerContract
{
	/**
	 * Notices
	 *
	 * @var array
	 */
	protected $notices = array();

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
		add_action('admin_notices', array($this, 'displayNotices'));
	}

	/**
	 * Displays the notices
	 *
	 * @return void
	 */
	public function displayNotices()
	{
		foreach ($this->notices as $notice)
		{
			$notice->display();
		}
	}

	/**
	 * Pushes a notice
	 *
	 * @param  string  $type
	 * @param  string  $handle
	 * @param  string  $message
	 * @return void
	 */
	protected function pushNotice($type, $handle, $message)
	{
		switch ($type) {
			case 'error':
				$notice = new ErrorNotice($handle, $message);
				break;

			case 'info':
				$notice = new InfoNotice($handle, $message);
				break;

			case 'success':
				$notice = new SuccessNotice($handle, $message);
				break;
		}

		if (isset($notice)) {
			$this->notices[] = $notice;
		}
	}

	/**
	 * Shows a success notice
	 *
	 * @param  string  $handle
	 * @param  string  $message
	 * @return void
	 */
	public function success($handle, $message)
	{
		$this->pushNotice('success', $handle, $message);
	}

	/**
	 * Shows an error notice
	 *
	 * @param  string  $handle
	 * @param  string  $message
	 * @return void
	 */
	public function error($handle, $message)
	{
		$this->pushNotice('error', $handle, $message);
	}

	/**
	 * Shows an info notice
	 *
	 * @param  string  $handle
	 * @param  string  $message
	 * @return void
	 */
	public function info($handle, $message)
	{
		$this->pushNotice('info', $handle, $message);
	}
}
