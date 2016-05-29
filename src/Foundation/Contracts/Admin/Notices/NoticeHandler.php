<?php

namespace EasingSlider\Foundation\Contracts\Admin\Notices;

use EasingSlider\Foundation\Contracts\Admin\Notices\Notice;

/**
 * Exit if accessed directly
 */
if ( ! defined('ABSPATH')) {
	exit;
}

interface NoticeHandler
{
	/**
	 * Shows a success notice
	 *
	 * @param  string $handle
	 * @param  string $message
	 * @return void
	 */
	public function success($handle, $message);

	/**
	 * Shows an error notice
	 *
	 * @param  string $handle
	 * @param  string $message
	 * @return void
	 */
	public function error($handle, $message);

	/**
	 * Shows an info notice
	 *
	 * @param  string $handle
	 * @param  string $message
	 * @return void
	 */
	public function info($handle, $message);
}
