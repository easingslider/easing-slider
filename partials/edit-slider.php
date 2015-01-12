<div class="wrap">
	<form id="slider" name="slider" action="admin.php?page=<?php echo $page; if ( isset( $_GET['edit'] ) ) { echo "&amp;edit={$_GET['edit']}"; } ?>" method="post">
		<?php
			/**
			 * Security nonce field
			 */
			wp_nonce_field( 'save' );
		?>

		<h2>
			<?php if ( isset( $_GET['edit'] ) ) : ?>
				<?php _e( 'Edit Slider', 'easingslider' ); ?>
				<a href="admin.php?page=easingslider_publish_slider" class="add-new-h2"><?php _e( 'Add New', 'easingslider' ); ?></a>
			<?php else : ?>
				<?php _e( 'Add New Slider', 'easingslider' ); ?>
			<?php endif; ?>
		</h2>

		<div class="clearfix">
			<div class="toolbar-container">
				<div class="media-toolbar wp-filter">
					<div class="media-toolbar-secondary title-form">
						<label for="post_title"><?php _e( 'Name:', 'easingslider' ); ?></label>
						<input type="text" name="post_title" id="post_title" autocomplete="off" placeholder="<?php _e( 'Enter a slider name', 'easingslider' ); ?>" value="<?php echo esc_attr( $slider->post_title ); ?>">
						<a href="#" id="select-all" class="button media-button button-large hidden"><?php _e( 'Select All', 'easingslider' ); ?></a>
					</div>

					<div class="media-toolbar-primary">
						<label for="randomize" class="randomize">
							<input type="hidden" name="general[randomize]" value="false">
							<input type="checkbox" id="randomize" name="general[randomize]" value="true" <?php checked( $slider->general->randomize, true ); ?>><span style="display: inline;"><?php _e( 'Randomize the slide order', 'easingslider' ); ?></span>
						</label>
						<a href="#" id="add-slides" class="button media-button button-primary button-large"><?php _e( 'Add Slides', 'easingslider' ); ?></a>
						<a href="#" id="bulk-select" class="button media-button button-large select-mode-toggle-button"><?php _e( 'Bulk Select', 'easingslider' ); ?></a>
						<a href="#" id="cancel-select" class="button media-button button-large select-mode-toggle-button hidden"><?php _e( 'Cancel Selection', 'easingslider' ); ?></a>
						<a href="#" id="delete-slides" class="button media-button button-primary button-large hidden"><?php _e( 'Delete Selected', 'easingslider' ); ?></a>
					</div>
				</div>
			</div>

			<div class="settings-container">
				<?php
					/**
					 * Keep our settings sidebar in a separate file for clarity. Woohoo!
					 */
					require 'edit-slider_sidebar.php';
				?>
			</div>

			<div class="slides-container">
				<div class="media-frame wp-core-ui mode-grid hide-menu">
					<div class="media-frame-content" data-columns="5">
						<div id="slides-browser" class="attachments-browser hide-sidebar">
							<?php
								/**
								 * Slides are dynamically added using Backbone.js here.
								 */
							?>
						</div>
					</div>
				</div>
			</div>
		</div>

		<?php
			/**
			 * This ensures that the slide's JSON is encoded correctly.
			 * Using PHP JSON encode can cause magic quote issues.
			*/
		?>
		<script type="text/javascript">var slides = '<?php echo addslashes( json_encode( $slider->slides ) ); ?>';</script>
	</form>
</div>