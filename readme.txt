=== Easing Slider  ===
Homepage: http://easingslider.com
Contributors: MatthewRuddy
Tags: slideshow, slider, slides, slide, gallery, images, image, responsive, mobile, jquery, javascript, featured, content
Requires at least: 4.0
Tested up to: 4.2
Stable tag: 2.2.1.1

Easing Slider is an easy to use slider plugin. Simple and lightweight, is makes creating beautiful WordPress sliders a breeze.

== Description ==

Easing Slider is an extremely easy to use slider plugin for WordPress. It is built to be lightweight and simple, enabling you to create beautiful sliders quickly and efficiently. It comes with many great features, some of which include:

* Fully responsive & mobile ready
* Bulk image uploading, integrated with new WordPress Media Library
* CSS3 transitions for ultra smooth transitions
* Navigation arrows & pagination
* Preloading functionality on page load
* A visual editor for customizing basic styling
* Developer friendly with built-in Javascript events
* Dozens of extensions that greatly enhance plugin functionality
* Lots of actions & filters for custom functionality

<strong>Easing Slider has many great extensions that can vastly enhance the plugin's functionality. <a href="http://easingslider.com/extensions">Browse them all here</a>.</strong>

Throughly tested on iPhone, iPad and multiple Android devices, Easing Slider is the perfect solution for mobile sliders. We've used CSS3 animation to ensure ultra smooth transitions on supported devices, with graceful fallbacks for older browsers.

We've also integrated the new WordPress Media Library workflow to provide a better media management experience. Similar to the Media Library, the learning curve and minimal and the admin interface feels instantly recognisable. Bulk uploading images to your slider is now easy, requiring just a few clicks.

Last but not least, we've left plenty of opportunity for custom plugin modifications using the WordPress Action & Filter APIs. You can completely create your own external functionality, or purchase <a href="http://easingslider.com/extensions">one of our extensions</a> to avail of pre-built additional features.

<strong>Follow & contribute to this plugin on <a href="https://github.com/easingslider/easing-slider">Github</a>.</strong>

== Installation ==

= Display a slider =
To display the slider, you can use any of the following methods.

**In a post/page:**
Simply insert the shortcode below into the post/page to display the slider. Be sure to replace the "1" with the ID of the slider you wish to display.

`[easingslider id="1"]`

**Function in template files (via php):**
To insert the slider into your theme, add the following code to the appropriate theme file. As above, replace the "1" with the ID of the slider you wish to display.

`<?php if ( function_exists( "easingslider" ) ) { easingslider( 1 ); } ?>`

== Frequently Asked Questions ==

= My slider continually loads. What's wrong? =

This can often be caused by a jQuery conflict. Many plugins don't load jQuery correctly and as a result break the plugins that do.

Firstly, disable all of the other plugins you have activated (or as many as you can). If the issue persists, with just Easing Slider "Lite" active, it is more than likely a conflict with the theme.

If the slider works when it is the only plugin active, you're experiencing a plugin conflict. Carefully enable each plugin, one-by-one, checking the slider each time. Keep doing this until you activate the plugin that breaks the slider.

After you've taken these two steps, make a support topic and we will get back to you as soon as you can. Otherwise, feel free to contact the developer(s) of the conflict plugin/theme also. They should also be able to provide you with assistance.

= How can I edit a slide? =

This is easy. When editing a slider in the "All Sliders" admin area, simply click an individual slide thumbnail and it's settings panel will appear.

== Screenshots ==

1. "All Sliders" admin page. This is where you manage your sliders.
2. Preview of the "Edit Slider" panel.
3. Modal window for managing the settings of an individual slide.
4. Preview of the plugin "Settings" admin page.
5. "Customize" admin page, which allows you to customize the styling of a slider.
6. Browse available extensions for Easing Slider from the "Extensions" admin page.
7. A preview of a slider. This is how is should appear on your WordPress site (may differ based on your settings).

== Changelog ==

= 2.2.1.1 =
* Fixed “Maximum call stack exceeded” bug encountered when displaying multiple sliders on a single page.

