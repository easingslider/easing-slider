;(function($) {

	/**
	 * jQuery Slider
	 */
	$.EasingSlider = function(el) {

		// Core variables
		var base = this,
			o;

		// Establish our elements
		base.el          = el;
		base.$el         = $(base.el);
		base.$viewport   = base.$el.find('.easingslider-viewport');
		base.$slides     = base.$el.find('.easingslider-slide');
		base.$arrows     = base.$el.find('.easingslider-arrows');
		base.$next       = base.$el.find('.easingslider-next');
		base.$prev       = base.$el.find('.easingslider-prev');
		base.$pagination = base.$el.find('.easingslider-pagination');
		base.$icons      = base.$el.find('.easingslider-icon');
		base.$preload    = base.$el.find('.easingslider-preload');

		// Get the plugin options
		base.options = o = $.extend({}, $.EasingSlider.defaults, $.parseJSON(base.$el.attr('data-options')));

		// State variables
		base.current  = 0;
		base.previous = 0;
		base.count    = base.$slides.length;
		base.width    = o.dimensions.width;
		base.height   = o.dimensions.height;

		// Store our data
		base.$el.data('easingslider', base);

		/**
		 * Constructor
		 */
		base.initialize = function() {

			// Determine click event
			base._clickEvent = ( 'ontouchstart' in document.documentElement ) ? 'touchstart' : 'click';

			// Hide all slides
			base.$slides.css({ 'display': 'none' });

			// Set the current slide
			base.$slides.eq(base.current).css({ 'display': 'block' }).addClass('active');

			// Setup components
			base._setupArrows();
			base._setupPagination();
			base._setupPlayback();
			base._setupResizing();
			base._setupBackgrounds();

			// Preload the slider
			base._preload();

			// Trigger event
			base.$el.trigger('init', base);

			return base;

		};

		/**
		 * Sets up the "Arrow" navigation
		 */
		base._setupArrows = function() {

			// Continue if arrows are enabled
			if ( o.navigation.arrows ) {

				// "Next" & "Previous" arrow functionality
				base.$next.bind(base._clickEvent, base.nextSlide);
				base.$prev.bind(base._clickEvent, base.prevSlide);

				// Add hover toggle if enabled
				if ( o.navigation.arrows_hover ) {
					base.$arrows.addClass('has-hover');
				}

				// Show the arrows
				base.$arrows.css({ 'display': 'block' });

			}

			return base;

		};

		/**
		 * Sets up the "Pagination" navigation
		 */
		base._setupPagination = function() {

			// Continue if pagination is enabled
			if ( o.navigation.pagination ) {

				// Bind events
				base.$el.bind('loaded', base._updatePagination);
				base.$el.bind('transition.before', base._updatePagination);

				// Enable click event for each icon
				base.$icons.bind(base._clickEvent, function() {

					// Get the next slide index and direction we are travelling
					var eq        = $(this).index(),
						direction = (eq > base.current) ? 'forward' : 'backward';

					// Transition to the desired slide
					base.goToSlide(eq, direction);

				});

				// Add hover toggle if enabled
				if ( o.navigation.pagination_hover ) {
					base.$pagination.addClass('has-hover');
				}

				// Show the pagination
				base.$pagination.css({ 'display': 'block' });

			}

			return base;

		};

		/**
		 * Updates the active pagination icon
		 */
		base._updatePagination = function() {

			// Continue if pagination is enabled
			if ( o.navigation.pagination ) {

				// Update the active icon
				base.$icons.removeClass('active').eq(base.current).addClass('active');

			}

			return base;

		};

		/**
		 * Sets up the automatic playback
		 */
		base._setupPlayback = function() {

			// Clear playback timer before the transition. It'll be reset after the transition has completed.
			base.$el.bind('transition.before', function() {
				if ( base._playbackTimer ) {
					clearTimeout(base._playbackTimer);
				}
			});

			// Once a transition has completed, continue playback if we have an active timer.
			base.$el.bind('transition.after', function() {
				if ( base._playbackTimer ) {
					base.startPlayback();
				}
			});

			// Queue playback after the slider has loaded, if enabled.
			if ( o.playback.enabled && base.count > 1 ) {
				base.$el.bind('loaded', base.startPlayback);
			}

			return base;

		};

		/**
		 * Sets up the responsive support
		 */
		base._setupResizing = function() {

			// Continue if responsive is enabled
			if ( o.dimensions.responsive ) {

				// Update the size
				base._updateSize();

				// Handle window resizing
				$(window).bind('resize', base._updateSize);
			
			}

			return base;

		};

		/**
		 * Updates the slider's size
		 */
		base._updateSize = function() {

			// Get the container width
			var width = base.$el.outerWidth();

			// If it has changed, resize the height to match.
			if ( width <= base.width && o.dimensions.keep_ratio ) {

				// Using the default slider width, let's calculate the percentage change and thus calculate the new height.
				var height = Math.floor((width / base.width) * base.height);

				// Set the viewport height
				base.$viewport.css({ 'height': height +'px' });

				// Trigger event
				base.$el.trigger('size.update', [base, width, height]);

			}

			return base;

		};

		/**
		 * Sets up the background slides, if enabled
		 */
		base._setupBackgrounds = function() {

			// Handle background images, if enabled
			if ( o.dimensions.background_images ) {
				base.$slides.each(function() {

					// Establish variables
					var $slide = $(this),
						$image = $slide.find('.easingslider-image');

					// Set background
					$slide.addClass('easingslider-background-slide').css({ 'background-image': 'url('+ $image.attr('src') +')' });

					// Hide slide image
					$image.css({ 'display': 'none' });

				});
			}

		};

		/**
		 * Preloads the slider
		 */
		base._preload = function() {

			// Get the total number of images
			var total_images = base.$slides.find('.easingslider-image').length;

			// Preloaded slide count
			base._preloadCount = 0;

			// Loop through and preload each image slide. Doesn't stop on failure, just continues instead.
			base.$el.find('.easingslider-image').each(function() {

				// Load the image
				$(this).one('load', function() {

					// If all slides have been preloaded, hide the preloader and start the playback. Also increase preloader count.
					if ( ++base._preloadCount == total_images ) {
						base.$preload.animate({ 'opacity': 0 }, {
							duration: 400,
							complete: function() {

								// Remove preloader
								$(this).remove();

								// Flag as loaded
								base.$el.addClass('has-loaded');

								// Trigger events
								base.$el.trigger('loaded', base);

							}
						});
					}

				}).each(function() {

					// Load on complete
					if ( this.complete ) {
						$(this).load();
					}

				});

			});

		};

		/**
		 * Handles slide preloading
		 */
		base._load = function() {

			// Increase preloaded count
			base._preloadCount++;

			// Get the total number of images
			var total_images = base.$slides.find('.easingslider-image').length;

			// If all slides have been preloaded, hide the preloader and start the playback.
			if ( base._preloadCount == total_images ) {
				base.$preload.animate({ 'opacity': 0 }, {
					duration: 400,
					complete: function() {

						// Remove preloader
						$(this).remove();

						// Flag as loaded
						base.$el.addClass('has-loaded');

						// Trigger events
						base.$el.trigger('loaded', base);

					}
				});
			}

		};

		/**
		 * Starts automatic playback
		 */
		base.startPlayback = function() {

			// Runtime variable
			base._runtime = new Date();

			// Get pause time
			base._pauseTime = o.playback.pause;

			// Start automatic playback
			base._playbackTimer = setTimeout(function() {
				base.nextSlide();
			}, base._pauseTime);

			// Trigger event
			base.$el.trigger('playback.start', base);

			return base;

		};

		/**
		 * Ends automatic playback
		 */
		base.endPlayback = function() {

			// Clear playback timer
			clearTimeout(base._playbackTimer);

			// Set timer to flase
			base._playbackTimer = false;

			// Trigger event
			base.$el.trigger('playback.end', base);

			return base;

		};

		/**
		 * Pauses automatic playback
		 */
		base.pausePlayback = function() {

			// Clear playback timer
			clearTimeout(base._playbackTimer);

			// Calculate runtime left
			base._runtime = Math.ceil(new Date() - base._runtime);

			// Trigger event
			base.$el.trigger('playback.pause', base);

			return base;

		};

		/**
		 * Resumes automatic playback
		 */
		base.resumePlayback = function() {

			// Calculate playback time remaining
			base._pauseTime = Math.ceil(base._pauseTime - base._runtime);

			// Reset runtime
			base._runtime = new Date();

			// Resume automatic playback
			base._playbackTimer = setTimeout(function() {
				base.nextSlide();
			}, base._pauseTime);

			// Trigger event
			base.$el.trigger('playback.resume', base);

			return base;

		};

		/**
		 * Executes a transition
		 */
		base._transition = function(eq, direction) {

			// Bail if we only have one slide
			if ( base.count <= 1 ) {
				return base;
			}

			// Bail if specified slide doesn't exist
			if ( base.$slides.eq(eq).length == 0 ) {
				return base;
			}

			// Bail if animating already
			if ( base._animating ) {
				return base;
			}

			// Flag that we are transitioning
			base._animating = true;

			// Establish the next and previous slides
			base.previous = base.current;
			base.current  = eq;

			/**
			 * Add animation classes based on direction.
			 *
			 * Timeout functions are used here to avoid a bug in Safari.
			 * The animations won't work if we toggle the display property as we add the animation class,
			 * instead it would intermittently show/hide the slide.
			 *
			 * Using a timeout seems to negate this.
			 */
			if ( 'backward' == direction ) {
				base.$slides.eq(base.previous).css({ 'display': 'block' });
				base.$slides.eq(base.current).css({ 'display': 'block' });

				setTimeout(function() {
					base.$slides.eq(base.previous).addClass('next-out');
					base.$slides.eq(base.current).addClass('prev-in');
				});
			}
			else {
				base.$slides.eq(base.previous).css({ 'display': 'block' });
				base.$slides.eq(base.current).css({ 'display': 'block' });

				setTimeout(function() {
					base.$slides.eq(base.previous).addClass('prev-out');
					base.$slides.eq(base.current).addClass('next-in');
				});
			}

			// After timeout, do some cleaning up.
			clearTimeout(base._cleanup);
			base._cleanup = setTimeout(function() {

				// Toggle the active slide
				base.$slides.eq(base.current).css({ 'display': 'block' }).addClass('active');
				base.$slides.eq(base.previous).css({ 'display': 'none' }).removeClass('active');
				
				// Remove all animation related classes
				base.$slides.removeClass('next-in next-out prev-in prev-out');

				// Flag that we are no longer animating
				base._animating = false;

				// Trigger event
				base.$el.trigger('transition.after', [ base, eq, direction ]);

			}, o.transitions.duration);

			// Trigger event
			base.$el.trigger('transition.before', [ base, eq, direction ]);

			return base;

		};

		/**
		 * Transitions to the next slide
		 */
		base.nextSlide = function() {

			// Establish the next slide
			var eq = ( base.current == (base.count - 1) ) ? 0 : (base.current + 1);

			// Transition to the next slide
			base._transition(eq, 'forward');

			// Trigger event
			base.$el.trigger('transition.next', [ base, eq, 'forward' ]);

			return base;

		};

		/**
		 * Transitions to the previous slide
		 */
		base.prevSlide = function() {

			// Establish the previous slide
			var eq = ( base.current == 0 ) ? (base.count - 1) : (base.current - 1);

			// Transition to the previous slide
			base._transition(eq, 'backward');

			// Trigger event
			base.$el.trigger('transition.prev', [ base, eq, 'backward' ]);

			return base;

		};

		/**
		 * Transitions to a specified slide
		 */
		base.goToSlide = function(eq, direction) {

			// Transition to the specified slide
			this._transition(eq, direction);

			// Trigger event
			base.$el.trigger('transition.to', [ base, eq, direction ]);

			return base;

		};

		// Initialize the plugin
		base.initialize();

	};

	/**
	 * Plugin defaults settings
	 */
	$.EasingSlider.defaults = {
		dimensions: {
			width:               640,
			height:              400,
			responsive:          true,
			full_width:          false,
			image_resizing:      false,
			keep_ratio:          true,
			background_images:   false
		},
		transitions: {
			effect:              'fade',
			duration:            400
		},
		navigation: {
			arrows:              true,
			arrows_hover:        false,
			arrows_position:     'inside',
			pagination:          true,
			pagination_hover:    false,
			pagination_position: 'inside',
			pagination_location: 'bottom-center'
		},
		playback: {
			enabled:             true,
			pause:               4000
		}
	};

	/**
	 * Initiates slider(s)
	 */
	$.fn.EasingSlider = function() {
		return this.each(function() {
			new $.EasingSlider(this);
		});
	};

	// Let's go!
	$(document).ready(function() {
		$('.easingslider').EasingSlider();
	});

})(jQuery);