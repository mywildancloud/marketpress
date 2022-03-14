<?php

/**
 * Template part for displaying page content in page.php
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package MarketPress
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
    <?php

    if (get_theme_mod('enable_page_title', 1)) {
        echo '<div class="entry-header">';
        the_title('<h1 class="entry-title">', '</h1>');
        echo '</div>';
    }
    ?>

    <div class="entry-content">
        <?php
        the_content();
        ?>
    </div><!-- .entry-content -->
</article><!-- #post-<?php the_ID(); ?> -->