<?php

/**
 * Template part for displaying product post type
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package marketpress
 */

$discount = get_post_meta(get_the_ID(), 'discount', true);
if ($discount) {
    if (strpos($discount, '%') !== false) {
        $discount = '-' . $discount;
    } else {
        $discount = intval($discount);
        $discount = '- Rp' . number_format($discount, 0, '.', '.');
    }
}
$regular_price = intval(get_post_meta(get_the_ID(), 'price', true));
$promo_price = intval(get_post_meta(get_the_ID(), 'promo_price', true));

if ($promo_price) {
    $price = number_format($promo_price, 0, '.', '.');
    $price_slik = number_format($regular_price, 0, '.', '.');
} else {
    $price = number_format($regular_price, 0, '.', '.');
    $price_slik = '';
}
$salespage_link = get_post_meta(get_the_ID(), 'salespage_affiliate_link', true);

$salespage_link_open = get_post_meta(get_the_ID(), 'salespage_affiliate_link_open', true);

if ($salespage_link_open == 'iframe') {
    $salespage_link = get_the_permalink() . '?iframe';
}

$cta = get_post_meta(get_the_ID(), 'call_to_action', true);
$target = ' target="_blank"';

if ($cta == 'form' || get_post_type() == 'marketpress-store') {
    $salespage_link = get_the_permalink();
    $target = '';
}

$cta_button = 'Lihat Produk';

if (get_post_type() == 'marketpress-store') {
    $cta_button = 'Detail Produk';
}

if (empty($salespage_link)) {
    $salespage_link = get_the_permalink();
}

$product_images = array();

if (get_post_thumbnail_id(get_the_ID())) {
    $product_images[] = get_post_thumbnail_id(get_the_ID());
}

if (get_post_meta(get_the_ID(), 'product_images', true)) {
    $galeries = get_post_meta(get_the_ID(), 'product_images', true);
    $galery_ids = array_keys($galeries);
    $product_images = array_merge($product_images, $galery_ids);
}

$thumb_url = '';

if ($product_images) {
    $thumb_url = wp_get_attachment_url($product_images[0], 'full');
}

$is_out_stock = get_post_meta(get_the_ID(), 'out_on_stock', true);

?>

<article class="productbox masonry-item">
    <div class="contentbox">
        <a <?php if ($is_out_stock != 'on') : ?>href="<?php echo get_the_permalink(); ?>" <?php endif; ?> title="<?php echo get_the_title(); ?>">
            <div class="thumb masonry-product-thumbnail lazy" data-bg="url(<?php echo $thumb_url; ?>)">
                <?php if ($is_out_stock == 'on') : ?>
                    <div class="oos-guard">
                        <div class="oos-box">
                            <div class="alerto">
                                Stock Habis
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </a>

        <div class="entry">
            <h3 class="title" style="z-index:9999999999999">
                <?php echo get_the_title(); ?>
            </h3>
            <ul class="meta">
                <li>Rp <?php echo $price; ?></li>
                <?php if ($price_slik) : ?>
                    <li><del>Rp
                            <?php echo $price_slik; ?></del><?php echo $discount ? '<span>' . $discount . '</span>' : ''; ?>
                    </li>
                <?php endif; ?>
            </ul>
            <div class="action">
                <a class="button-bg button-color" <?php echo $target; ?> <?php if ($is_out_stock != 'on') : ?>href="<?php echo $salespage_link; ?>" <?php endif; ?>><?php echo $cta_button; ?></a>
            </div>
        </div>
    </div>
</article>