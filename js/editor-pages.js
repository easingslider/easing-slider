/**
 * Globalize
 */
window.EasingSlider = window.EasingSlider || {
	Editor: {
		models:      {},
		collections: {},
		controllers: {},
		frames:      {},
		views:       {},
		router:      {},
		Router:      {}
	}
};

/**
 * Where the magic happens!
 */
(function($) {

	var Editor = window.EasingSlider.Editor;

	/**
	 * Our slide types
	 */
	Editor.models.Slide = {

		/**
		 * Returns the appropriate "Slide" model
		 */
		get: function(attributes) {
			return new Editor.models.Slide[attributes.type](attributes);
		}

	};

	/**
	 * Image "Slide" model
	 */
	Editor.models.Slide.image = Backbone.Model.extend({

		/**
		 * Attachment
		 */
		attachment: false,

		/**
		 * Defaults
		 */
		defaults: _.defaults({
			attachment_id:   null,
			type:            'image',
			alt:             '',
			aspectRatio:     null,
			link:            'none',
			linkUrl:         '',
			linkTargetBlank: false,
			title:           '',
			url:             null
		}, wp.media.model.PostImage.prototype.defaults)

	});

	/**
	 * Our slides collection
	 */
	Editor.collections.Slides = Backbone.Collection.extend({

		/**
		 * The models for this collection are polymorphic,
		 * so the model to add is determined by function.
		 *
		 * Don't be fooled, Editor.models.Slide.get is just a function (not a model).
		 */
		model: Editor.models.Slide.get,

		/**
		 * Constructor
		 */
		initialize: function() {

			// Bind our collection events
			this.on('add',    this._resetIDs, this);
			this.on('remove', this._resetIDs, this);
			this.on('reset',  this._resetIDs, this);

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
	 * Our "Add Slide" controllers for various slide types
	 */
	Editor.controllers.AddSlide = {

		/**
		 * Returns the appropriate "Add Slide" controller, based on the type of slide provided
		 */
		get: function(options) {
			return new Editor.controllers.AddSlide[options.type](options);
		}

	};

	/**
	 * "Add Slide" controller for images
	 */
	Editor.controllers.AddSlide.image = wp.media.controller.Library.extend();

	/**
	 * Our "Edit Slide" controllers for various slide types
	 */
	Editor.controllers.EditSlide = {

		/**
		 * Returns the appropriate "Edit Slide" controller, based on the type of slide provided
		 */
		get: function(options) {
			return new Editor.controllers.EditSlide[options.type](options);
		}

	};

	/**
	 * "Edit Slide" controller for images
	 */
	Editor.controllers.EditSlide.image = wp.media.controller.ImageDetails.extend({

		/**
		 * Defaults
		 */
		defaults: _.defaults({
			id:    'edit-slide',
			title: _easingsliderEditorL10n.media_upload.title
		}, wp.media.controller.ImageDetails.prototype.defaults )

	});

	/**
	 * "Add Slide" WordPress Media Frame
	 */
	Editor.frames.AddSlide = wp.media.view.MediaFrame.Post.extend({

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

			// Bind Handlers
			this.on('content:render:browse', this.removeSidebar, this);
			this.on('toolbar:create:insert-slide', this.createToolbar, this);
			this.on('toolbar:render:insert-slide', this.insertToolbar, this);

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
			contentRegion.view = new Editor.views.AddSlide.image({
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
		 * Creates our states
		 */
		createStates: function() {

			// Add the default states
			this.states.add([
				new Editor.controllers.AddSlide.image({
					id: 'insert',
					type: 'image',
					title: _easingsliderEditorL10n.media_upload.image_from_media,
					priority: 20,
					toolbar: 'insert-slide',
					filterable: false,
					library: wp.media.query({ type: 'image' }),
					multiple: true,
					editable: true,
					allowLocalEdits: true,
					displaySettings: false,
					displayUserSettings: true
				})
			]);

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
		 * Adds our "Insert into Slider" toolbar
		 */
		insertToolbar: function(view) {

			var controller = this;

			// Add the toolbar to our provided view
			view.set('insert-slide', {
				style: 'primary',
				priority: 80,
				text: _easingsliderEditorL10n.media_upload.insert_into_slider,
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
				var slide = new Editor.models.Slide.get({
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

		}

	});

	/**
	 * Our "Edit Slide" frames for various slide types
	 */
	Editor.frames.EditSlide = {

		/**
		 * Returns the appropriate "Edit Slide" frame, based on the type of slide provided
		 */
		get: function(options) {
			return new Editor.frames.EditSlide[options.model.get('type')](options);
		}

	};

	/**
	 * Frame for editing an "Image" slide
	 */
	Editor.frames.EditSlide.image = wp.media.view.MediaFrame.ImageDetails.extend({

		/**
		 * Constructor
		 */
		initialize: function() {
		
			// Set the image
			this.image = new wp.media.model.PostImage(this.model.attributes);

			// Set our options
			this.options.state     = 'edit-slide';
			this.options.selection = new wp.media.model.Selection(this.image.attachment, { multiple: false });

			// Call parent constructor
			wp.media.view.MediaFrame.Select.prototype.initialize.apply(this, arguments);

			// Bind events
			this.on('content:render:browse', this.removeSidebar, this);
			this.state('edit-slide').on('update', this.updateSettings, this);
			this.state('replace-image').on('replace', this.replaceImage, this);

		},

		/**
		 * Creates our states
		 */
		createStates: function() {

			// Add our "Edit Slide" state
			this.states.add([
				new Editor.controllers.EditSlide.get({
					type:     'image',
					image:    this.image,
					editable: false
				})
			]);

			// Call parent states
			wp.media.view.MediaFrame.ImageDetails.prototype.createStates.apply(this, arguments);

		},

		/**
		 * Replaces the "Image Details" section with our "Edit Slide" instead.
		 *
		 * Bit of a hack, but it works.
		 */
		imageDetailsContent: function(options) {

			// Initiate the view
			options.view = new Editor.views.EditSlide.get({
				type:       'image',
				controller: this,
				model:      this.state().image,
				attachment: this.state().image.attachment
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
		 * Applies a setting change to the model
		 */
		updateSettings: function() {

			var data = {};

			// Gather the settings
			this.$('*[data-setting]').each(function() {
				
				// Handle setting
				if ( 'checkbox' == this.type ) {
					data[this.dataset.setting] = ( this.checked ) ? this.value : false;
				}
				else {
					data[this.dataset.setting] = this.value;
				}

			});

			// Set the model data
			this.model.set(data);

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
	 * Admin view
	 */
	Editor.views.Admin = Backbone.View.extend({

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
				AddSlide: new Editor.frames.AddSlide(),
				AdminSlides: new Editor.views.AdminSlides({
					collection: this.collection
				})
			};

			// Bind our events
			this.collection.on('sync:done', this.render, this);
			this.collection.on('sync:done', this._enableSave, this);
			this.subviews.AddSlide.on('insert', this._handleInsert, this);

			// Change number of columns on window resize
			$(window).on('resize', this._setColumns.bind(this));

			// Disable select mode
			this._selectMode = false;

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

			if ( confirm( _easingsliderEditorL10n.warn ) ) {

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

			if ( confirm( _easingsliderEditorL10n.warn ) ) {

				// Delete the slides
				this._deleteSlides(event);

				// Toggle mode
				this._toggleMode(event);

			}

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
			this.$('.media-toolbar div *').toggleClass('hidden');

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

		},

		/**
		 * Opens the "Edit Slide" frame
		 */
		editSlide: function(id) {

			// Create the frame
			this.subviews.EditSlide = new Editor.frames.EditSlide.get({
				model: this.collection.get(id)
			});

			// Open the frame
			this.subviews.EditSlide.open();

			return this;

		},

		/**
		 * Renders the view
		 */
		render: function() {

			// Hide the spinner
			this._hideSpinner();

			// Render the subview
			var slides = this.subviews.AdminSlides.render().el;

			// Add the slides to the view
			this.$('#slides-browser').append(slides);

			return this;

		}

	});

	/**
	 * Admin "Slides" view
	 */
	Editor.views.AdminSlides = Backbone.View.extend({

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
			var view = new Editor.views.AdminSlide({
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
	 * Admin "Slide" view
	 */
	Editor.views.AdminSlide = Backbone.View.extend({

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
	 * Our "Add Slide" views for various slide types
	 */
	Editor.views.AddSlide = {

		/**
		 * Returns the appropriate "Add Slide" view, based on the type of slide provided
		 */
		get: function(options) {
			return new Editor.views.AddSlide[options.type](options);
		}

	};

	/**
	 * Our "Add Slide" view for images
	 */
	Editor.views.AddSlide.image = wp.media.view.AttachmentsBrowser.extend();

	/**
	 * Our "Edit Slide" views for various slide types
	 */
	Editor.views.EditSlide = {

		/**
		 * Returns the appropriate "Edit Slide" view, based on the type of slide provided
		 */
		get: function(options) {
			return new Editor.views.EditSlide[options.type](options);
		}

	};

	/**
	 * "Edit Slide" view for images
	 */
	Editor.views.EditSlide.image = wp.media.view.ImageDetails.extend({

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

			console.log(this.model.get('linkTargetBlank'));

			// Call parent constructor
			wp.media.view.ImageDetails.prototype.initialize.apply(this, arguments);

			// Bind events
			this.model.on('change:url', this._updateImage, this);

		},

		/**
		 * Updates the image preview (URL images only)
		 */
		_updateImage: function() {

			this.$('.details-image').attr('src', this.model.get('url'));

		}

	});

	/**
	 * Router
	 */
	Editor.router = Backbone.Router.extend({

		/**
		 * Routes
		 */
		routes: {
			'admin.php?page=:page&edit=:id&change=:slide': 'changeSlide',
			'admin.php?page=:page&edit=:id&slide=:slide':  'editSlide',
			'admin.php?page=:page&edit=:id':               'edit',
			'admin.php?page=:page':                        'edit'
		},

		/**
		 * Shows the change slide editor within the admin editor
		 */
		changeSlide: function(page, id, slide) {

			// Load the admin view
			this.edit();

			// Once the colleciton has been synced, open the edit slide frame
			this.AdminView.collection.on('sync:done', function() {
				this.AdminView.openChangeSlideFrame(slide);
			}, this);

			return this;

		},

		/**
		 * Shows the slide editor within the admin editor
		 */
		editSlide: function(page, id, slide) {

			// Load the admin view
			this.edit();

			// Once the colleciton has been synced, open the edit slide frame
			this.AdminView.collection.on('sync:done', function() {
				this.AdminView.openEditSlideFrame(slide);
			}, this);

			return this;

		},

		/**
		 * Shows the admin editor
		 */
		edit: function(page) {

			// Bail if we don't have slides
			if ( ! window.slides ) {
				return this;
			}

			// Initate our admin view
			this.AdminView = new Editor.views.Admin({
				collection: new Editor.collections.Slides(JSON.parse(window.slides))
			});

			// Sync the collection & get the ball rolling!
			this.AdminView.collection.sync();

			return this;

		}

	});

	/**
	 * Let's go!
	 */
	$(document).ready(function() {

		// Initiate the router
		Editor.Router = new Editor.router();

		// Start the history
		Backbone.history.start({
			root:       window._wpMediaGridSettings.adminUrl,
			pushState:  true,
		});

		// Handly delete class
		$('.delete').each(function() {
			$(this).on('click', function() {
				if ( ! confirm( _easingsliderEditorL10n.warn ) ) {
					return false;
				}
			});
		});

	});

})(jQuery);