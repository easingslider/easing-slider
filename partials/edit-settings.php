<div class="wrap">
    <h2 class="nav-tab-wrapper">
        <?php foreach ( $tabs as $slug => $label ) : ?>
            <a href="admin.php?page=<?php echo $page; ?>&amp;tab=<?php echo esc_attr( $slug ); ?>" class="nav-tab <?php echo ( $current_tab == $slug ) ? 'nav-tab-active' : ''; ?>"><?php echo esc_html( $label ); ?></a>
        <?php endforeach; ?>
    </h2>

    <form name="post" action="admin.php?page=<?php echo $page; ?>&amp;tab=<?php echo $current_tab; ?>" method="post">
        <?php
            /**
             * Security nonce field
             */
            wp_nonce_field( 'save' );
        ?>
        
        <div class="main-panel">
            <?php
                /**
                 * Before panel action
                 */
                do_action( "easingslider_before_{$current_tab}_settings", $settings );

                /**
                 * Load the appropriate tab subview, or trigger an action if otherwise.
                 * This approach allows extensions to add their own "Settings" panels.
                 */
                easingslider_partial_or_action( "edit-settings_{$current_tab}", $settings );

                /**
                 * After panel action
                 */
                do_action( "easingslider_after_{$current_tab}_settings", $settings );
            ?>
        </div>

        <p class="submit">
            <input type="submit" name="save" class="button button-primary button-large" id="save" accesskey="p" value="<?php _e( 'Save Settings', 'easingslider' ); ?>">
        </p>
    </form>
</div>