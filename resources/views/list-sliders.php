<div class="wrap">
    <h2>
        <?php _e('Sliders ', 'easingslider'); ?>
        <a href="admin.php?page=easingslider-add-new" class="add-new-h2">
            <?php _e('Add New', 'easingslider'); ?>
        </a>

        <?php if ( ! empty($_GET['s'])) : ?>
            <span class="subtitle"><?php printf(__('Search results for &#8220;%s&#8221;', 'easingslider'), esc_attr($_GET['s'])); ?></span>
        <?php endif; ?>
    </h2>

    <?php
        /**
         * Prepare the sliders list table items
         */
        $listTable->prepare_items();
    ?>

    <form id="sliders-list" method="get">
        <input type="hidden" name="page" value="<?php echo esc_attr($page); ?>" />

        <?php
            /**
             * Display a search input, allowing us to search through our sliders list.
             */
            $listTable->search_box(__('Search Sliders', 'easingslider'), 'search_id');

            /**
             * Display the available views
             */
            $listTable->views();
            
            /**
             * Display the list table
             */
            $listTable->display();
        ?>
    </form>
</div>
