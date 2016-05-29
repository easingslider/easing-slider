<?php

namespace EasingSlider\Foundation\Admin\Notices;

use EasingSlider\Foundation\Admin\Notices\Notice;

/**
 * Exit if accessed directly
 */
if ( ! defined('ABSPATH')) {
	exit;
}

class ErrorNotice extends Notice
{
	/**
	 * Provides the notice class names
	 *
	 * @return array
	 */
	protected function classNames()
	{
		return array('message', 'error');
	}
}
