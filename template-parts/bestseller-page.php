<?php

$argss = array(
    'post_type'      => 'marketpress-store',
    'posts_per_page' => -1,
    'post_status'    => 'publish',
    // 'order'          => 'DESC',
    // 'orderby'        => 'date',
    'meta_query' => array(
        array(
            'key' => 'label',
            'value' => 'bestseller',
            'compare' => '='
        )
    )
);
$fsposts = new WP_Query($argss);


get_header();
?>
<main id="primary" class="site-main">

    <div class="wrapper-content clear">
        <div class="page-content list-product clear store-product">
            <?php if ($fsposts->have_posts()) : ?>

                <div class="page-content-header clear">
                    <h2><i class="lni lni-shopping-basket"></i> Terlaris</h2>
                </div>
                <div class="page-content-query clear masonry-container">
                    <?php
                    /* Start the Loop */
                    while ($fsposts->have_posts()) :
                        $fsposts->the_post();

                        /*
				 * Include the Post-Type-specific template for the content.
				 * If you want to override this in a child theme, then include a file
				 * called content-___.php (where ___ is the Post Type name) and that will be used instead.
				 */
                        get_template_part('template-parts/productbox');

                    endwhile;

                    ?>
                </div>
            <?php endif; ?>
            <?php wp_reset_query(); ?>
        </div>
    </div>
</main>
<?php
get_footer();
