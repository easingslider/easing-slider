<?php

namespace EasingSlider\Plugin\Shortcodes;

use EasingSlider\Foundation\Contracts\Models\Model;
use EasingSlider\Foundation\Contracts\Repositories\Repository;
use EasingSlider\Foundation\Shortcodes\Shortcode;
use EasingSlider\Plugin\Views\Slider as SliderView;

/**
 * Exit if accessed directly
 */
if ( ! defined('ABSPATH')) {
	exit;
}

class Slider extends Shortcode
{
	/**
	 * Tag
	 *
	 * @var string
	 */
	protected $tag = 'easingslider';

	/**
	 * Sliders
	 *
	 * @var \EasingSlider\Foundation\Contracts\Repositories\Repository
	 */
	protected $sliders;

	/**
	 * Constructor
	 *
	 * @param \EasingSlider\Foundation\Contracts\Repositories\Repository $sliders
	 * @return void
	 */
	public function __construct(Repository $sliders)
	{
		$this->sliders = $sliders;

		parent::__construct();
	}

	/**
	 * Minifies the HTML output. Some plugins have a habit of
	 * parsing post HTML content, and injecting nasty `<code>` and `<pre>` tags into the markup.
	 * 
	 * This in turn breaks Easing Slider. Minifying the HTML can prevent this.
	 *
	 * @param  string $content
	 * @return string
	 */
	protected function minifyOutput($content)
	{
		$content = preg_replace('!/\*.*?\*/!s', '', $content);
        $content = preg_replace('/\n\s*\n/', "\n", $content);
        $content = str_replace(array("\r\n", "\r", "\t", "\n"), '', $content);

        return $content;
	}

	/**
	 * Renders the view for this shortcode
	 *
	 * @param  \EasingSlider\Foundation\Contracts\Models\Model $slider
	 * @return string
	 */
	protected function renderView(Model $slider)
	{
		// Start output buffer
		ob_start();

		// Display the slider
		$view = new SliderView($slider);
		$view->display();

		// Get content from output buffer
		$content = ob_get_clean();

		// Minify & return
		return $this->minifyOutput($content);
	}

	/**
	 * Renders the shortcode
	 *
	 * @param  array $atts
	 * @return string
	 */
	public function render($atts = array())
	{
		// Default shortcode attributes
		$defaults = apply_filters('easingslider_shortcode_defaults', array('id' => false));

		// Combine shortcode attributes with defaults
		$atts = (object) shortcode_atts($defaults, $atts);

		// Continue if we have an ID
		if ( ! empty($atts->id)) {

			// Find the slider
			$slider = $this->sliders->find($atts->id);

			// Continue if we have a slider
			if ($slider) {

				// Render and return view
				return $this->renderView($slider);

			} else {

				// Tell user no slider has been found (admins only)
				if (is_super_admin()) {
					return sprintf(__('<p><strong>The slider specified (ID #%d) could not be found.</strong></p>', 'easingslider'), $atts->id);
				}

			}

		}
	}
}
