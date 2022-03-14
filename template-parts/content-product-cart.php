<?php

/**
 * The template for displaying all single product
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package MarketPress
 */

$thumbnail = get_the_post_thumbnail_url(get_the_ID());

$regular_price = intval(get_post_meta(get_the_ID(), 'price', true));
$promo_price = intval(get_post_meta(get_the_ID(), 'promo_price', true));
if ($promo_price) {
    $prices = sprintf(
        '<span class="price">Rp %s</span><span class="price_slik">Rp %s</span>',
        number_format($promo_price, 0, ',', '.'),
        number_format($regular_price, 0, ',', '.')
    );
} else {
    $prices = sprintf(
        '<span class="price">Rp %s</span>',
        number_format($regular_price, 0, ',', '.')
    );
}

$price = ($promo_price) ? $promo_price : $regular_price;

$salespage_link = get_post_meta(get_the_ID(), 'salespage_affiliate_link', true);
$checkout_link = get_post_meta(get_the_ID(), 'checkout_affiliate_link', true);
$contact_link = get_post_meta(get_the_ID(), 'seller_contact_link', true);

$salespage_link_open = get_post_meta(get_the_ID(), 'salespage_affiliate_link_open', true);

if ($salespage_link_open == 'iframe') {
    $salespage_link = get_the_permalink() . '?iframe';
}

$cta = get_post_meta(get_the_ID(), 'call_to_action', true);

$product_images = array();

if (get_post_thumbnail_id()) {
    $product_images[] = get_post_thumbnail_id();
}

if (get_post_meta(get_the_ID(), 'store_images', true)) {
    $galeries = get_post_meta(get_the_ID(), 'store_images', true);
    $galery_ids = array_keys($galeries);
    $product_images = array_merge($product_images, $galery_ids);
}

$product_images_url = array();
foreach ($product_images as $key => $val) {
    $product_images_url[] = wp_get_attachment_url($val, 'full');
}

$variation1 = get_post_meta(get_the_ID(), 'variation1_option', true);
$variation1_label = get_post_meta(get_the_ID(), 'variation1_label', true);

$variation2 = get_post_meta(get_the_ID(), 'variation2_option', true);
$variation2_label = get_post_meta(get_the_ID(), 'variation2_label', true);

$endtime = get_option('mes_flashsale_date_end', '');

$label = get_post_meta(get_the_ID(), 'label', true);

$is_out_stock = get_post_meta(get_the_ID(), 'out_on_stock', true);

get_header();
?>

