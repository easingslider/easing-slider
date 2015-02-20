/**
 * Globalize
 */
window.EasingSlider = window.EasingSlider || { Customizer: { models: {}, views: {} } };

/**
 * Where the magic happens!
 */
(function($) {

	var Customizer = window.EasingSlider.Customizer;

	/**
	 * Customizer View
	 */
	Customizer.views.Customizer = Backbone.View.extend({

		/**
		 * Our view element
		 */
		el: '.customize-container',

		/**
		 * Events
		 */
		events: {
			'change #change-slider':          '_changeSlider',
			'change [data-selector]':         '_reflectChanges',
			'click .collapse-sidebar':        '_toggleSidebar',
			'click .accordion-section-title': '_toggleSection'
		},

		/**
		 * Constructor
		 */
		initialize: function() {

			var view = this;

            // Inititiate color pickers
            this.$('.color-picker-hex').each(function() {
                $(this).wpColorPicker({
                	change:       view._reflectChanges,
                    defaultColor: $(this).attr('data-default')
                });
            });

		},

		/**
		 * Changes the slider, reloading the page.
		 */
		_changeSlider: function(event) {
			
			// Redirect the page
			window.location.href = 'http://'+ window.location.hostname + window.location.pathname + '?page=easingslider_manage_customizations&edit='+ event.target.value;

		},

		/**
		 * Reflects changes made to inputs on the provided selector.
		 * This provides a live preview of the changes as they are made.
		 */
		_reflectChanges: function(event) {

			// Prefix and suffix the value as necessary
			switch ( event.target.dataset.property ) {

				case 'background-image':
					$(event.target.dataset.selector).css({
						'background-image': 'url('+ event.target.value +')'
					});
					break;

				case 'border-width':
					$(event.target.dataset.selector).css({
						'border-style': 'solid',
						'border-width': event.target.value +'px'
					});
					break;

				case 'border-radius':
					$(event.target.dataset.selector).css({
						'-webkit-border-radius': event.target.value +'px',
						'-moz-border-radius':    event.target.value +'px',
						'border-radius':         event.target.value +'px'
					});
					break;

				case 'display':
					if ( 'true' == event.target.value ) {
						$(event.target.dataset.selector).css('display', 'block');
					}
					else {
						$(event.target.dataset.selector).css('display', 'none');
					}
					break;

				case 'src':
					$(event.target.dataset.selector).attr('src', event.target.value);
					break;

				default:
					$(event.target.dataset.selector).css(event.target.dataset.property, event.target.value);
					break;

			}

		},

		/**
		 * Toggles the sidebar
		 */
		_toggleSidebar: function(event) {

			event.preventDefault();

			// Expand/collapse the sidebar
			if ( this.$('.wp-full-overlay').hasClass('expanded') ) {
				this.$('.wp-full-overlay').removeClass('expanded').addClass('collapsed');
			}
			else {
				this.$('.wp-full-overlay').removeClass('collapsed').addClass('expanded');
			}

		},

		/**
		 * Toggles a section
		 */
		_toggleSection: function(event) {

			event.preventDefault();

			// Loop through and toggle each section appropriately
			this.$('.accordion-section-title').each(function() {

				// Get the inside
				var $section = $(this).closest('.accordion-section'),
					$inside  = $section.find('.accordion-section-content');

				// Determine if this is the clicked section, or another section.
				if ( this == event.currentTarget ) {

					// If the clicked section is already open, close it. Otherwise, open it.
					if ( $section.hasClass('open') ) {

						// "Slide Up" the section content then close it entirely
						$inside.slideUp({
							duration: 'fast',
							easing:   'linear',
							complete: function() {
								$section.removeClass('open');
							}
						});

					}
					else {

						// Open the section
						$section.addClass('open');

						// "Slide Down" the content
						$inside.css({ 'display': 'none' }).slideDown('fast');

					}
				}
				else {

					// "Slide Up" the section content then close it entirely					
					$inside.slideUp({
						duration: 'fast',
						easing:   'linear',
						complete: function() {
							$section.removeClass('open');
						}
					});

				}

			});

		}

	});

	/**
	 * Let's go!
	 */
	$(document).ready(function() {

		// Initiate the view
		new Customizer.views.Customizer();

	});

})(jQuery);