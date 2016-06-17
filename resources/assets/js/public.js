;(function($) {

	/**
	 * jQuery Easing Slider
	 *
	 * This is essentially a container for our interactions with Owl Carousel, aliased as `$.easingSlider` to avoid conflicts.
	 * We're relying on it as our third-party slider script, plus some additional functionality to enhance.
	 */
	$.EasingSlider = function(el) {

		// Core variables
		var base = this;

		// Establish our elements
		base.el       = el;
		base.$el      = $(base.el);

		// Get options
		base.options = window['EasingSlider'+ base.$el.attr('data-id')];

		// Determine click event
		base._clickEvent = ( 'ontouchstart' in document.documentElement ) ? 'touchstart' : 'click';

		// Store our data for external access
		base.$el.data('easingslider', base);

		/**
		 * Initiates the slider
		 */
		base.initSlider = function() {

			// Initiate Owl Carousel (aliased as $.easingSlider to avoid conflicts)
			base.$el.easingSlider($.extend(base.options, {
				afterInit: function() {
					base._maybeResize();
				},
				afterUpdate: function() {
					base._maybeResize();
				},
				afterAction: function() {
					base.$el.trigger('changeSlide', this.currentItem);	
				}
			}));

		};

		/**
		 * Resizes the slider if aspect ratio is enabled
		 */
		base._maybeResize = function() {

			// Resize if maintaining aspect ratio
			if ( base.$el.hasClass('easingslider-aspect-ratio') ) {
				base.doResize();
			}

			return base;

		};

		/**
		 * Executes a resize
		 */
		base.doResize = function() {

			// Get elements
			var $el = base.$el;
			var $wrapper = $el.find('.easingslider-wrapper');
			var $items = $el.find('.easingslider-item');
			var $images = $el.find('.easingslider-image');

			// Get the current width & height
			var currentWidth = $el.outerWidth();

			// Get the outer wrapper
			var outerWrapper = $el.find('.easingslider-wrapper-outer');

			// If it has changed, resize the height to match.
			if ( currentWidth <= base.options.width ) {

				// Using the default slider width, let's calculate the percentage change and thus calculate the new height.
				var newHeight = Math.floor((currentWidth / base.options.width) * base.options.height);

				// Set heights
				$el.css({ 'height': newHeight +'px' });
				$items.css({ 'height': newHeight +'px' });
				$wrapper.css({ 'height': newHeight +'px' });
				$images.css({ 'max-height': newHeight +'px' });

			} else {

				// Set heights
				$el.css({ 'height': base.options.height +'px' });
				$items.css({ 'height': base.options.height +'px' });
				$wrapper.css({ 'height': base.options.height +'px' });
				$images.css({ 'max-height': base.options.height +'px' });

			}

			// Prevent subpixel rendering by rounding width
			outerWrapper.css({ 'width': Math.floor(outerWrapper.width()) +'px' });

			return base;

		};

		// Initialize plugin
		base.initSlider();

	};

	/**
	 * Initiate slider(s)
	 */
	$.fn.EasingSlider = function() {
		return this.each(function() {
			new $.EasingSlider(this);
		});
	};

	/**
	 * Let's go!
	 */
	$(document).ready(function() {
		$('.easingslider').EasingSlider();
	});

})(jQuery);
