<script type="text/html" id="tmpl-easingslider-edit-slide">
	<div class="attachment-media-view {{ data.model.orientation }}">
		<div class="thumbnail thumbnail-{{ data.type }}">
			<# if ( 'image' == data.model.type ) { #>
				<# if ( data.attachment ) { #>
					<img class="details-image" src="{{ data.attachment.url }}" draggable="false" />

					<# if ( window.imageEdit ) { #>
						<div class="actions">
							<input type="button" class="edit-attachment button" value="<?php _e( 'Edit Original' ); ?>" />
							<input type="button" class="replace-attachment button" value="<?php _e( 'Replace' ); ?>" />
						</div>
					<# } #>
				<# } else { #>
					<img class="details-image" src="{{ data.model.url }}" draggable="false" />
				<# } #>
			<# } #>
			
			<?php
				/**
				 * This is for our extensions and their custom slide preview
				 */
				do_action( 'easingslider_print_edit_slide_preview' );
			?>
		</div>
	</div>

	<div class="attachment-info">
		<# if ( 'image' == data.model.type ) { #>
			<div class="settings">
				<h3><?php _e( 'Link Settings', 'easingslider' ); ?></h3>

				<label class="setting link-to">
					<span class="name"><?php _e( 'Link To', 'easingslider' ); ?></span>
					<select data-setting="link">
						<option value="none">
							<?php _e( 'None', 'easingslider' ); ?>
						</option>
						<option value="custom">
							<?php _e( 'Custom URL', 'easingslider' ); ?>
						</option>
						<# if ( data.attachment ) { #>
							<option value="file">
								<?php _e( 'Media File', 'easingslider' ); ?>
							</option>
							<option value="post">
								<?php _e( 'Attachment Page', 'easingslider' ); ?>
							</option>
						<# } else { #>
							<option value="file">
								<?php _e( 'Image URL', 'easingslider' ); ?>
							</option>
						<# } #>
					</select>
					<input type="text" class="link-to-custom" data-setting="linkUrl" />
				</label>

				<?php
					/**
					 * This is for our extensions to add their own link settings
					 */
					do_action( 'easingslider_print_link_to_settings' );
				?>
			</div>

			<div class="settings">
				<h3><?php _e( 'Image Settings', 'easingslider' ); ?></h3>

				<label class="setting title">
					<span class="name"><?php _e( 'Title Attribute', 'easingslider' ); ?></span>
					<input type="text" data-setting="title" />
				</label>

				<label class="setting alt-text">
					<span class="name"><?php _e( 'Alternative Text', 'easingslider' ); ?></span>
					<input type="text" data-setting="alt" />
				</label>
			</div>
		<# } #>
			
		<?php
			/**
			 * This is for our extensions and their custom slide settings
			 */
			do_action( 'easingslider_print_edit_slide_settings' );
		?>
	</div>
</script>