<?php

/**
 * marketpress coupon function
 * @package marketpress/inc
 * @author Taufik Hidayat <taufik@fiqhidayat.com>
 */

add_action('init', 'marketpress_coupon');
/**
 * register Coupon post type
 * @return [type] [description]
 */
function marketpress_coupon()
{
    register_post_type(
        'marketpress-coupon', // Register Custom Post Type
        array(
            'labels' => array(
                'name'               => __('Coupon', 'marketpress'), // Rename these to suit
                'singular_name'      => __('Coupon', 'marketpress'),
                'add_new'            => __('Add New', 'marketpress'),
                'add_new_item'       => __('Add New Coupon', 'marketpress'),
                'edit'               => __('Edit', 'marketpress'),
                'edit_item'          => __('Edit Coupon', 'marketpress'),
                'new_item'           => __('New Coupon', 'marketpress'),
                'view'               => __('View Coupon', 'marketpress'),
                'view_item'          => __('View Coupon', 'marketpress'),
                'search_items'       => __('Search Coupon', 'marketpress'),
                'not_found'          => __('No Coupons found', 'marketpress'),
                'not_found_in_trash' => __('No Coupons found in Trash', 'marketpress')
            ),
            'public' => true,
            'hierarchical' => false,
            'show_in_menu' => 'admin.php?page=marketpress',
            'has_archive' => false,
            'publicly_queryable' => false,
            'supports' => array(
                'title',
                'editor'
            ),
            'can_export' => false,
            'exclude_from_search' => true,
            'menu_icon' => 'dashicons-tag',
        )
    );
}

add_action('admin_print_scripts-post-new.php', 'marketpress_coupon_admin_script', 11);
add_action('admin_print_scripts-post.php', 'marketpress_coupon_admin_script', 11);
/**
 * tweak admin Coupon ui
 * @return [type] [description]
 */
function marketpress_coupon_admin_script()
{
    global $post_type;

    if ('marketpress-coupon' == $post_type) :
?>
<script type="text/javascript">
setTimeout(function() {
    document.getElementById('title-prompt-text').innerHTML = 'Add Coupon Code';
}, 100);
</script>
<?php
    endif;
}


add_filter('manage_marketpress-coupon_posts_columns', 'marketpress_coupon_column');
/**
 * register Coupon custom column
 * @param  [type] $columns [description]
 * @return [type]          [description]
 */
function marketpress_coupon_column($columns)
{

    $columns['title'] = 'Code';
    $columns['description'] = 'Description';
    $columns['coupon_type'] = 'Type';
    $columns['coupon_discount'] = 'Discount';
    $columns['coupon_expired'] = 'Expired';
    $columns['coupon_limit'] = 'Usage';

    unset($columns['date']);

    return $columns;
}

add_action('manage_marketpress-coupon_posts_custom_column', 'marketpress_coupon_content_column', 10, 2);
/**
 * Coupon custom column value
 * @param  [type] $column  [description]
 * @param  [type] $post_id [description]
 * @return [type]          [description]
 */
function marketpress_coupon_content_column($column, $post_id)
{

    switch ($column):

        case 'description':
            echo get_the_content($post_id);
            break;

        case 'coupon_type':
            $type = get_post_meta($post_id, 'coupon_type', true);
            echo $type == 'fixed' ? 'Fixed Discount' : 'Percentage Discount';
            break;

        case 'coupon_discount':
            echo number_format(get_post_meta($post_id, 'coupon_discount', true), 0, ',', '.');
            break;

        case 'coupon_expired':
            echo get_post_meta($post_id, 'coupon_expired', true);
            break;

        case 'coupon_limit':
            $amount = get_post_meta($post_id, 'coupon_amount', true);
            $usage = intval(get_post_meta($post_id, 'coupon_usage', true));
            echo $usage . '/' . $amount;
            break;
    endswitch;
}

add_filter('user_can_richedit', function ($default) {
    global $post;
    if ($post && $post->post_type === 'marketpress-coupon')  return false;
    return $default;
});


