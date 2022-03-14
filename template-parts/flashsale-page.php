<?php

$endtime = get_option('mes_flashsale_date_end', '');

get_header();


?>
<main id="primary" class="site-main">

    <div class="wrapper-content clear">
        <div class="page-content list-product clear store-product">
            <?php if (marketpress_flashsale_is_active()) : ?>
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
                            'value' => 'flashsale',
                            'compare' => '='
                        )
                    )
                );
                $fsposts = new WP_Query($argss);
                ?>
                <?php if ($fsposts->have_posts()) : ?>

                    <div class="page-content-header clear">
                        <div class="flashsale">
                            <h2>Flashsale</h2>
                            <div class="cd countdown" data-end="<?php echo $endtime; ?>"><span class="days"></span><span class="hours"></span> : <span class="minutes"></span> : <span class="seconds"></span>
                            </div>
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
            <?php else : ?>
                <div class="flashsale-end">
                    <div class="flashsale-end-box">
                        <?php echo get_option('mes_flashsale_end_message', 'Flashsale telah berakhir, nantikan flashsale berikutnya'); ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</main>
<?php
get_footer();
