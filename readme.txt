=== Easing Slider  ===
Homepage: http://easingslider.com
Contributors: MatthewRuddy
Tags: slider, wordpress slider, carousel, image slider, responsive slider, slide, slider, slideshow, wordpress slideshow, youtube slider, photo slider, banner rotator, best slider, content slider, fullwidth slider, gallery, hardware accelerate, mobile slider,post slider, swipe, touch slider, page slider, slider plugin, slider shortcode
Requires at least: 4.5
Tested up to: 4.6
Stable tag: 3.0.8
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

The easiest way to create sliders with WordPress.

== Description ==

Creating sliders with WordPress has always been a tricky business. Similiar plugins try to provide a solution but none really hit the mark. Easing Slider aims to fix that. This plugin focuses on achieving it's core purpose - to create and manage sliders with ease. It aims to keep slider creation simple, require little to no learning curve, and fit into the WordPress admin area like it was included from the start.

Put simply, Easing Slider makes creating sliders simple by providing just the features you need.

> Need additionals features? Easing Slider is fully flexible and can be enhanced with add-ons available to license holders only. Want to gain access? <a href="http://easingslider.com/#product-pricing" target="_blank">See Pricing</a>.

Some of the best Easing Slider features include:

#### Primary Features
* Responsive & ready for all devices
* Seamless integration with the WordPress admin area
* Full WordPress Media Library integration
* Smooth transitions thanks to hardware acceleration
* Professional & reliable code by experienced PHP developers
* Lazy Loading for extremely fast page loading times
* Lots of add-ons to enhance functionality greatly (<a href="http://easingslider.com/addons" target="_blank">See our Website</a>)

#### Support
Need help & support? No problems. If you ever run into any trouble, don't hesitate to <a href="http://easingslider.com/support" target="_blank">Contact Us</a>.

#### Additional Features
Easing Slider is highly focused to ensure a solid user experience. To ensure this, we offer our additional features as installable add-ons. This ensures that you're using only the features you require, with nothing else to pollute your product experience. Some additional feature available include:

*  **Simple Captions** - Add captions to your slides with minimal effort!
*  **Posts Feed** - Source slides from WordPress posts, pages, or a custom post type.
*  **Thumbnails** - Use thumbnails as for slider pagination, instead of icons.
*  **Video Slides** - Add videos to your sliders from YouTube, Vimeo or Wistia.
*  **Carousel** - Turn your slider(s) into a carousel, showing multiple slides at once.
*  **Lightbox** - Link images to a jQuery Lightbox
*  **External Images** - Add images from external sites via URL

#### Contributing
Easing Slider is fully open source. We welcome all contributions, issues and criticism. Please don't hesitate to follow & contribute through Github. You can find our repository <a href="https://github.com/easingslider/easing-slider">here</a>.

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

= 3.0.8 - June 24th, 2016 =
* Fixed bug that would prevent slider(s) from obeying dimensions correctly when displaying multiple on a single page.
* Fixed bug that would prevent uninstallation on PHP versions less than 5.3.

= 3.0.7 - June 17th, 2016 =
* Added CSS property to ensure slider item `max-width` is always `auto`.
* Fixed issue in Javascript that would break initial slider height set under some circumstances.
* Fixed slider on RTL sites.
* Slide classes now printed using PHP function. Also filtered for additional customization.

= 3.0.6 - June 11th, 2016 =
* Refactored & improved initial v3.0.0 upgrade, fixing issues with upgrade process between v3.0.4 & v3.0.5.
* Now using `admin_url` function to generate redirect URLs in admin.

= 3.0.5 - June 8th, 2016 =
* Improvements to upgrade process from versions 2.1 and 2.2. Process should now be much smoother.
* Added support for installing addons where FTP access is required.
* Shortcode HTML output is now minified to avoid issues related to other plugins parsing post content and injecting markup.
* Slight tweaks to Javascript that should improve accuracy of responsive slider width calculations.
* Slides are now centered when container is set to 100% fluid width.

= 3.0.4 - June 6th, 2016 =
* Added additional legacy functionality for users upgrading from v2.1.*.
* Fixed issue that would cause widget not to display.

= 3.0.3 - June 4th, 2016 =
* Fixed issue that caused template function not to display unless echoed.
* Fixed `Fatal error: Can't inherit abstract function` related issues (known PHP bug https://bugs.php.net/bug.php?id=66818)

= 3.0.2 - June 3rd, 2016 =
* Refactored boot sequence to prevent errors prior to completing PHP & WordPress version checks.
* Fixed issue that lead to version number not getting updated unless an updater class existed.
* Fixed issue with settings tabs on PHP 5.4 or lower.
* Fixed issue that caused upgrade notice not to dismiss immediately when dismissed.
* Additional tweaks and enhancements under the hood.

= 3.0.1 - June 3rd, 2016 =
* Fixed bug that prevented lazy loading icon from showing when loading is in progress.
* Minimum requirement checks now occur before any PHP 5.3 namespaced code gets loaded, thus preventing fatal errors.

= 3.0.0 - June 3rd, 2016 =
* A complete internal rebuild, now fully compatible with current versions of WordPress and those coming in the future. Marks the start of a new product direction for Easing Slider, and a renewed committment to continued development!

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