add_action('admin_footer', 'marketpress_coupon_admin_footer');
/**
 * admin coupon footer script
 * @return [type] [description]
 */
function marketpress_coupon_admin_footer()
{
    $current_screen = get_current_screen();
    if ($current_screen->parent_file == 'edit.php?post_type=marketpress-coupon') :
    ?>
<script>
jQuery('.row-actions .inline').hide();
</script>
<?php
    endif;
}


add_action('wp_ajax_apply_coupon', 'marketpress_apply_coupon');
add_action('wp_ajax_nopriv_apply_coupon', 'marketpress_apply_coupon');
/**
 * marketpress ajax capply coupon
 * @return [type] [description]
 */
function marketpress_apply_coupon()
{

    $input = file_get_contents("php://input");

    /*
    no input ? return.
     */
    if (empty($input)) exit;
    $data = json_decode($input, true);

    $data_default = array(
        'nonce' => '',
        'code' => '',
        'cart_items' => array(),
    );

    $data = wp_parse_args($data, $data_default);

    $response = array(
        'status'   => 'invalid',
        'message'  => 'Kode voucher tidak valid',
    );

    $return = false;


    /*
    Check nonce.
     */
    if (isset($data['nonce'])  && wp_verify_nonce($data['nonce'], 'marketpress')) :
        $code_coupon = sanitize_text_field($data['code']);

        $coupon = marketpress_get_coupon($code_coupon);
        if ($coupon) {

            $total = 0;
            foreach ((array) $data['cart_items'] as $item) {
                $price = intval($item['price']) + intval($item['price_plus']);

                $subtotal = intval($item['quantity']) * intval($price);

                $total = $total + intval($subtotal);
            }

            if (!$return && $coupon->expired < strtotime('now')) {
                $response['message'] = 'Kode voucher sudah kadaluarsa';
                $return = true;
            }

            if (!$return && $total < $coupon->minimum_cart) {
                $response['message'] = 'Total belanja Anda belum mencapai batas minimum, batas minimum total belanja untuk dapat menggunakan voucher ini adalah Rp ' . number_format($coupon->minimum_cart, 0, '.', '.');
                $return = true;
            }

            if (!$return && $coupon->amount <= $coupon->usage) {
                $response['message'] = 'Kode voucher telah habis di gunakan';
                $return = true;
            }

            if (!$return) {

                if ($coupon->type == 'percent') {
                    $discount = ($coupon->discount * $total) / 100;
                } else {
                    $discount = $coupon->discount;
                }

                $response['status'] = 'valid';
                $response['message'] = 'Kode voucher valid, Anda mendapatkan potongan harga sebesar Rp ' . number_format(intval($discount), 0, '.', '.');
                $response['discount'] = intval($discount);

                $usage = intval(get_post_meta($coupon->id, 'coupon_usage', true));
                $usage = $usage + 1;

                //update_post_meta($coupon->id, 'coupon_usage', $usage);
            }
        }

        echo json_encode($response);
        exit;
    endif;
}

function marketpress_get_coupon($code)
{
    global $wpdb;

    $coupon = array();

    $code = sanitize_text_field($code);
    $post_id = $wpdb->get_var("SELECT ID FROM $wpdb->posts WHERE post_title = '" . $code . "' AND post_type = 'marketpress-coupon'");

    if ($post_id) {
        $coupon = array(
            'id'           => $post_id,
            'type'         => get_post_meta($post_id, 'coupon_type', true),
            'discount'     => intval(get_post_meta($post_id, 'coupon_discount', true)),
            'expired'      => strtotime(get_post_meta($post_id, 'coupon_expired', true)),
            'amount'       => intval(get_post_meta($post_id, 'coupon_amount', true)),
            'usage'        => intval(get_post_meta($post_id, 'coupon_usage', true)),
            'minimum_cart' => intval(get_post_meta($post_id, 'coupon_min_cart', true)),
        );
    }


    return $coupon ? (object) $coupon : false;
}