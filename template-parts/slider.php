<?php
$sliders = get_option('marketpress_slider') ? get_option('marketpress_slider') : array();

if (empty($sliders)) {
    $args = array(
        'post_type' => 'slider',
        'post_status' => 'publish',
        'fields' => 'ids',
    );

    $query = new WP_Query($args);

    foreach ((array) $query->posts as $key => $val) :
        $image_url = get_post_meta($val, 'slider_image_url', true);
        if ($image_url) :
            $sliders[] = array(
                'image' => esc_url_raw($image_url),
                'link' => esc_url_raw(get_post_meta($val, 'slider_image_link', true))
            );
        endif;
    endforeach;

    update_option('marketpress_slider', $sliders);
}


?>
<?php if ($sliders) : ?>
<div id="splide1" class="splide splides">
    <div class="splide__track">
        <ul class="splide__list">
            <?php foreach ((array) $sliders as $key => $val) : ?>
            <li class="splide__slide">
                <?php if ($val['link']) : ?>
                <a href="<?php echo esc_url($val['link']); ?>" target="_blank">
                    <img data-splide-lazy="<?php echo $val['image']; ?>">
                </a>
                <?php else : ?>
                <img data-splide-lazy="<?php echo $val['image']; ?>">
                <?php endif; ?>
            </li>
            <?php endforeach; ?>
        </ul>
    </div>
</div>
<?php endif; ?>