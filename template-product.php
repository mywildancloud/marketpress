<?php
global $wp;

/**
 * Template Name: Product Post
 *
 * @package MarketPress
 */

get_header();

$args = array(
    'post_type'      => 'marketpress-product',
    'posts_per_page' => get_option('posts_per_page'),
    'post_status'    => 'publish',
    'order'          => 'DESC',
    'orderby'        => 'date',
    'paged' => (get_query_var('paged')) ? get_query_var('paged') : 1
);

$cposts = new WP_Query($args);

?>

<main id="primary" class="site-main">

    <div class="wrapper clear">
        <div class="page-content list-product clear">
            <?php if ($cposts->have_posts()) : ?>

            <div class="page-content-header clear">
                <h2><i class="lni lni-shopping-basket"></i> Produk Terbaru</h2>
                <?php marketpress_product_category_list(); ?>
            </div><!-- .page-header -->
            <div class="page-content-query clear masonry-container">
                <?php
                    /* Start the Loop */
                    while ($cposts->have_posts()) :
                        $cposts->the_post();

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
                if ($cposts->max_num_pages > 1) :
                    $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
                    $paged = intval($paged) + 1;
                    echo '<div class="loop-navigation hide clear"><a href="' . site_url() . '/product/page/' . $paged . '/" class="next">Next Page</a></div>';
                endif;
            else :

                get_template_part('template-parts/content', 'none');

            endif;
            ?>
        </div>
    </div>

</main><!-- #main -->

<?php
get_footer();