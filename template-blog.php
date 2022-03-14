<?php
global $wp;
/**
 * Template Name: Blog Post
 *
 * @package MarketPress
 */

get_header();

$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;

if (isset($wp->query_vars['paged'])) {
    $paged = sanitize_text_field($wp->query_vars['paged']);
}

$args = array(
    'post_type'      => 'post',
    'posts_per_page' => get_option('posts_per_page'),
    'post_status'    => 'publish',
    'order'          => 'DESC',
    'orderby'        => 'date',
    'paged' => $paged,
);


$cposts = new WP_Query($args);

?>

<main id="primary" class="site-main">

    <div class="wrapper clear">
        <div class="page-content clear">
            <?php if ($cposts->have_posts()) : ?>

            <div class="page-content-header clear">
                <h2>Latest</h2>
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
                        get_template_part('template-parts/postbox');

                    endwhile;

                    ?>
            </div>

            <?php
                marketpress_posts_pagination('', 1, $cposts);
            else :

                get_template_part('template-parts/content', 'none');

            endif;
            ?>
        </div>
        <?php get_sidebar(); ?>
    </div>

</main><!-- #main -->

<?php
get_footer();