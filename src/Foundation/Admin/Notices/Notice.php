<?php

namespace EasingSlider\Foundation\Admin\Notices;

use EasingSlider\Foundation\Contracts\Admin\Notices\Notice as NoticeContract;

/**
 * Exit if accessed directly
 */
if ( ! defined('ABSPATH')) {
	exit;
}

abstract class Notice implements NoticeContract
{
	/**
	 * Handle
	 *
	 * @var string
	 */
	protected $handle;

	/**
	 * Message
	 *
	 * @var string
	 */
	protected $message;

	/**
	 * Constructor
	 *
	 * @param  string  $handle
	 * @param  string  $message
	 * @return void
	 */
	public function __construct($handle, $message)
	{
		$this->handle = $handle;
		$this->message = $message;
	}

	/**
	 * Gets the notice class names
	 *
	 * @return string
	 */
	protected function getClassNames()
	{
		return implode(' ', $this->classNames());
	}

	/**
	 * Displays the notice
	 *
	 * @return void
	 */
	public function display()
	{
		$classNames = $this->getClassNames();

		?>
			<div class="<?php echo esc_attr($classNames); ?>">
				<p><?php echo wp_kses_post($this->message); ?></p>
			</div>
		<?php
	}

	/**
	 * Provides the notice class names
	 *
	 * @return array
	 */
	abstract protected function classNames();
}
