/**
 * Namespace
 */
window.EasingSlider = window.EasingSlider || {};
window.EasingSlider.Admin = window.EasingSlider.Admin || {};

// Add our components
_.extend(EasingSlider.Admin, {
	model: {},
	collection: {},
	controller: {},
	frame: {},
	view: {},
	toolbar: {},
	router: {},
	Router: {}
});

/**
 * Defines the admin functionality
 */
(function($) {

	var Admin = window.EasingSlider.Admin;

	/**
	 * Returns the appropriate "Slide" model
	 */
	Admin.model.Slide = {

		/**
		 * Get the appropriate model
		 */
		get: function(attributes) {
			
			if ( 'undefined' !== typeof Admin.model.Slide[attributes.type] ) {
				return new Admin.model.Slide[attributes.type](attributes);
			}

			return new Admin.model.Slide.base(attributes);
			
		}

	};

	/**
	 * Base Slide Model
	 */
	Admin.model.Slide.base = Backbone.Model.extend();

	/**
	 * "Image" Slide Model
	 */
	Admin.model.Slide.image = Admin.model.Slide.base.extend({

		/**
		 * Attachment
		 */
		attachment: false,

		/**
		 * Defaults
		 */
		defaults: {
			attachment_id:   null,
			type:            'image',
			alt:             '',
			link:            'none',
			linkUrl:         '',
			linkTargetBlank: false,
			title:           '',
			url:             null
		}

	});

	/**
	 * Slides Collection
	 */
	Admin.collection.Slides = Backbone.Collection.extend({

		/**
		 * The models for this collection are polymorphic,
		 * so the model to add is determined by function.
		 *
		 * Don't be fooled, Admin.model.Slide.get is just a function (not a model).
		 */
		model: Admin.model.Slide.get,

		/**
		 * Constructor
		 */
		initialize: function() {

			// Bind our collection events
			this.on('add', this._resetIDs, this);
			this.on('remove', this._resetIDs, this);
			this.on('reset', this._resetIDs, this);

		},

		/**
		 * Comparator
		 */
		comparator: function(model) {

			return model.get('id');

		},

		/**
		 * Resets the ID attribute on each model
		 */
		_resetIDs: function() {

			// Create a new collection. We need to do this to avoid issues with duplicate IDs.
			var collection = new Backbone.Collection();

			// Loop through each model and update the ID attribute. Then add it to our collection.
			_.each(this.models, function(model, index) {
				model.set({ id: index + 1 });

				collection.add(model);
			});

			// Reset this collection with the models from our temporary collection
			this.reset(collection.models, { silent: true });

		},

		/**
		 * Repositions a model to the specified index in the collection
		 */
		reposition: function(model, index) {

			// Remove the model
			this.remove(model, { silent: true });

			// Add the model at our desired index
			this.add(model, { at: index, silent: true });

			// Reset the IDs
			this._resetIDs();

			return this;

		},

		/**
		 * Syncs the models in this collection
		 */
		sync: function() {

			var collection = this,
				ids        = [];

			// Loop through each model and get attachment IDs
			_.each(this.models, function(model) {

				// Check if the model has an attachment ID
				if ( model.has('attachment_id') ) {
					ids.push(model.get('attachment_id'));
				}

			}, this);

			// Query the WordPress Media Library
			var query = wp.media.query({
				post__in:       ids,
				posts_per_page: -1
			});

			// Do the query
			query.more().done(function() {
				_.each(query.models, function(attachment) {

					// Get models with a matching ID
					var matches = collection.where({ attachment_id: attachment.get('id') });

					// Loop through each match
					_.each(matches, function(model) {

						// Set the attachment object
						model.attachment = attachment;

					});

				});

				// Trigger a sync action
				collection.trigger('sync:done');
			});

			return this;

		}

	});

	/**
	 * Returns the appropriate "Add Slide" controller
	 */
	Admin.controller.AddSlide = {

		/**
		 * Get the appropriate controller
		 */
		get: function(options) {

			if ( 'undefined' !== typeof Admin.controller.AddSlide[options.type] ) {
				return new Admin.controller.AddSlide[options.type](options);
			}

			return new Admin.controller.AddSlide.base(options);

		}

	};

	/**
	 * Base "Add Slide" Controller
	 */
	Admin.controller.AddSlide.base = wp.media.controller.State.extend({

		/**
		 * Constructor
		 */
		initialize: function() {

			// Get the model
			this.props = new Admin.model.Slide.get({
				type: this.get('type')
			});

		}

	});

	/**
	 * Image "Add Slide" Controller
	 */
	Admin.controller.AddSlide.image = wp.media.controller.Library.extend();

	/**
	 * Returns the appropriate "Edit Slide" controller
	 */
	Admin.controller.EditSlide = {

		/**
		 * Get the appropriate controller
		 */
		get: function(options) {
			
			if ( 'undefined' !== typeof Admin.controller.EditSlide[options.type] ) {
				return new Admin.controller.EditSlide[options.type](options);
			}

			return new Admin.controller.EditSlide.base(options);
			
		}

	};

	/**
	 * Base "Edit Slide" Controller
	 */
	Admin.controller.EditSlide.base = wp.media.controller.State.extend({

		/**
		 * Defaults
		 */
		defaults: _.defaults({
			id:       'edit-slide',
			title:    _easingsliderAdminL10n.media_upload.title,
			content:  'edit-slide',
			menu:     false,
			router:   false,
			toolbar:  'edit-slide',
			editing:  false,
			priority: 60
		}, wp.media.controller.State.defaults )

	});

	/**
	 * Image "Edit Slide" Controller
	 */
	Admin.controller.EditSlide.image = Admin.controller.EditSlide.base.extend();

	/**
	 * "Add Slide" Frame
	 */
	Admin.frame.AddSlide = wp.media.view.MediaFrame.Post.extend({

		/**
		 * Constructor
		 */
		initialize: function() {

			// Alter defaults
			_.defaults(this.options, {
				multiple: true
			});

			// Call parent constructor
			wp.media.view.MediaFrame.Post.prototype.initialize.apply(this, arguments);

		},

		/**
		 * Bind event handlers
		 */
		bindHandlers: function() {

			// Bind Handlers
			this.on('close', this.updateRouter, this);
			this.on('content:render:browse', this.removeSidebar, this);
			this.on('toolbar:create:insert-slide', this.createToolbar, this);
			this.on('toolbar:render:insert-slide', this.insertToolbar, this);

			// Call parent
			wp.media.view.MediaFrame.Post.prototype.bindHandlers.apply(this, arguments);

		},

		/**
		 * Render callback for the content region in the 'browse' mode.
		 */
		browseContent: function(contentRegion) {

			// Get the state
			var state = this.state();

			// Show the toolbar
			this.$el.removeClass('hide-toolbar');

			// Browse our library of attachments
			contentRegion.view = new Admin.view.AddSlide.image({
				controller:       this,
				collection:       state.get('library'),
				selection:        state.get('selection'),
				model:            state,
				sortable:         state.get('sortable'),
				search:           state.get('searchable'),
				filters:          state.get('filterable'),
				date:             state.get('date'),
				display:          state.has('display') ? state.get('display') : state.get('displaySettings'),
				dragInfo:         state.get('dragInfo'),
				idealColumnWidth: state.get('idealColumnWidth'),
				suggestedWidth:   state.get('suggestedWidth'),
				suggestedHeight:  state.get('suggestedHeight'),
				AttachmentView:   state.get('AttachmentView')
			});

		},

		/**
		 * Removes the sidebar from the frame
		 */
		removeSidebar: function(view) {

			// Remove the sidebar
			view.sidebar.remove('details');

			// Hide sidebar altogether
			view.$el.addClass('hide-sidebar');

		},

		/**
		 * Creates our states
		 */
		createStates: function() {

			// Add the default states
			this.states.add([
				new Admin.controller.AddSlide.image({
					id:                  'insert',
					type:                'image',
					title:               _easingsliderAdminL10n.media_upload.image_from_media,
					priority:            20,
					toolbar:             'insert-slide',
					filterable:          false,
					library:             wp.media.query({ type: 'image' }),
					multiple:            true,
					editable:            true,
					allowLocalEdits:     true,
					displaySettings:     false,
					displayUserSettings: true
				})
			]);

		},

		/**
		 * We're overriding this method to prevent WordPress from adding iFrame states to our states.
		 * We don't want these, simple.
		 */
		createIframeStates: function() {
			
			return this;
			
		},

		/**
		 * Adds our "Insert into Slider" toolbar
		 */
		insertToolbar: function(view) {

			var controller = this;

			// Add the toolbar to our provided view
			view.set('insert-slide', {
				style: 'primary',
				priority: 80,
				text: _easingsliderAdminL10n.media_upload.insert_into_slider,
				requires: { selection: false },
				click: function() {

					// Close then trigger "insert", providing the selection
					controller.close().trigger('insert', controller.getSelection()).reset();

				}
			});

		},

		/**
		 * Prepares & returns our selection for handoff to our view(s)
		 */
		getSelection: function(selection) {

			var collection = new wp.media.model.Selection(null, { multiple: true });

			// Get the type & selection
			var type      = this.state().get('type'),
				selection = this.state().get('selection');

			// Loop through each selection
			_.each(selection.models, function(model) {

				// Create a new slide & set its type
				var slide = new Admin.model.Slide.get({
					type: type
				});

				// Handle attachments (attachments will have an ID attribute)
				if ( model.get('id') ) {
					slide.attachment = model;
					slide.set({ attachment_id: model.get('id') }, { silent: true });
				}
				else {
					slide.set(model, { silent: true });
				}

				// Add to the collection
				collection.add(slide);

			}, this);

			return collection;

		},

		/**
		 * Updates the URL when the frame is closed
		 */
		updateRouter: function() {

			Admin.Router.navigate(_easingsliderAdminL10n.base_url);

		}

	});

	/**
	 * Returns the appropriate "Edit Slide" controller
	 */
	Admin.frame.EditSlide = {

		/**
		 * Get the appropriate frame
		 */
		get: function(options) {
			
			if ( 'undefined' !== typeof Admin.frame.EditSlide[options.model.get('type')] ) {
				return new Admin.frame.EditSlide[options.model.get('type')](options);
			}

			return new Admin.frame.EditSlide.base(options);
			
		}

	};

	/**
	 * Base "Edit Slide" Frame
	 */
	Admin.frame.EditSlide.base = wp.media.view.MediaFrame.Select.extend({

		/**
		 * Classname
		 */
		className: 'edit-slide-frame media-frame',

		/**
		 * Template for this frame
		 */
		template: wp.template('easingslider-edit-slide-frame'),

		/**
		 * Events
		 */
		events: _.defaults({
			'click .left':  'previousSlide',
			'click .right': 'nextSlide'
		}, wp.media.view.MediaFrame.Select.prototype.events),

		/**
		 * Constructor
		 */
		initialize: function() {

			// Set our options
			this.options.state = 'edit-slide';
			this.options.selection = new wp.media.model.Selection({}, { multiple: false });

			// Call parent constructor
			wp.media.view.MediaFrame.Select.prototype.initialize.apply(this, arguments);

		},

		/**
		 * Bind event handlers
		 */
		bindHandlers: function() {

			var frame = this;

			// Bind events
			this.on('close', this.updateRouter, this);
			this.on('content:create:edit-slide', this.createView, this);
			this.on('toolbar:render:edit-slide', this.createToolbar, this);

			// Call parent
			wp.media.view.MediaFrame.Select.prototype.bindHandlers.apply(this, arguments);

		},

		/**
		 * Creates the view
		 */
		createView: function(options) {

			// Initiate the view
			options.view = new Admin.view.EditSlide.get({
				type:       this.model.get('type'),
				model:      this.model,
				controller: this
			});

		},

		/**
		 * Creates the toolbar
		 */
		createToolbar: function() {

			// Create and set the toolbar
			this.toolbar.set(
				new wp.media.view.Toolbar({
					controller: this,
					items: {
						select: {
							style:    'primary',
							text:     _easingsliderAdminL10n.media_upload.update,
							priority: 80,
							click: function() {

								// Establish variables
								var controller = this.controller,
									state      = controller.state();

								// Close the frame
								controller.close();

								// Trigger update
								state.trigger('update', controller.model.toJSON());

								// Restore and reset the default state
								controller.setState(controller.options.state);
								controller.reset();

							}
						}
					}
				})
			);

		},

		/**
		 * Creates our states
		 */
		createStates: function() {

			// Add our "Edit Slide" state
			this.states.add([
				new Admin.controller.EditSlide.get({
					type: this.model.get('type')
				})
			]);

			// Call parent states
			wp.media.view.MediaFrame.Select.prototype.createStates.apply(this, arguments);

		},

		/**
		 * Renders the frame
		 */
		render: function() {

			// Call parent
			wp.media.view.MediaFrame.Select.prototype.render.apply(this, arguments);

			// Toggle navigation
			this.toggleNav();

			return this;

		},

		/**
		 * Rerenders the frame
		 */
		rerender: function() {

			// Rerender the content region
			this.content.render();

			// Toggle navigation
			this.toggleNav();

			return this;

		},

		/**
		 * Toggles the navigation
		 */
		toggleNav: function() {

			// Reset navigation
			this.$('.left').removeProp('disabled').removeClass('disabled');
			this.$('.right').removeProp('disabled').removeClass('disabled');

			// Toggle previous
			if ( ! this.hasPrevious() ) {
				this.$('.left').prop('disabled', 'disabled').addClass('disabled');
			}

			// Toggle next
			if ( ! this.hasNext() ) {
				this.$('.right').prop('disabled', 'disabled').addClass('disabled');
			}

			return this;

		},

		/**
		 * Click handler to switch to the previous slide
		 */
		previousSlide: function() {

			// Bail if we don't have a previous slide
			if ( ! this.hasPrevious() ) {
				this.$('.left').blur();
				return;
			}

			// Set the new model
			this.model = this.collection.at(this.collection.indexOf(this.model) - 1);

			// Rerender the view
			this.rerender();

			// Focus navigation arrow
			this.$('.left').focus();

		},

		/**
		 * Click handler to switch to the next slide
		 */
		nextSlide: function() {

			// Bail if we don't have a next slide
			if ( ! this.hasNext() ) {
				this.$('.right').blur();
				return;
			}

			// Set the new model
			this.model = this.collection.at(this.collection.indexOf(this.model) + 1);

			// Rerender the view
			this.rerender();

			// Focus navigation arrow
			this.$( '.right' ).focus();

		},

		/**
		 * Checks if we have a next slide
		 */
		hasNext: function() {

			return ( this.collection.indexOf(this.model) + 1 ) < this.collection.length;

		},

		/**
		 * Checks if we have a previous slide
		 */
		hasPrevious: function() {

			return ( this.collection.indexOf(this.model) - 1 ) > -1;

		},

		/**
		 * Updates the URL when the frame is closed
		 */
		updateRouter: function() {

			Admin.Router.navigate(_easingsliderAdminL10n.base_url);

		}

	});

	/**
	 * Image "Edit Slide" Frame
	 */
	Admin.frame.EditSlide.image = Admin.frame.EditSlide.base.extend({

		/**
		 * Constructor
		 */
		initialize: function() {

			// Set the image
			this.image = new wp.media.model.PostImage(this.model.attributes);

			// Call parent constructor
			Admin.frame.EditSlide.base.prototype.initialize.apply(this, arguments);
		
		},

		/**
		 * Bind event handlers
		 */
		bindHandlers: function() {

			// Bind events
			this.on('content:create:browse', this.modifyBrowseFilters, this);
			this.on('content:render:browse', this.removeBrowseSidebar, this);
			this.on('content:render:edit-slide', this.showNav, this);
			this.on('content:render:edit-image', this.renderEditImageContent, this);
			this.on('toolbar:render:replace', this.hideNav, this);
			this.on('toolbar:render:replace', this.renderReplaceImageToolbar, this);
			this.state('replace-image').on('replace', this.replaceImage, this);

			// Call parent
			Admin.frame.EditSlide.base.prototype.bindHandlers.apply(this, arguments);

		},

		/**
		 * Create our states
		 */
		createStates: function() {

			// Call parent
			Admin.frame.EditSlide.base.prototype.createStates.apply(this, arguments);

			// Add "Edit" and "Replace" image states
			this.states.add([
				new wp.media.controller.ReplaceImage({
					id:              'replace-image',
					library:         wp.media.query({ type: 'image' }),
					image:           this.image,
					multiple:        false,
					title:           _easingsliderAdminL10n.media_upload.replace_image,
					toolbar:         'replace',
					priority:        80,
					displaySettings: true
				}),
				new wp.media.controller.EditImage( {
					image:     this.image,
					selection: this.options.selection
				} )
			]);

		},

		/**
		 * Overrides the Media Library browser filters, as the default filters aren't suitable.
		 */
		modifyBrowseFilters: function(contentRegion) {

			// Set the filters state
			this.state().set('filterable', true);

		},

		/**
		 * Removes the sidebar from the Media Library browser
		 */
		removeBrowseSidebar: function(view) {

			// Remove the sidebar and hide it's element
			view.sidebar.remove('details');
			view.$el.addClass('hide-sidebar');

		},

		/**
		 * Show the navigation
		 */
		showNav: function() {

			this.$('.left, .right').show();
			$('.media-modal-close').removeClass('no-border');

		},

		/**
		 * Hide the navigation
		 */
		hideNav: function() {

			this.$('.left, .right').hide();
			$('.media-modal-close').addClass('no-border');
			
		},

		/**
		 * Renders the "Edit Image" view in the frame
		 */
		renderEditImageContent: function() {

			// Establish variables
			var state = this.state(),
				model = state.get('image'),
				view;

			// Bail if we have no model
			if ( ! model ) {
				return;
			}

			// Initiate the "Edit Image" view
			view = new wp.media.view.EditImage({ model: model, controller: this }).render();

			// Set the frame content
			this.content.set(view);

			// After bringing in the frame, load the actual editor via an ajax call
			view.loadEditor();

		},

		/**
		 * Renders the "Replace Image" toolbar in the frame
		 */
		renderReplaceImageToolbar: function() {

			// Establish variables
			var frame     = this,
				lastState = frame.lastState(),
				previous  = lastState && lastState.id;

			// Set the toolbar
			this.toolbar.set(
				new wp.media.view.Toolbar({
					controller: this,
					items: {
						back: {
							text:     _easingsliderAdminL10n.media_upload.back,
							priority: 20,
							click: function() {

								// Close or go back to previous state
								if ( previous ) {
									frame.setState(previous);
								} else {
									frame.close();
								}

							}
						},
						replace: {
							style:    'primary',
							text:     _easingsliderAdminL10n.media_upload.replace,
							priority: 80,
							click: function() {

								// Establish variables
								var controller = this.controller,
									state      = controller.state(),
									selection  = state.get('selection'),
									attachment = selection.single();

								// Close controller
								controller.close();

								// Change attachment
								controller.image.changeAttachment(attachment, state.display(attachment));

								// Not sure if we want to use wp.media.string.image which will create a shortcode or
								// perhaps wp.html.string to at least to build the <img />
								state.trigger( 'replace', controller.image.toJSON() );

								// Restore and reset the default state
								controller.setState(controller.options.state);
								controller.reset();

							}
						}
					}
				})
			);

		},

		/**
		 * Replaces the image
		 *
		 * This functionality is only available to attachment,
		 * so we can safely assume this is one.
		 */
		replaceImage: function() {

			// Replace the attachment
			this.model.set({ attachment_id: this.image.attachment.id });
			this.model.attachment.set(this.image.attachment.attributes);

		}

	});

	/**
	 * "Editor" View
	 */
	Admin.view.Editor = Backbone.View.extend({

		/**
		 * Our view element
		 */
		el: '.wrap',

		/**
		 * Events
		 */
		events: {
			'click #add-slides':                '_addSlide',
			'click #select-all':                '_selectAll',
			'click #delete-slides':             '_clickBulkDelete',
			'click #save':                      '_clickSave',
			'click .toolbar .edit':             '_clickEdit',
			'click .toolbar .remove':           '_clickDelete',
			'click .show-advanced-options':     '_clickAdvancedOptions',
			'click .thumbnail':                 '_clickThumb',
			'click .select-mode-toggle-button': '_toggleMode',
			'click .sidebar-name':              '_toggleWidget'
		},
		
		/**
		 * Constructor
		 */
		initialize: function() {

			// Initiate our subviews
			this.subviews = {
				AddSlide: new Admin.frame.AddSlide(),
				Slides: new Admin.view.Slides({
					collection: this.collection
				})
			};

			// Bind our events
			this.collection.on('sync:done', this.render, this);
			this.collection.on('sync:done', this._enableSave, this);
			this.collection.on('sync:done', this._handleNoSlides, this);
			this.collection.on('add', this._handleNoSlides, this);
			this.collection.on('remove', this._handleNoSlides, this);
			this.subviews.AddSlide.on('insert', this._handleInsert, this);

			// Change number of columns on window resize
			$(window).on('resize', this._setColumns.bind(this));

			// Disable select mode
			this._selectMode = false;

			// Set number of columns
			this._setColumns();

			// Show the spinner
			this._showSpinner();

		},

		/**
		 * Shows the spinner
		 */
		_showSpinner: function() {

			this.$('#slides-browser').append('<div class="spinner"></div>');

		},

		/**
		 * Hides the spinner
		 */
		_hideSpinner: function() {

			this.$('#slides-browser .spinner').remove();

		},

		/**
		 * Enables the save button
		 */
		_enableSave: function() {

			this.$('#save').prop('disabled', false);

		},

		/**
		 * Handles save button click
		 */
		_clickSave: function() {

			this.$('#publishing-action .spinner').css({ 'display': 'block' });

		},

		/**
		 * Sets the appropriate number of columns
		 */
		_setColumns: function() {

			var content          = this.$('.media-frame-content'),
				previous_columns = content.attr('data-columns'),
				width            = content.width();

			// Continue if we have width
			if ( width ) {

				// Calculate the maximum number of columns we can fit
				var columns = Math.min(Math.round(width / 145), 12) || 1;

				// Change the number of columns if it's not the same as previously
				if ( ! previous_columns || previous_columns !== columns ) {
					content.attr('data-columns', columns);
				}

			}

		},

		/**
		 * Handles our slides hidden input when no slides exists
		 */
		_handleNoSlides: function() {

			if ( 0 == this.collection.length ) {
				this.$('form').prepend('<input type="hidden" id="slides" name="slides" value="[]" />');
			}
			else {
				this.$('input[name="slides"]').remove();
			}

		},

		/**
		 * Handles slide(s) insert from the "Add Slide" frame view
		 */
		_handleInsert: function(selection) {
		
			// Add to the collection
			this.collection.add(selection.models);

		},

		/**
		 * Handles a thumbnail click event
		 */
		_clickThumb: function(event) {

			event.preventDefault();

			// Handle click based on mode
			if ( ! this._selectMode ) {
				this._editSlide(event);
			}
			else {
				this._toggleSelect(event);
			}

		},

		/**
		 * Handles an "edit" button click event
		 */
		_clickEdit: function(event) {

			event.preventDefault();

			this._editSlide(event);

		},

		/**
		 * Handles a "delete" button click event
		 */
		_clickDelete: function(event) {

			event.preventDefault();

			if ( confirm( _easingsliderAdminL10n.warn ) ) {

				// Get the model ID
				var id = $(event.currentTarget).parents('.attachment').attr('data-id');

				// Delete the model from the collection
				this.collection.remove(id);

			}

		},

		/**
		 * Handles a bulk delete button click
		 */
		_clickBulkDelete: function(event) {

			event.preventDefault();

			if ( confirm( _easingsliderAdminL10n.warn ) ) {

				// Delete the slides
				this._deleteSlides(event);

				// Toggle mode
				this._toggleMode(event);

			}

		},

		/**
		 * Handles "advanced options" link click
		 */
		_clickAdvancedOptions: function(event) {

			event.preventDefault();

			// Get the advanced options
			var $options = $(event.currentTarget).parent().find('.advanced-options');

			// Find the closest advanced options and show it
			$options.toggleClass('hide');
			
		},

		/**
		 * Toggles between "Bulk Select" mode
		 */
		_toggleMode: function(event) {

			event.preventDefault();

			// Toggle mode
			if ( ! this._selectMode ) {
				this._selectMode = true;
			}
			else {
				this._selectMode = false;
			}

			// Toggle select mode class
			this.$('.attachment').removeClass('selected details');
			this.$('.media-frame').toggleClass('mode-select');
			this.$('.media-toolbar div *').toggleClass('hide');

		},

		/**
		 * Toggles selection of a slide on click
		 */
		_toggleSelect: function(event) {

			event.preventDefault()

			// Highlight the thumbnail
			$(event.currentTarget).parents('.attachment').toggleClass('selected details');

		},

		/**
		 * Toggles a sidebar settings widget metabox
		 */
		_toggleWidget: function(event) {

			event.preventDefault();

			var $widget =  $(event.currentTarget).parent(),
				$content = $widget.find('.sidebar-content');

			// Bail if this is a fixed widget
			if ( $widget.hasClass('fixed') ) {
				return;
			}

			// Close any open sidebar metaboxes
			this.$('.widgets-holder-wrap').each(function() {
				var $metabox = $(this);

				if ( ! $metabox.hasClass('fixed') ) {
					$metabox.find('.sidebar-content').slideUp(200, function() {
						$metabox.addClass('closed');
					});
				}
			});

			// Bail if the clicked widget is already open
			if ( ! $widget.hasClass('closed') ) {
				return;
			}

			// Open the sidebar metabox
			$content.slideDown(200, function() {
				$widget.removeClass('closed');
			});

		},

		/**
		 * Selects all of the slides
		 */
		_selectAll: function(event) {

			event.preventDefault();

			// Highlight all the thumbnails
			this.$('.attachment').addClass('selected details');

		},

		/**
		 * Deletes the currently selected slides
		 */
		_deleteSlides: function(event) {

			event.preventDefault();

			// Establish variables
			var collection = this.collection,
				models     = [];

			// Loop through each slide and remove selected
			this.$('.attachment').each(function(index) {

				// Check if this slide has been selected & add it to be removed if so.
				if ( $(this).hasClass('selected') ) {
					models.push(collection.at(index));
				}

			});

			// Remove the models from the collection
			this.collection.remove(models);

		},

		/**
		 * Opens the "Add Slide" frame view on click
		 */
		_addSlide: function(event) {

			event.preventDefault();

			// Open the frame
			this.addSlide();

			// Navigate router
			Admin.Router.navigate(_easingsliderAdminL10n.base_url + '&add=true');

		},

		/**
		 * Opens the "Add Slide" frame
		 */
		addSlide: function() {

			// Open the "Add Slide" frame
			this.subviews.AddSlide.open();

			return this;

		},

		/**
		 * Opens the "Edit Slide" frame on click
		 */
		_editSlide: function(event) {

			event.preventDefault();

			// Get the slide ID
			var id = $(event.currentTarget).parents('.attachment').attr('data-id');

			// Open the frame
			this.editSlide(id);

			// Navigate router
			Admin.Router.navigate(_easingsliderAdminL10n.base_url + '&slide=' + id);

		},

		/**
		 * Opens the "Edit Slide" frame
		 */
		editSlide: function(id) {

			// Create the frame
			this.subviews.EditSlide = new Admin.frame.EditSlide.get({
				collection: this.collection,
				model:      this.collection.get(id)
			});

			// Open the frame
			this.subviews.EditSlide.open();

			return this;

		},

		/**
		 * Adds a frame to "Add Slide"
		 */
		newAddSlideFrame: function(options) {

			var frame = this.subviews.AddSlide;

			// Add our states to the "Add Slide" frame
			frame.states.add([
				new Admin.controller.AddSlide.get(options)
			]);

			// Render content
			frame.on('content:render:'+ options.content, function() {

				// Create the view
				var view = new Admin.view.AddSlide.get({
					type:       options.content,
					model:      this.state().props,
					controller: this
				});

				// Set the view
				this.content.set(view);
				
			}, frame);

			// Render toolbar
			frame.on('toolbar:render:'+ options.toolbar, function() {

				// Create the toolbar
				var toolbar = new Admin.toolbar.AddSlide.get({
					type:       options.toolbar,
					controller: this
				});

				// Set the toolbar
				this.toolbar.set(toolbar);

			}, frame);

		},

		/**
		 * Renders the view
		 */
		render: function() {

			// Hide the spinner
			this._hideSpinner();

			// Render the subview
			var slides = this.subviews.Slides.render().el;

			// Add the slides to the view
			this.$('#slides-browser').append(slides);

			return this;

		}

	});

	/**
	 * "Slides" View
	 */
	Admin.view.Slides = Backbone.View.extend({

		/**
		 * Tagname
		 */
		tagName: 'ul',

		/**
		 * Attributes
		 */
		attributes: {
			'class':    'attachments ui-sortable',
			'tabindex': '-1'
		},

		/**
		 * Constructor
		 */
		initialize: function() {

			// Subviews
			this.subviews = [];

			// Bind our collection events
			this.collection.on('add', this.add, this);
			this.collection.on('remove', this.render, this);
			this.collection.on('reset', this.render, this);

			// Enable sorting
			this.$el.sortable({
				items:       '.attachment',
				containment: 'parent',
				tolerance:   'pointer',
				stop:        this._sort.bind(this)
			});

		},

		/**
		 * Sorts the view
		 */
		_sort: function(event, ui) {

			// Get the model
			var model = this.collection.get(ui.item.context.dataset.id);

			// Reposition the model in the collection
			this.collection.reposition(model, ui.item.index());

		},

		/**
		 * Adds a subview
		 */
		add: function(model) {

			// Render and add the slide to the view
			var view = new Admin.view.Slide({
				model: model
			});

			// Add the subview
			this.subviews.push(view);

			// Render and append subview to this view
			this.$el.append(view.render().$el);

			return this;

		},

		/**
		 * Renders the view
		 */
		render: function() {

			this.subviews = [];

			// Empty the view
			this.$el.empty();

			// Render each subview
			_.each(this.collection.models, function(model) {
				this.add(model);
			}, this);

			return this;

		}

	});

	/**
	 * "Slide" View
	 */
	Admin.view.Slide = Backbone.View.extend({

		/**
		 * Tagname
		 */
		tagName: 'li',

		/**
		 * Attributes
		 */
		attributes: function() {
			return {
				'role':     'checkbox',
				'class':    'attachment save-ready',
				'tabindex': '0',
				'data-id':  this.model.id
			};
		},

		/**
		 * Template for this view
		 */
		template: wp.media.template('easingslider-slide'),

		/**
		 * Constructor
		 */
		initialize: function() {

			// Bind events
			this.model.on('change', this._setData, this);
			this.model.on('change:id', this._updateID, this);
			this.model.on('change:url', this.render, this);
			this.model.on('change:poster', this.render, this);
			this.model.on('change:attachment_id', this.render, this);

			// Bind additional attachment events if appropriate
			if ( this.model.attachment ) {
				this.model.attachment.on('change', this.render, this);
			}

		},

		/**
		 * Sets our data
		 */
		_setData: function() {

			// Set data in hidden input
			this.$('input[name="slides[]"]').val(JSON.stringify(this.model.attributes));

		},

		/**
		 * Update our ID attribute
		 */
		_updateID: function(model, value) {

			// Update attrbitue on DOM
			this.$el.attr('data-id', value);

			// Update attribute on view object
			this.attributes['data-id'] = value;

		},

		/**
		 * Renders the view
		 */
		render: function() {

			var data = { model: this.model.toJSON() };

			// If our model has an attachment, add it to the data.
			if ( this.model.attachment ) {
				data.attachment = this.model.attachment.toJSON();
			}

			// Generate the template
			this.$el.html(this.template(data));

			return this;

		}

	});

	/**
	 * Returns the appropriate "Add Slide" view
	 */
	Admin.view.AddSlide = {

		/**
		 * Get the appropriate view
		 */
		get: function(options) {

			if ( 'undefined' !== typeof Admin.view.AddSlide[options.type] ) {
				return new Admin.view.AddSlide[options.type](options);
			}

			return new Admin.view.AddSlide.base(options);

		}

	};

	/**
	 * Base "Add Slide" View
	 */
	Admin.view.AddSlide.base = wp.media.View.extend();

	/**
	 * Image "Add Slide" View
	 */
	Admin.view.AddSlide.image = wp.media.view.AttachmentsBrowser.extend();

	/**
	 * Returns the appropriate "Edit Slide" view
	 */
	Admin.view.EditSlide = {

		/**
		 * Get the appropriate view
		 */
		get: function(options) {

			if ( 'undefined' !== typeof Admin.view.EditSlide[options.type] ) {
				return new Admin.view.EditSlide[options.type](options);
			}

			return new Admin.view.EditSlide.base(options);

		}

	};

	/**
	 * Base "Edit Slide" View
	 */
	Admin.view.EditSlide.base = wp.media.view.Settings.extend({

		/**
		 * Classname
		 */
		className: 'edit-attachment-frame attachment-details mode-select',

		/**
		 * Template for this view
		 */
		template: wp.media.template('easingslider-edit-slide'),

		/**
		 * Constructor
		 */
		initialize: function() {

			// Call parent constructor
			wp.media.view.Settings.prototype.initialize.apply(this, arguments);

			// Bind events
			this.model.on('change:link', this.toggleLinkSettings, this);
			this.model.on('change:link', this.updateLinkTo, this);

		},

		/**
		 * Prepares the view data
		 */
		prepare: function() {

			var attachment = false;

			// Check model for attachment
			if ( this.model.attachment ) {
				attachment = this.model.attachment.toJSON();
			}

			return _.defaults({
				model: this.model.toJSON(),
				attachment: attachment
			}, this.options );
			
		},

		/**
		 * Adds support for "Link" options show/hide
		 */
		toggleLinkSettings: function() {

			if ( 'none' === this.model.get('link') ) {
				this.$('.link-options').addClass('hide');
			} else {
				this.$('.link-options').removeClass('hide');
			}

		},

		/**
		 * Handles "Link To" option
		 */
		updateLinkTo: function() {

			var linkTo = this.model.get('link'),
				$input = this.$('.link-to-custom');

			if ( this.model.attachment ) {

				if ( 'none' === linkTo || 'embed' === linkTo || ( ! this.model.attachment && 'custom' !== linkTo ) ) {
					$input.addClass('hidden');
					return;
				}

				if ( this.model.attachment ) {
					if ( 'post' === linkTo ) {
						$input.val(this.model.attachment.get('link'));
					} else if ( 'file' === linkTo ) {
						$input.val(this.model.attachment.get('url'));
					} else if ( ! this.model.get('linkUrl') ) {
						$input.val('http://');
					}

					$input.prop('readonly', 'custom' !== linkTo);
				}

				$input.removeClass('hidden');

				// If the input is visible, focus and select its contents.
				if ( ! wp.media.isTouchDevice && $input.is(':visible') ) {
					$input.focus()[0].select();
				}

			}

		},

		/**
		 * Renders the view
		 */
		render: function() {

			// Call parent
			wp.media.view.Settings.prototype.render.apply(this, arguments);

			// Set toggles
			this.toggleLinkSettings();
			this.updateLinkTo();

			return this;

		}

	});

	/**
	 * Image "Edit Slide" View
	 */
	Admin.view.EditSlide.image = Admin.view.EditSlide.base.extend({

		/**
		 * Events
		 */
		events: _.defaults({
			'click .edit-attachment':    'editAttachment',
			'click .replace-attachment': 'replaceAttachment'
		}, Admin.view.EditSlide.base.prototype.events),

		/**
		 * Constructor
		 */
		initialize: function() {

			// Call parent constructor
			Admin.view.EditSlide.base.prototype.initialize.apply(this, arguments);

			// Bind events
			this.model.on('change:url', this.updateImage, this);

		},

		/**
		 * Changes to "Edit" state
		 */
		editAttachment: function(event) {

			// Get current state
			var editState = this.controller.states.get('edit-image');

			// Continue if editing is allowed and we've an edit state
			if ( window.imageEdit && editState ) {
				event.preventDefault();
				editState.set('image', this.model.attachment);
				this.controller.setState('edit-image');
			}

		},

		/**
		 * Changes to "Replace" state
		 */
		replaceAttachment: function(event) {

			event.preventDefault();

			this.controller.setState('replace-image');

		},

		/**
		 * Updates the image preview (URL images only)
		 */
		updateImage: function() {

			this.$('.details-image').attr('src', this.model.get('url'));

		}

	});

	/**
	 * Returns the appropriate "Add Slide" toolbar
	 */
	Admin.toolbar.AddSlide = {

		/**
		 * Get the appropriate toolbar
		 */
		get: function(options) {

			if ( 'undefined' !== typeof Admin.toolbar.AddSlide[options.type] ) {
				return new Admin.toolbar.AddSlide[options.type](options);
			}

			return new Admin.toolbar.AddSlide.base(options);

		}

	};

	/**
	 * Base "Add Slide" Toolbar
	 */
	Admin.toolbar.AddSlide.base = wp.media.view.Toolbar.extend();

	/**
	 * Router
	 */
	Admin.router = Backbone.Router.extend({

		/**
		 * Routes
		 */
		routes: {
			'admin.php?page=:page&edit=:id&slide=:slide': 'editSlide',
			'admin.php?page=:page&edit=:id&add=true':     'addSlide',
			'admin.php?page=:page&edit=:id':              'editor',
			'admin.php?page=easingslider-add-new':        'editor'
		},

		/**
		 * Constructor
		 */
		initialize: function() {

			// Get the slides
			var slides = ( typeof window.slides === 'undefined' ) ? '[]' : window.slides;

			// Initate our view
			this.view = new Admin.view.Editor({
				collection: new Admin.collection.Slides(JSON.parse(slides))
			});

		},

		/**
		 * Shows the "Edit Slide" frame
		 */
		editSlide: function(page, id, slide) {

			// Load the admin editor
			this.editor();

			// Once the colleciton has been synced, open the edit slide frame
			this.view.collection.on('sync:done', function() {
				this.view.editSlide(slide);
			}, this);

			return this;

		},

		/**
		 * Shows the "Add Slide" frame
		 */
		addSlide: function(page, id) {

			// Load the admin editor
			this.editor();

			// Once the colleciton has been synced, open the add slide frame
			this.view.collection.on('sync:done', function() {
				this.view.addSlide();
			}, this);

			return this;

		},

		/**
		 * Shows the admin editor
		 */
		editor: function(page) {

			// Sync the collection & get the ball rolling!
			this.view.collection.sync();

			return this;

		}

	});

	/**
	 * Let's go!
	 */
	$(document).ready(function() {

		// Initiate the router
		Admin.Router = new Admin.router();

		// Start the history
		Backbone.history.start({
			root:       window._easingsliderAdminL10n.admin_url,
			pushState:  true,
		});

		// Handly delete class
		$('.delete').each(function() {
			$(this).on('click', function() {
				if ( ! confirm( _easingsliderAdminL10n.warn ) ) {
					return false;
				}
			});
		});

		// Change our slider type
		$('select[name="type"]').on('change', function() {

			// Hide options
			$('*[data-type]').addClass('hidden');
			$('*[data-type="'+ this.value +'"]').removeClass('hidden');

			// Hide primary toolbar when not using slides from "Media"
			if ( 'media' != this.value ) {
				$('.media-toolbar-primary').addClass('hidden');
			} else {
				$('.media-toolbar-primary').removeClass('hidden');
			}

		});

		/**
		 * Handle addon activation
		 */
		$(document).on('click', '.js-activate-addon', function(event) {
			event.preventDefault();

			// Establish variables
			var $button  = $(this);
			var $el      = $button.parents('.addon-status');
			var $message = $el.find('.status-message');

			// Remove errors
			$('.action-error').remove();

			// Tell the user what's happening
			$button.text(_easingsliderAdminL10n.buttons.activating);

			// Process the Ajax to perform the activation.
			var options = {
				url: ajaxurl,
				type: 'post',
				async: true,
				cache: false,
				dataType: 'json',
				data: {
					action: 'easingslider_activate_addon',
					nonce:  _easingsliderAdminL10n.nonces.activate,
					plugin: $button.attr('data-plugin')
				},
				success: function(response) {

					// If there is a WP Error instance, output it here and quit
					if ( response && true !== response ) {
						$button.after('<div class="action-error error"><strong>'+ response.error +'</strong></div>');
						return false;
					}

					// The Ajax request was successful, update the button.
					$button
						.text(_easingsliderAdminL10n.buttons.deactivate)
						.removeClass('js-activate-addon')
						.addClass('js-deactivate-addon');

					// Update message
					$message.text(_easingsliderAdminL10n.messages.active);

					// Change status
					$el.removeClass('is-inactive').addClass('is-active');

				},
				error: function(xhr, textStatus, event) {
					return false;
				}
			};

			// Perform ajax request
			$.ajax(options);
		});

		/**
		 * Handle addon deactivation
		 */
		$(document).on('click', '.js-deactivate-addon', function(event) {
			event.preventDefault();

			// Establish variables
			var $button  = $(this);
			var $el      = $button.parents('.addon-status');
			var $message = $el.find('.status-message');

			// Remove errors
			$('.action-error').remove();

			// Tell the user what's happening
			$button.text(_easingsliderAdminL10n.buttons.deactivating);

			// Process the Ajax to perform the activation.
			var options = {
				url: ajaxurl,
				type: 'post',
				async: true,
				cache: false,
				dataType: 'json',
				data: {
					action: 'easingslider_deactivate_addon',
					nonce:  _easingsliderAdminL10n.nonces.deactivate,
					plugin: $button.attr('data-plugin')
				},
				success: function(response) {

					// If there is a WP Error instance, output it here and quit
					if ( response && true !== response ) {
						$button.after('<div class="action-error error"><strong>'+ response.error +'</strong></div>');
						return false;
					}

					// The Ajax request was successful, update the button.
					$button
						.text(_easingsliderAdminL10n.buttons.activate)
						.removeClass('js-deactivate-addon')
						.addClass('js-activate-addon');

					// Update message
					$message.text(_easingsliderAdminL10n.messages.inactive);

					// Change status
					$el.removeClass('is-active').addClass('is-inactive');

				},
				error: function(xhr, textStatus, event) {
					return false;
				}
			};

			// Perform ajax request
			$.ajax(options);
		});

		/**
		 * Handle addon installation
		 */
		$(document).on('click', '.js-install-addon', function(event) {
			event.preventDefault();

			// Establish variables
			var $button  = $(this);
			var $addons  = $button.parents('.addons');
			var $status  = $button.parents('.addon-status');
			var $message = $status.find('.status-message');

			// Remove errors
			$('.action-error').remove();

			// Tell the user what's happening
			$button.text(_easingsliderAdminL10n.buttons.installing);

			// Process the Ajax to perform the activation.
			var options = {
				url: ajaxurl,
				type: 'post',
				async: true,
				cache: false,
				dataType: 'json',
				data: {
					action: 'easingslider_install_addon',
					nonce:  _easingsliderAdminL10n.nonces.install,
					plugin: $button.attr('data-plugin')
				},
				success: function(response) {

					// If there is a WP Error instance, output it here and quit
					if ( response.error ) {
						$button.after('<div class="action-error error"><strong>'+ response.error +'</strong></div>');
						return false;
					}

					// Ask for credentials if needed
					if ( response.form ) {

						// Hide addons temporarily
						$addons.hide();

						// Display the form to ask for user credentials
						$addons.after('<div class="action-error error">' + response.form + '</div>');

						// Add a disabled attribute the install button
						$button.attr('disabled', true);

						// Act when "Proceed" with FTP credentials button is clicked
						$(document).on('click', '#upgrade', function(event) {
							event.preventDefault();

							// Get FTP credentials
							var $proceedButton = $(this);
							var $connectForm   = $proceedButton.parent().parent().parent().parent();
							var hostname       = $proceedButton.parent().parent().find('#hostname').val();
							var username       = $proceedButton.parent().parent().find('#username').val();
							var password       = $proceedButton.parent().parent().find('#password').val();

							// Now let's attempt the Ajax request again
							$.ajax({
								url: ajaxurl,
								type: 'post',
								async: true,
								cache: false,
								dataType: 'json',
								data: {
									action:   'easingslider_install_addon',
									nonce:    _easingsliderAdminL10n.nonces.install,
									plugin:   $button.attr('data-plugin'),
									hostname: hostname,
									username: username,
									password: password
								},
								success: function(response) {

									// If there is a WP Error instance, output it here and quit the script.
									if ( response.error ) {
										$button
											.attr('data-plugin', response.plugin)
											.text(_easingsliderAdminL10n.buttons.activate)
											.removeClass('js-install-addon')
											.addClass('js-activate-addon');

										return false;
									}

									if ( response.form ) {
										$addons.after('<div class="action-error error"><p>'+ _easingsliderAdminL10n.ftp_error +'</p></div>');
										return false;
									}

									// Hide the FTP connection form
									$connectForm.remove();

									// Show addons again
									$addons.show();

									// Update message
									$message.text(_easingsliderAdminL10n.messages.inactive);

									// Change status
									$status.removeClass('not-installed').addClass('is-inactive');

								},
								error: function(xhr, textStatus, event) {
									return false;
								}
							});
						});

						// No need to continue.
						return;

					}

					// The Ajax request was successful, update the button.
					$button
						.attr('data-plugin', response.plugin)
						.text(_easingsliderAdminL10n.buttons.activate)
						.removeClass('js-install-addon')
						.addClass('js-activate-addon');

					// Update message
					$message.text(_easingsliderAdminL10n.messages.inactive);

					// Change status
					$status.removeClass('not-installed').addClass('is-inactive');

				},
				error: function(xhr, textStatus, event) {
					return false;
				}
			};

			// Perform ajax request
			$.ajax(options);
		});

	});

})(jQuery);
