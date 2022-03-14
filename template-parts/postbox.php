<?php

/**
 * Template part for displaying posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package marketpress
 */

?>

<article class="postbox masonry-item">
    <a href="<?php echo get_the_permalink(); ?>" title="<?php echo get_the_title(); ?>">
        <div class="contentbox">
            <?php
            $thumb = get_the_post_thumbnail_url(get_the_ID());
            ?>

            <div class="thumb masonry-post-thumbnail lazy" data-bg="url(<?php echo $thumb; ?>)">
            </div>

            <div class="entry">
                <div class="category button-color button-bg">
                    <?php
                    $categories = get_the_category();
                    $category = $categories ? $categories[0]->name : 'Uncategorized';
                    ?>
                    <span><?php echo $category; ?></span>
                </div>
                <h3 class="title">
                    <?php echo get_the_title(); ?>
                </h3>
                <ul class="meta flexbox flexboxcenter">
                    <li class="flex"><span>By <?php echo get_the_author(); ?></span></li>
                    <li class="flex"><span><?php echo get_the_date('d/m/Y'); ?></span></li>
                </ul>
            </div>
        </div>
    </a>
</article>