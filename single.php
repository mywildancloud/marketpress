<?php

/**
 * The template for displaying all single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package MarketPress
 */

get_header();
?>

<main id="primary" class="site-main">
    <div class="wrapper clear">
        <div class="content">
            <?php
			while (have_posts()) :
				the_post();

				get_template_part('template-parts/content', get_post_type());

				if (get_theme_mod('enable_post_related', 1)) {
					marketpress_post_related();
				}

				// If comments are open or we have at least one comment, load up the comment template.
				if (comments_open() || get_comments_number()) :
					comments_template();
				endif;

			endwhile; // End of the loop.
			?>
        </div>
        <?php get_sidebar(); ?>
    </div>
</main><!-- #main -->

<?php
get_footer();