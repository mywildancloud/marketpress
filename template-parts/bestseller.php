<?php


$argsss = array(
    'post_type'      => 'marketpress-store',
    'posts_per_page' => 4,
    'post_status'    => 'publish',
    // 'order'          => 'DESC',
    // 'orderby'        => 'date',\
    'meta_query' => array(
        array(
            'key' => 'label',
            'value' => 'bestseller',
            'compare' => '='
        )
    )
);

$tposts = new WP_Query($argsss);
if ($tposts->have_posts()) :
?>
    <div class="page-content list-product clear store-product">

        <div class="page-content-header clear">
            <h2>Terlaris</h2>
            <div class="seeall">
                <a href="<?php echo site_url(); ?>/bestseller">Lihat Semua</a>
            </div>
        </div>
        <div class="page-content-query clear masonry-container">
            <?php
            /* Start the Loop */
            while ($tposts->have_posts()) :
                $tposts->the_post();

                /*
				 * Include the Post-Type-specific template for the content.
				 * If you want to override this in a child theme, then include a file
				 * called content-___.php (where ___ is the Post Type name) and that will be used instead.
				 */
                get_template_part('template-parts/productbox');

            endwhile;

            ?>
        </div>
    </div>
<?php endif; ?>
<?php wp_reset_query(); ?>