<?php

/**
 * The template for displaying archive pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package MarketPress
 */

get_header();
?>

<main id="primary" class="site-main">

    <div class="wrapper-store clear store-product">
        <?php get_template_part('template-parts/slider'); ?>

        <?php get_template_part('template-parts/slide-category'); ?>
        <div class="page-content list-product clear">
            <?php if (have_posts()) : ?>

                <div class="page-content-header clear">
                    <h2><i class="lni lni-shopping-basket"></i> Produk Terbaru</h2>
                </div><!-- .page-header -->
                <div class="page-content-query clear masonry-container">
                    <?php
                    /* Start the Loop */
                    while (have_posts()) :
                        the_post();

                        /*
				 * Include the Post-Type-specific template for the content.
				 * If you want to override this in a child theme, then include a file
				 * called content-___.php (where ___ is the Post Type name) and that will be used instead.
				 */
                        get_template_part('template-parts/productbox');

                    endwhile;

                    ?>
                </div>

            <?php
                marketpress_animation_loader();
                marketpress_products_pagination();
            else :

                get_template_part('template-parts/content', 'none');

            endif;
            ?>
        </div>
    </div>

</main><!-- #main -->

<?php
get_footer();
