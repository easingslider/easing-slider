<div class="wrap">
    <h2>
        <?php _e( 'Sliders ', 'easingslider' ); ?>
        <a href="admin.php?page=easingslider_publish_slider" class="add-new-h2">
            <?php _e( 'Add New', 'easingslider' ); ?>
        </a>

        <?php if ( isset( $_GET['s'] ) && ! empty( $_GET['s'] ) ) : ?>
            <span class="subtitle"><?php printf( __( 'Search results for &#8220;%s&#8221;', 'easingslider' ), $_GET['s'] ); ?></span>
        <?php endif; ?>
    </h2>

    <?php
        /**
         * Prepare the sliders list table items
         */
        $list_table->prepare_items();
    ?>

    <form id="sliders-list" method="get">
        <input type="hidden" name="page" value="<?php echo $_GET['page']; ?>" />

        <?php
            /**
             * Display a search input, allowing us to search through our sliders list.
             */
            $list_table->search_box( __( 'Search Sliders', 'easingslider' ), 'search_id' );

            /**
             * Display the list table
             */
            $list_table->display();
        ?>
    </form>
</div>