<main id="primary" class="site-main">
    <div class="wrapper-content">
        <div class="product single-store-product">
            <?php
            while (have_posts()) :
                the_post();

            ?>
                <div class="detailbox clear">
                    <div class="leftbox">
                        <?php marketpress_store_breadcrumb(); ?>
                        <div class="galeries" id="galeries">
                            <div class="galerybig">
                                <div class="galeryshow lazy" id="biggalery" data-bg="url(<?php if (isset($product_images_url[0])) {
                                                                                                echo $product_images_url[0];
                                                                                            } ?>)"></div>
                            </div>
                            <div id="galery-scroll" class="galery-scroll">
                                <div class="arrow arrow-left">
                                    <i class="lni lni-angle-double-left"></i>
                                </div>
                                <div class="galerysmall clear">

                                    <?php foreach ((array)$product_images_url as $photo) : ?>
                                        <div class="galerysmallbox" data-image="<?php echo $photo; ?>">
                                            <div class="img lazy" data-bg="url(<?php echo $photo; ?>)"></div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                                <div class="arrow arrow-right">
                                    <i class="lni lni-angle-double-right"></i>
                                </div>
                            </div>
                            <div class="scrollbar">
                                <div class="scrollbarinner button-bg"></div>
                            </div>
                        </div>
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
                    <div class="rightbox product-store">
                        <?php if ($label == 'flashsale' && marketpress_flashsale_is_active()) : ?>
                            <div class="flashsale">
                                <h2>Flashsale</h2>
                                <div class="cd countdown" data-end="<?php echo $endtime; ?>"><span class="days"></span><span class="hours"></span> : <span class="minutes"></span> : <span class="seconds"></span>
                                </div>
                            </div>
                        <?php endif; ?>
                        <?php if ($label != 'flashsale') : ?>
                            <?php
                            $timezone = 'Asia/Jakarta';

                            if (get_option('timezone_string')) {
                                $timezone = get_option('timezone_string');
                            };

                            date_default_timezone_set($timezone);

                            $type = get_post_meta(get_the_ID(), 'scarcity_type', true);
                            $clabel = get_post_meta(get_the_ID(), 'scarcity_label', true);
                            $fd = get_post_meta(get_the_ID(), 'scarcity_fixed_date', true);
                            $ft = get_post_meta(get_the_ID(), 'scarcity_fixed_time', true);
                            $et = get_post_meta(get_the_ID(), 'scarcity_evergreen_time', true);

                            if ($type == 'evergreen') {
                                $new_et = 0;

                                if ($et) {
                                    list($hours, $minutes) = explode(':', $et);
                                    $new_et = intval($hours) * 60 + intval($minutes);
                                    $new_et = $new_et * 60;
                                }

                                $saved_et = isset($_COOKIE['marketpress_evergreen_scarcity']) ? sanitize_text_field($_COOKIE['marketpress_evergreen_scarcity']) : '';

                                if ($saved_et) {
                                    list($hours, $minutes, $seconds) = explode(':', $saved_et);
                                    $saved_new_et = intval($hours) * 60 + intval($minutes);
                                    $saved_new_et = $saved_new_et * 60;
                                    $saved_new_et = $saved_new_et + intval($seconds);

                                    $dd = strtotime('+' . $saved_new_et . ' seconds');

                                    if ($saved_new_et > 0) {
                                        $new_et = $saved_new_et;
                                    }
                                }

                                $new_d = strtotime('+' . $new_et . ' seconds');
                            } else {
                                $datetime = $fd . ' ' . $ft;
                                $new_d = strtotime($datetime);
                            }
                            ?>
                            <?php if ($new_d > strtotime('now')) : ?>
                                <div class="flashsale">
                                    <h2><?php echo $clabel; ?></h2>
                                    <div class="cd countdown" data-end="<?php echo date('Y-m-d H:i:s', $new_d); ?>">
                                        <span class="days"></span><span class="hours"></span> : <span class="minutes"></span> :
                                        <span class="seconds"></span>
                                    </div>
                                </div>
                            <?php endif; ?>
                        <?php endif; ?>
                        <div style="background: #ffffff;padding: 16px;margin-bottom: 20px;border-radius: 5px;margin-top:30px;">
                            <h1><?php the_title(); ?></h1>
                            <div class="pricing">
                                <?php echo $prices; ?>
                            </div>
                            <?php if ($variation1 && $variation1_label) : ?>
                                <div class="variations">
                                    <label><?php echo $variation1_label; ?></label>
                                    <div class="variation variation1">
                                        <?php
                                        foreach ((array) $variation1 as $k => $v) {

                                            $checked = '';

                                            if ($k == 0) {
                                                $checked = ' checked="checked"';
                                            }

                                            $li = '<label for="' . $v . '" class="variation-radio" data-value="' . $v . '"><input type="radio" name="variation1"' . $checked . ' id="' . $v . '"><span class="marked">' . $v . '</span></label>';

                                            echo $li;
                                        }
                                        ?>
                                    </div>
                                </div>
                            <?php endif; ?>
                            <?php if ($variation2 && $variation2_label) : ?>
                                <div class="variations">
                                    <label><?php echo $variation2_label; ?></label>
                                    <div class="variation variation2">
                                        <?php
                                        foreach ((array) $variation2 as $k => $v) {
                                            $checked = '';

                                            if ($k == 0) {
                                                $checked = ' checked="checked"';
                                            }
                                            $vtext = isset($v['text']) ? sanitize_text_field($v['text']) : '';
                                            if (isset($v['price']) && $v['price']) {
                                                $vprice = intval($v['price']);
                                            } else {
                                                $vprice = $price;
                                            }

                                            $li = '<label for="' . $vtext . '" class="variation-radio" data-price="' . $vprice . '" data-value="' . $vtext . '"><input type="radio" name="variation2" id="' . $vtext . '"' . $checked . '><span class="marked">' . $vtext . '</span></label>';

                                            echo $li;
                                        }
                                        ?>
                                    </div>
                                </div>
                            <?php endif; ?>
                            <div class="attribute">
                                <p>Quantity</p>
                                <div class="quantity-changer clear">
                                    <button type="button" class="minus">-</button>
                                    <input min="1" type="number" value="1">
                                    <button type="button" class="plus">+</button>
                                </div>
                            </div>
                            <div class="action flexbox flexcenter">
                                <?php if ($is_out_stock == 'on') : ?>
                                    <div class="oos-guard"></div>
                                <?php endif; ?>
                                <a class="atc">
                                    <?php echo get_option('mes_shop_buy_now', 'Add To Cart'); ?>
                                </a>
                            </div>
                            <div class="product-store-mp">
                                <h4>Anda bisa belanja melalui:</h4>
                                <div class="button-order-mp">
                                    <a title="Bukalapak" target="_blank" href="<?php echo get_post_meta(get_the_ID(), 'product_mp_bukalapak', true); ?>">
                                        <img class="lazy bukalapak" data-src="<?php echo MARKETPRESS_URL; ?>/images/marketpress-bukalapak.png">
                                    </a>
                                    <a title="Tokopedia" target="_blank" href="<?php echo get_post_meta(get_the_ID(), 'product_mp_tokopedia', true); ?>">
                                        <img class="lazy tokopedia" data-src="<?php echo MARKETPRESS_URL; ?>/images/marketpress-tokopedia.png">
                                    </a>
                                    <a title="Shoppe" target="_blank" href="<?php echo get_post_meta(get_the_ID(), 'product_mp_shoppe', true); ?>">
                                        <img class="lazy shoppe" data-src="<?php echo MARKETPRESS_URL; ?>/images/marketpress-shoppe.png">
                                    </a>
                                    <a title="Lazada" target="_blank" href="<?php echo get_post_meta(get_the_ID(), 'product_mp_lazada', true); ?>">
                                        <img class="lazy lazada" data-src="<?php echo MARKETPRESS_URL; ?>/images/marketpress-lazada.png">
                                    </a>

                                </div>
                            </div>
                        </div>
                        <?php get_template_part('template-parts/social-share2'); ?>
                    </div>
                </div>
                <div class="product-store-contentbox">
                    <?php if ($is_out_stock == 'on') : ?>
                        <div class="oos-guard"></div>
                    <?php endif; ?>
                    <div class="contentbox-items product-store-content">
                        <h4>Deskripsi Produk</h4>
                        <?php the_content(); ?>
                    </div>
                </div>
                <?php get_template_part('template-parts/related-product-cart'); ?>
                <?php get_template_part('template-parts/basket'); ?>
            <?php

            endwhile; // End of the loop.
            ?>
            <div id="atc-success" class="alert-popup">
                <div class="alert-popup-box">
                    <div class="alert-popup-inner">
                        <svg width="284" height="284" viewBox="0 0 284 284" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <circle cx="146.162" cy="149.888" r="87.8882" fill="#FECDA0" />
                            <circle cx="153.5" cy="167.5" r="47.5" fill="white" />
                            <path d="M96.5 166C112.069 166 130.755 170.897 145.835 190.772C146.89 192.162 149.159 191.75 149.598 190.061C158.746 154.885 180.042 114.826 233 106" stroke="#6B899A" stroke-width="7.20812" stroke-linecap="round" />
                            <circle cx="230.873" cy="80.9697" r="7.20812" stroke="#DCDCDC" stroke-width="1.44162" />
                            <circle cx="29.0457" cy="134.31" r="4.32487" stroke="#DCDCDC" stroke-width="1.44162" />
                            <circle cx="184.741" cy="253.965" r="5.7665" stroke="#DCDCDC" stroke-width="1.44162" />
                            <path d="M125.724 40.6538C126.189 39.3987 127.964 39.3987 128.428 40.6538L129.377 43.218C129.523 43.6126 129.834 43.9237 130.229 44.0697L132.793 45.0185C134.048 45.483 134.048 47.2582 132.793 47.7226L130.229 48.6714C129.834 48.8174 129.523 49.1285 129.377 49.5231L128.428 52.0873C127.964 53.3424 126.189 53.3424 125.724 52.0873L124.775 49.5231C124.629 49.1285 124.318 48.8174 123.924 48.6714L121.359 47.7226C120.104 47.2582 120.104 45.483 121.359 45.0185L123.924 44.0697C124.318 43.9237 124.629 43.6126 124.775 43.218L125.724 40.6538Z" fill="#6B899A" />
                            <path d="M243.12 197.546C243.37 196.87 244.326 196.87 244.576 197.546L245.087 198.927C245.165 199.139 245.333 199.307 245.545 199.385L246.926 199.896C247.602 200.146 247.602 201.102 246.926 201.352L245.545 201.863C245.333 201.942 245.165 202.109 245.087 202.322L244.576 203.703C244.326 204.378 243.37 204.378 243.12 203.703L242.609 202.322C242.53 202.109 242.363 201.942 242.15 201.863L240.77 201.352C240.094 201.102 240.094 200.146 240.77 199.896L242.15 199.385C242.363 199.307 242.53 199.139 242.609 198.927L243.12 197.546Z" fill="#6B899A" />
                            <path d="M46.8506 176.484C47.1721 175.615 48.4011 175.615 48.7226 176.484L49.3795 178.259C49.4806 178.533 49.696 178.748 49.9692 178.849L51.7444 179.506C52.6133 179.827 52.6133 181.056 51.7444 181.378L49.9692 182.035C49.696 182.136 49.4806 182.351 49.3795 182.624L48.7226 184.4C48.4011 185.269 47.1721 185.269 46.8506 184.4L46.1937 182.624C46.0926 182.351 45.8772 182.136 45.6041 182.035L43.8289 181.378C42.96 181.056 42.96 179.827 43.8289 179.506L45.6041 178.849C45.8772 178.748 46.0926 178.533 46.1937 178.259L46.8506 176.484Z" fill="#6B899A" />
                        </svg>
                        <p>
                            item telah ditambahkan ke dalam keranjang
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main><!-- #main -->
<?php
get_footer();
