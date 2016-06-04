<?php

namespace EasingSlider\Foundation\Admin\Menus;

use EasingSlider\Foundation\Contracts\Admin\Menus\Menu as MenuContract;

/**
 * Exit if accessed directly
 */
if ( ! defined('ABSPATH')) {
	exit;
}

abstract class Menu implements MenuContract
{
	/**
	 * Page Title
	 *
	 * @var string
	 */
	protected $pageTitle;

	/**
	 * Menu Title
	 *
	 * @var string
	 */
	protected $menuTitle;

	/**
	 * Capability
	 *
	 * @var string
	 */
	protected $capability;

	/**
	 * Menu Slug
	 *
	 * @var string
	 */
	protected $menuSlug;

	/**
	 * Function
	 *
	 * @var string|array|null
	 */
	protected $function = null;

	/**
	 * Icon Url
	 *
	 * @var string
	 */
	protected $iconUrl = '';

	/**
	 * Submenus
	 *
	 * @var array
	 */
	public $submenus = array();

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
	public function defineHooks()
	{
		add_action('admin_menu', array($this, 'setupMenu'));
	}

	/**
	 * Sets the toplevel menu
	 *
	 * @param  string            $pageTitle
	 * @param  string            $menuTitle
	 * @param  string            $capability
	 * @param  string            $menuSlug
	 * @param  string|array|null $function
	 * @param  string            $iconUrl
	 * @return void
	 */
	public function setToplevelMenu($pageTitle, $menuTitle, $capability, $menuSlug, $function = null, $iconUrl = '')
	{
		// Get toplevel position
		$position = $this->getToplevelPosition();

		// Set variables
		$this->pageTitle = $pageTitle;
		$this->menuTitle = $menuTitle;
		$this->capability = $capability;
		$this->menuSlug = $menuSlug;
		$this->function = $function;
		$this->iconUrl = $iconUrl;

		// Add the toplevel menu
		add_menu_page(
			$this->pageTitle,
			$this->menuTitle,
			$this->capability,
			$this->menuSlug,
			$this->function,
			$this->iconUrl,
			"{$position}"
		);
	}

	/**
	 * Adds a submenu page
	 *
	 * @param  string            $pageTitle
	 * @param  string            $menuTitle
	 * @param  string            $capability
	 * @param  string            $menuSlug
	 * @param  string|array|null $callable
	 * @return void
	 */
	public function addSubmenuPage($pageTitle, $menuTitle, $capability, $menuSlug, $callable = null)
	{
		// Add submenu page
		$this->submenus[] = add_submenu_page(
			$this->menuSlug,
			$pageTitle,
			$menuTitle,
			$capability,
			$menuSlug,
			$callable
		);
	}

	/**
	 * Gets a suitable toplevel menu position
	 *
	 * @return string
	 */
	protected function getToplevelPosition()
	{
		global $menu;

		// Default menu positioning
		$position = '100.1';

		// If enabled, relocate the plugin menus higher
		if (apply_filters('easingslider_relocate_menus', __return_true())) {

			for ($position = '40.1'; $position <= '100.1'; $position += '0.1') {

				// Ensure there is a space before and after each position we are checking, leaving room for our separators.
				$before = $position - '0.1';
				$after  = $position + '0.1';

				// Do the checks for each position. These need to be strings, hence the quotation marks.
				if (isset($menu["$position"])) {
					continue;
				}
				if (isset($menu["$before" ])) {
					continue;
				}
				if (isset($menu["$after" ])) {
					continue;
				}

				// If we've successfully gotten this far, break the loop. We've found the position we need.
				break;

			}

			// Add the menu separators
			$this->addSeparator("$before");
			$this->addSeparator("$after");

		}

		return $position;
	}

	/**
	 * Create a separator in the admin menus, above and below our plugin menus
	 *
	 * @param  string $position The menu position to insert the separator
	 * @return void
	 */
	protected function addSeparator($position = '40.1')
	{
		global $menu;

		// Set index as 0
		$index = 0;

		// Loop through each menu item
		foreach ($menu as $offset => $section) {

			// Increment if we find a separate already at this position
			if ('separator' == substr($section[2], 0, 9)) {
				$index++;
			}

			// Add our separator
			if ($offset >= $position) {

				// Quotation marks ensures the position is a string. Integers won't work if we are using decimal values.
				$menu[ "$position" ] = array( '', 'read', "separator{$index}", '', 'wp-menu-separator' );
				break;

			}
			
		}

		// Sort the menu
		ksort($menu);
	}

	/**
	 * Setup menu
	 *
	 * @return void
	 */
	public function setupMenu()
	{
		//
	}
}
