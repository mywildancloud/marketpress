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

    <div class="wrapper clear">
        <div class="page-content clear">
            <?php if (have_posts()) : ?>

            <div class="page-content-header clear">
                <?php
					the_archive_title('<h1>', '</h1>');
					the_archive_description('<div class="archive-description">', '</div>');
					?>
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
						get_template_part('template-parts/postbox');

					endwhile;

					?>
            </div>

            <?php
				marketpress_posts_pagination();
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