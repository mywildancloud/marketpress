<?php
$terms = [];

$class = '';

if (is_post_type_archive('marketpress-store') || is_page_template('template-product-atc.php')) {
    $class = "-store";

    $terms = get_terms(array(
        'taxonomy' => 'marketpress-store-category',
        'hide_empty' => false,
    ));
}

if (is_post_type_archive('marketpress-product') || is_page_template('template-product.php')) {
    $terms = get_terms(array(
        'taxonomy' => 'marketpress-product-category',
        'hide_empty' => false,
    ));
}

//__debug($terms);

if ($terms) :
?>
    <div id="slide-category<?php echo $class; ?>" class="slide-category<?php echo $class; ?>" id="category-slide">
        <a class="seemore">Lihat Semua</a>
        <div class="arrow arrow-left">
            <i class="lni lni-angle-double-left"></i>
        </div>
        <ul>
            <?php $i = 1;
            foreach ((array) $terms as $key => $term) : ?>
                <?php
                if (!isset($term->term_id) || !isset($term->name)) continue;
                $term_icon = get_term_meta($term->term_id, 'icon', true);
                $term_link = get_term_link($term->term_id);
                ?>
                <li>
                    <a href="<?php echo $term_link; ?>">
                        <?php if ($term_icon) {
                            echo '<img class="lazy" data-src="' . $term_icon . '">';
                        } ?>
                        <h4><?php echo $term->name; ?></h4>
                    </a>
                </li>
            <?php

                if ('-store' == $class && $i >= 6) {
                    break;
                }
                $i++;

            endforeach; ?>
        </ul>
        <div class="arrow arrow-right">
            <i class="lni lni-angle-double-right"></i>
        </div>
        <div class="scrollbar" id="scrollbar">
            <div class="scrollbarinner button-bg"></div>
        </div>
    </div>

    <div class="slide-category-store-canvas">
        <ul>
            <?php foreach ((array) $terms as $key => $term) : ?>
                <?php
                if (!isset($term->term_id) || !isset($term->name)) continue;
                $term_icon = get_term_meta($term->term_id, 'icon', true);
                $term_link = get_term_link($term->term_id);
                ?>
                <li>
                    <a href="<?php echo $term_link; ?>">
                        <?php if ($term_icon) {
                            echo '<img class="lazy" data-src="' . $term_icon . '">';
                        } ?>
                        <h4><?php echo $term->name; ?></h4>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php
endif;
