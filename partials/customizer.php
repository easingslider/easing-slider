<div id="customize-container" class="customize-container">
	<div class="wp-full-overlay expanded">
		<form id="customize-controls" action="admin.php?page=<?php echo esc_attr( $page ); if ( isset( $id ) ) { echo "&amp;edit=". esc_attr( $id ) .""; } ?>" method="post" class="wrap wp-full-overlay-sidebar">
			<div id="customize-header-actions" class="wp-full-overlay-header">
				<input type="submit" name="save" id="save" class="button button-primary save" value="<?php _e( 'Save', 'easingslider' ); ?>" />
				<span class="spinner"></span>
				<a class="customize-controls-close" href="admin.php?page=easingslider_edit_sliders">
					<span class="screen-reader-text"><?php _e( 'Close', 'easingslider' ); ?></span>
				</a>
				<span class="control-panel-back" tabindex="-1">
					<span class="screen-reader-text"><?php _e( 'Back', 'easingslider' ); ?></span>
				</span>
			</div>

			<div class="wp-full-overlay-sidebar-content" tabindex="-1">
				<div id="customize-info" class="accordion-section customize-section">
					<div class="accordion-section-title customize-section-title" aria-label="Theme Customizer Options" tabindex="0">
						<span class="preview-notice"><?php printf( __( 'You are customizing <strong class="theme-name">%s</strong>', 'easingslider' ), $slider->post_title ); ?></span>
						<p>
							<span class="preview-notice"><?php _e( 'Change to another slider', 'easingslider' ); ?></span>
							<select name="slider_id" id="change-slider" class="widefat">
								<?php foreach ( $sliders as $_slider ) : ?>
									<option value="<?php echo esc_attr( $_slider->ID ); ?>" <?php selected( $slider->ID, $_slider->ID ); ?>><?php echo esc_html( $_slider->post_title ); ?></option>
								<?php endforeach; ?>
							</select>
						</p>
					</div>
				</div>
				<div id="customize-theme-controls" class="accordion-container">
					<ul>
						<li class="control-section accordion-section customize-section">
							<h3 class="accordion-section-title customize-section-title" tabindex="0" title=""><?php _e( 'Next & Previous Arrows', 'easingslider' ); ?></h3>
							<ul class="accordion-section-content customize-section-content">
								<li class="customize-control customize-control-text">
									<label>
										<span class="customize-control-title"><?php _e( '"Next" Arrow Image', 'easingslider' ); ?></span>
										<input type="text" name="arrows[next]" data-selector=".easingslider-next" data-property="background-image" value="<?php echo esc_attr( $slider->customizations->arrows->next ); ?>">
									</label>
								</li>
								<li class="customize-control customize-control-text">
									<label>
										<span class="customize-control-title"><?php _e( '"Previous" Arrow Image', 'easingslider' ); ?></span>
										<input type="text" name="arrows[prev]" data-selector=".easingslider-prev" data-property="background-image" value="<?php echo esc_attr( $slider->customizations->arrows->prev ); ?>">
									</label>
								</li>
								<li class="customize-control customize-control-text">
									<label>
										<span class="customize-control-title"><?php _e( 'Width', 'easingslider' ); ?></span>
										<input type="number" min="0" step="1" name="arrows[width]" style="width: 90%" data-selector=".easingslider-arrows" data-property="width" value="<?php echo esc_attr( $slider->customizations->arrows->width ); ?>"> px
									</label>
								</li>
								<li class="customize-control customize-control-text">
									<label>
										<span class="customize-control-title"><?php _e( 'Height', 'easingslider' ); ?></span>
										<input type="number" min="0" step="1" name="arrows[height]" style="width: 90%" data-selector=".easingslider-arrows" data-property="height" value="<?php echo esc_attr( $slider->customizations->arrows->height ); ?>"> px
									</label>
								</li>
							</ul>
						</li>

						<li class="control-section accordion-section customize-section">
							<h3 class="accordion-section-title customize-section-title" tabindex="0" title=""><?php _e( 'Pagination Icons', 'easingslider' ); ?></h3>
							<ul class="accordion-section-content customize-section-content">
								<li class="customize-control customize-control-text">
									<label>
										<span class="customize-control-title"><?php _e( '"Inactive" Image', 'easingslider' ); ?></span>
										<input type="text" name="pagination[inactive]" data-selector=".easingslider-icon" data-property="background-image" value="<?php echo esc_attr( $slider->customizations->pagination->inactive ); ?>">
									</label>
								</li>
								<li class="customize-control customize-control-text">
									<label>
										<span class="customize-control-title"><?php _e( '"Active" Image', 'easingslider' ); ?></span>
										<input type="text" name="pagination[active]" data-selector=".easingslider-icon.active" data-property="background-image" value="<?php echo esc_attr( $slider->customizations->pagination->active ); ?>">
									</label>
								</li>
								<li class="customize-control customize-control-text">
									<label>
										<span class="customize-control-title"><?php _e( 'Icon Width', 'easingslider' ); ?></span>
										<input type="number" min="0" step="1" name="pagination[width]" style="width: 90%" data-selector=".easingslider-icon" data-property="width" value="<?php echo esc_attr( $slider->customizations->pagination->width ); ?>"> px
									</label>
								</li>
								<li class="customize-control customize-control-text">
									<label>
										<span class="customize-control-title"><?php _e( 'Icon Height', 'easingslider' ); ?></span>
										<input type="number" min="0" step="1" name="pagination[height]" style="width: 90%" data-selector=".easingslider-icon" data-property="height" value="<?php echo esc_attr( $slider->customizations->pagination->height ); ?>"> px
									</label>
								</li>
							</ul>
						</li>

						<li class="control-section accordion-section customize-section">
							<h3 class="accordion-section-title customize-section-title" tabindex="0" title=""><?php _e( 'Border', 'easingslider' ); ?></h3>
							<ul class="accordion-section-content customize-section-content">
								<li class="customize-control customize-control-text">
									<label>
										<span class="customize-control-title"><?php _e( 'Color', 'easingslider' ); ?></span>
										<input type="text" name="border[color]" class="color-picker-hex" data-selector=".easingslider" data-property="border-color" data-default="#000" value="<?php echo esc_attr( $slider->customizations->border->color ); ?>">
									</label>
								</li>
								<li class="customize-control customize-control-text">
									<label>
										<span class="customize-control-title"><?php _e( 'Width', 'easingslider' ); ?></span>
										<input type="number" min="0" step="1" name="border[width]" style="width: 90%" data-selector=".easingslider" data-property="border-width" value="<?php echo esc_attr( $slider->customizations->border->width ); ?>"> px
									</label>
								</li>
								<li class="customize-control customize-control-text">
									<label>
										<span class="customize-control-title"><?php _e( 'Radius', 'easingslider' ); ?></span>
										<input type="number" min="0" step="1" name="border[radius]" style="width: 90%" data-selector=".easingslider" data-property="border-radius" value="<?php echo esc_attr( $slider->customizations->border->radius ); ?>"> px
									</label>
								</li>
							</ul>
						</li>

						<li class="control-section accordion-section customize-section">
							<h3 class="accordion-section-title customize-section-title" tabindex="0" title=""><?php _e( 'Drop Shadow', 'easingslider' ); ?></h3>
							<ul class="accordion-section-content customize-section-content">
								<li class="customize-control customize-control-text">
									<label>
										<span class="customize-control-title"><?php _e( 'Display a Drop Shadow', 'easingslider' ); ?></span>
										<label for="shadow-enabled-true"><input type="radio" name="shadow[enabled]" id="shadow-enabled-true" data-selector=".easingslider-shadow" data-property="display" value="true" style="margin: 0 3px 0 0;" <?php checked( $slider->customizations->shadow->enabled, true ); ?>><?php _e( 'True', 'easingslider' ); ?></label>
										<label for="shadow-enabled-false"><input type="radio" name="shadow[enabled]" id="shadow-enabled-false" data-selector=".easingslider-shadow" data-property="display" value="false" style="margin: 0 3px 0 20px;" <?php checked( $slider->customizations->shadow->enabled, false ); ?>><?php _e( 'False', 'easingslider' ); ?></label>
									</label>
								</li>
								<li class="customize-control customize-control-text">
									<label>
										<span class="customize-control-title"><?php _e( 'Shadow Image', 'easingslider' ); ?></span>
										<input type="text" name="shadow[image]" data-selector=".easingslider-shadow img" data-property="src" value="<?php echo esc_attr( $slider->customizations->shadow->image ); ?>">
									</label>
								</li>
							</ul>
						</li>
					</ul>
				</div>
			</div>

			<div id="customize-footer-actions" class="wp-full-overlay-footer">
				<a href="#" class="collapse-sidebar button-secondary" title="<?php _e( 'Collapse Sidebar', 'easingslider' ); ?>">
					<span class="collapse-sidebar-arrow"></span>
					<span class="collapse-sidebar-label"><?php _e( 'Collapse', 'easingslider' ); ?></span>
				</a>
			</div>

			<input type="hidden" name="id" value="<?php echo esc_attr( $slider->ID ); ?>">
			<input type="hidden" name="customizations" id="customizations" value="">
			<?php /** This ensures that the JSON is encoded correctly. Using PHP JSON encode can cause magic quote issues */ ?>
			<script type="text/javascript">document.getElementById('customizations').value = '<?php echo addslashes( json_encode( null ) ); ?>';</script>
		</form>

		<div id="customize-preview" class="wp-full-overlay-main" style="position: relative;">
			<?php
				/**
				 * Display the slider
				 */
				if ( function_exists( 'easingslider' ) ) {
					easingslider( $slider->ID );
				}
			?>
		</div>
	</div>
</div>