= 2.2.1 =
* Added support for background images (found inside "Advanced Options" in "Dimensions" settings box).
* Added support for 100% full width sliders (found inside "Advanced Options" in "Dimensions" settings box).
* Added template function to "All Sliders" list table.
* Added image resizing option to sliders, rather than globally on the "Settings" page. Image resizing can now be enabled on a per-slider basis.
* Improved styling of sliders to accommodate new features and make it more versatile.
* Improved support for mobile devices in Easing Slider admin area.
* Added helper functions for determining what sliders have been rendered on the page.
* Improved support for older versions of jQuery. Minimum version required is now jQuery v1.4.2.
* Improved security by sanatizing keys where necessary and additional escaping of output. Better safe than sorry!
* Improved "Extensions" page. Extensions are now remotely fetched from a feed on our server.
* More minor improvements and fixes.

= 2.2.0.8 =
* Readded support for opening slide links in a new window/tab.
* Added proper extension descriptions to "Extensions" panel.
* Prepared plugin for video slide support.
* Improved some actions and filters to better facilitate developers and other extensions.
* Fixed issue where blank shadow image would display is shadow was enabled but no image was set.
* Fixed issue that limited attachments query to 40 images only.
* Fixed various bugs experienced by users.

= 2.2.0.7 =
* General improvements to cater for alternative slide types, such as videos or URL images.
* Fixed a bug (related to above improvement) that would prevent slider from loading when slide didn't contain an image.
* Fixed bug causing isolated jQuery errors when attempting to setup navigation elements that were disabled.
* Transparent PNGs now play nicely.
* Fixed some $_GET input validation security issues.
* Improvded support for touch devices.
* Added post type variable to slider object, allowing future extensions more flexibility.
* Added support for HiDPI devices.
* Fixed conflict with MooTools.

= 2.2.0.6 =
* Fixed z-index bug with dropdown menus in multiple themes.
* Update manager is now fully working and querying extension updates correctly.
* Fixed issue that caused slides to show up beneath transparent PNG's.
* Fixed error displayed when trying to using the Easing Slider 'Lite' shortcode after deleting the "Lite" slider.
* Fixed license key deactivation bug.
* Added alt text attribute to images, which was missing previously.
* Fixed double slashing bug with stylesheets and scripts.

= 2.2.0.5 =
* Fixed bug that prevented CSS and Javascripts from loading in certain circumstances.

= 2.2.0.4 =
* Added additional legacy functionality to display sliders using template function and shortcode from v1 of Easing Slider.

= 2.2.0.3 =
* Fixed compatibility issues with __callStatic and PHP 5.2.

= 2.2.0.2 =
* Fixed issues with legacy upgrades. Methods should now be prioritized correctly and flagged on completion appropriately.

= 2.2.0.1 =
* Fixed static bindings bug for users using less than PHP 5.3.

= 2.2 =
* Users can now have unlimited sliders (no longer limited to one).
* New admin interface makes managing your sliders easier and quicker, and provides users with enhanced media management.
* Under the hood, the plugin has been completely rebuilt to be faster, more extensible and future proof.

= 2.1.4.3 =
* Fixed issues with customizer and JSON encoding.

= 2.1.4.2 =
* Fixed widget title filter bug.
* Fixed admin menu button icon CSS margin.
* Updated adverts to reflect site changes.

= 2.1.4.1 =
* Added dashicon to top-level menu.
* Fixed admin menu styling bug when Easing Slider “Pro” was active and using WordPress v3.8+.
* Updated plugin translations .pot file.

= 2.1.4 =
* Fixed bug that broke media uploader in WordPress v3.9.
* Fixed bug that prevented "Customize" panel from loading in WordPress v3.9.

= 2.1.3 =
* Plugin is now fully styled to fit thew new WordPress v3.8+ administration area.
* Fixed a bug that could cause "Add Images" to fail if the selected image doesn't have a thumbnail.

= 2.1.2 =
* Added accordion CSS to fix WordPress 3.6 bugs.
* Fixed clearing and border edge case CSS issues
* Made preloading functionality more reliable.
* Added missing translations.
* Improved legacy functionality class.

