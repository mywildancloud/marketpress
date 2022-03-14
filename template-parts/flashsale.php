<?php

$endtime = get_option('mes_flashsale_date_end', '');


if (marketpress_flashsale_is_active()) {
    $argss = array(
        'post_type'      => 'marketpress-store',
        'posts_per_page' => 4,
        'post_status'    => 'publish',
        // 'order'          => 'DESC',
        // 'orderby'        => 'date',
        'meta_query' => array(
            array(
                'key' => 'label',
                'value' => 'flashsale',
                'compare' => '='
            )
        )
    );
    $fsposts = new WP_Query($argss);
    if ($fsposts->have_posts()) :
?>
        <div class="page-content list-product clear store-product">
            <?php if ($fsposts->have_posts()) : ?>

                <div class="page-content-header clear">
                    <div class="flashsale">
                        <h2>Flashsale</h2>
                        <div class="cd countdown" data-end="<?php echo $endtime; ?>"><span class="days"></span><span class="hours"></span> : <span class="minutes"></span> : <span class="seconds"></span>
                        </div>
                    </div>
                    <div class="seeall">
                        <a href="<?php echo site_url(); ?>/flashsale">Lihat Semua</a>
                    </div>
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
    <?php endif; ?>
    <?php wp_reset_query(); ?>
<?php
}
