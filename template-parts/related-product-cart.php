<?php

$args = array(
    'post__not_in'        => array(get_the_ID()),
    'post_type'      => 'marketpress-store',
    'posts_per_page' => 4,
    'post_status'    => 'publish',
    'ignore_sticky_posts' => 1,
);

$categories = get_the_terms(get_the_ID(), 'marketpress-store-category');

if ($categories) :
    $category_ids = array();
    foreach ($categories as $category) :
        $category_ids[] = $category->term_id;
    endforeach;

    if ($category_ids) :
        $args['tax_query'] = array(
            array(
                'taxonomy' => 'marketpress-store-category',
                'field' => 'term_id',
                'terms' => $category_ids,
                'operator' => 'IN',
            )
        );
    endif;
endif;


$tposts = new WP_Query($args);
if ($tposts->have_posts()) :
?>
    <div class="page-content list-product clear">

        <div class="page-content-header clear">
            <h2>Rekomendasi Produk</h2>
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