= 2.1.1 =
* Fixed all IE bugs. Now working perfectly in IE7+.
* Separated legacy code into its own separate file.
* Added languages file with .pot for translating the plugin.
* Fixed some textual mistakes and commenting errors.
* Fixed backface visbility bugs in Webkit browsers.
* Improved reliability of responsive functionality on plugin initialization.
* Fixed escaping issues related to slashes and quotation marks in strings.
* Improved admin notices functionality: now using WordPress native hooks.

= 2.1 =
* Added "Customize" panel which allows you to make basic slider styling alterations using a new visual editor.
* Reconfigured preloading functionality to fix a bug.
* Added title attribute functionality to images.
* Re-added functionality for script and style registration, making them easier enqueue.
* Fixed backbone templating issues that would render admin area unusable for some users.

= 2.0.1.3 =
* Made some alterations to give a better success rate when upgrading from v1.x.
* Added options to manually import v1.x options, instead of automatically (which often failed and caused major problems).
* Fixed IE7 bugs
* Reconfigured admin script & style functions to hopefully resolve some issues that were preventing them from loading for some users (inexplicably).
* Disable image resizing functionality on activation due to some rare unknown issues. Feel free to use it if you like!

= 2.0.1.2 =
* Fixed backwards compatibility issues with older versions of jQuery

= 2.0.1.1 =
* Fixed script cross origin bug.

= 2.0.1 =
* Fixed bugs with 2.0 release. Reverted name from Riva Slider "Lite" back to Easing Slider (transition did not go as hoped, sorry).
* Fixed CSS rendering issues some users were experiencing.
* Updated plugin upgrade procedures

= 2.0 =
* Too many updates to count. Completely revamped plugin from a clean slate. Hope you enjoy using it as much as I did creating it!

= 1.2.1 =
* Fixed: jQuery re-registering has been removed. Wordpress version of jQuery now used.
* Added: Notification for forthcoming major update.

= 1.2 =
* Changed: Adverts from Premium Slider to Easing Slider Pro.
* Changed: When activated, plugin will now default to 'Custom Images'
* Prepared plugin for major update (coming soon).

= 1.1.9 =
* Fixed: Plugin inconsistancies and Javascript mistakes.
* Changed: Plugin now only deletes slider when uninstalled (rather than de-activated).

= 1.1.8 =
* Fixed: IE9 issues. Slider is now fully functional in IE9.

= 1.1.7 =
* Added: Option to enable or disable jQuery.
* Fixed: Issue with slider appearing above post content when using shortcode.

= 1.1.6 =
* Added: Premium Slider notice.
* Added: Icon to heading on Admin options.

= 1.1.5 =
* Fixed: Mix up between autoPlay & transitionSpeed values in previous versions.

= 1.1.4 =
* Fixed: Added !important to padding & margin values of 0 to make sure slider doesn't inherit theme's css values.

= 1.1.3 =
* Fixed: CSS glitch in admin area.

= 1.1.2 =
* Fixed: Bug with previous version.

= 1.1.1 =
* Added: Option to disable permalinks in 'slider settings'.

= 1.1.0 =
* Added: Ability to add links to images. Images sourced from custom fields link to their respective post.
* Fixed: Edited script.js issue with fade animation.

= 1.0.3 =
* Added: paddingTop & paddingRight settings.
* Fixed: Bottom padding issue when shadow is enabled.
* Changed: Tab name 'Plugin Settings' to 'Usage Settings'.

= 1.0.2 =
* Added: Fade transition. Compatibility problems fixed.
* Fixed: Preloader margin-top with IE only. Used IE hack to add 1 pixel to the top margin to make preloader appear aligned.

= 1.0.1 =
* Fixed: Issues with 'Thematic' theme.
* Fixed: jQuery into noConflict mode to avoid conflictions with various other jQuery plugins.
* Fixed: Parse errors in CSS file.
* Fixed: jQuery version number.
* Removed: Fade transition effect due to compatibility problems & issue with certain themes.