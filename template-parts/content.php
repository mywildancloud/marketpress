<?php

/**
 * Template part for displaying posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package MarketPress
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
    <?php
    if (get_theme_mod('enable_post_breadcrumb', 1)) {
        marketpress_breadcrumb();
    }

    if (get_theme_mod('enable_post_thumbnail', 1)) {
        echo '<div class="entry-thumbnail">';
        marketpress_post_thumbnail();
        echo '</div>';
    }

    if (get_theme_mod('enable_post_title', 1)) {
        echo '<div class="entry-header">';
        if (is_singular()) :
            the_title('<h1 class="title">', '</h1>');
        else :
            the_title('<h2 class="title"><a href="' . esc_url(get_permalink()) . '" rel="bookmark">', '</a></h2>');
        endif;
        echo '</div>';
    }
    $ads1 = get_theme_mod('ad_content_after_title_code');
    ?>
    <?php if ($ads1) : ?>
    <div class="ads ads-before-content">
        <div class="wrapper">
            <?php echo $ads1; ?>
        </div>
    </div>
    <?php endif; ?>

    <div class="entry-content">
        <?php
        the_content(
            sprintf(
                wp_kses(
                    /* translators: %s: Name of current post. Only visible to screen readers */
                    __('Continue reading<span class="screen-reader-text"> "%s"</span>', 'marketpress'),
                    array(
                        'span' => array(
                            'class' => array(),
                        ),
                    )
                ),
                wp_kses_post(get_the_title())
            )
        );
        ?>
    </div><!-- .entry-content -->
    <?php
    $ads2 = get_theme_mod('ad_content_after_content_code');
    ?>
    <?php if ($ads2) : ?>
    <div class="ads ads-after-content">
        <div class="wrapper">
            <?php echo $ads2; ?>
        </div>
    </div>
    <?php endif; ?>
    <div class="entry-footer">
        <?php
        if (get_theme_mod('enable_post_share', 1)) {
            get_template_part('template-parts/social-share');
        } ?>
        <ul class="entry-tags">
            <?php foreach (wp_get_post_tags(get_the_ID()) as $tag) : ?>
            <li><a href="<?php echo get_tag_link($tag->term_id); ?>"># <?php echo $tag->name; ?></a></li>
            <?php endforeach; ?>
        </ul>
    </div>
</article><!-- #post-<?php the_ID(); ?> -->