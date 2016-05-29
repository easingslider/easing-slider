<?php

namespace EasingSlider\Foundation\Admin\Actions;

use EasingSlider\Foundation\Contracts\Admin\Actions\Actions as ActionsContract;
use EasingSlider\Foundation\Contracts\Admin\Notices\NoticeHandler;

/**
 * Exit if accessed directly
 */
if ( ! defined('ABSPATH')) {
	exit;
}

abstract class Actions implements ActionsContract
{
	/**
	 * Notices
	 *
	 * @var \EasingSlider\Foundation\Contracts\Admin\Notices\NoticeHandler
	 */
	protected $notices;

	/**
	 * Constructor
	 *
	 * @param  \EasingSlider\Foundation\Contracts\Admin\Notices\NoticeHandler $notices
	 * @return void
	 */
	public function __construct(NoticeHandler $notices)
	{
		$this->notices = $notices;

		$this->defineActions();
	}

	/**
	 * Defines our actions
	 *
	 * @return void
	 */
	abstract protected function defineActions();

	/**
	 * Gets a new validator instance
	 *
	 * @return array
	 */
	abstract protected function validator();
}
