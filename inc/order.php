<?php

/**
 * marketpress order function
 * @package marketpress/inc
 * @author Taufik Hidayat <taufik@fiqhidayat.com>
 */

add_action('init', 'marketpress_order');
/**
 * register order post type
 * @return [type] [description]
 */
function marketpress_order()
{
    register_post_type(
        'marketpress-order', // Register Custom Post Type
        array(
            'labels' => array(
                'name'               => __('Order', 'marketpress'), // Rename these to suit
                'singular_name'      => __('Order', 'marketpress'),
                'add_new'            => __('Add New', 'marketpress'),
                'add_new_item'       => __('Add New Order', 'marketpress'),
                'edit'               => __('Edit', 'marketpress'),
                'edit_item'          => __('Edit Order', 'marketpress'),
                'new_item'           => __('New Order', 'marketpress'),
                'view'               => __('View Order', 'marketpress'),
                'view_item'          => __('View Order', 'marketpress'),
                'search_items'       => __('Search Order', 'marketpress'),
                'not_found'          => __('No Orders found', 'marketpress'),
                'not_found_in_trash' => __('No Orders found in Trash', 'marketpress')
            ),
            'public' => false,
            'show_ui' => true,
            'publicly_queryable' => false,
            'exclude_from_search' => true,
            'hierarchical' => false,
            'has_archive' => false,
            'supports' => array(
                'title',
            ),
            'show_in_menu' => 'admin.php?page=marketpress',
            'can_export' => false,
            'capability_type' => 'post',
            'capabilities' => array(
                //'create_posts' => false,
            ),
            'menu_icon' => 'dashicons-cart',
        )
    );

    foreach ((array)marketpress_order_statuses() as $key => $val) {
        register_post_status($key, array(
            'label'                     => $val,
            'public'                    => true,
            'label_count'               => _n_noop($val . ' <span class="count">(%s)</span>', $val . ' <span class="count">(%s)</span>', 'marketpress'),
            'post_type'                 => array('marketpress-order'),
            'show_in_admin_all_list'    => true,
            'show_in_admin_status_list' => true,
            'show_in_metabox_dropdown'  => true,
            'show_in_inline_dropdown'   => true,
            'dashicon'                  => 'dashicons-yes',
        ));
    }
}

add_action('wp_ajax_order_create', 'marketpress_order_create');
add_action('wp_ajax_nopriv_order_create', 'marketpress_order_create');

add_action('admin_footer', 'foodpress_order_admin_footer');
/**
 * admin order footer script
 * @return [type] [description]
 */
function foodpress_order_admin_footer()
{
    $current_screen = get_current_screen();

    if ($current_screen->post_type == 'marketpress-order' && empty($current_screen->taxonomy) && !isset($_GET['page']) && !isset($_GET['action'])) :

        $ajax_url = admin_url('admin-ajax.php');
        $nonce = wp_create_nonce('marketpress');
        $status = isset($_GET['post_status']) ? $_GET['post_status'] : 'all';
?>
<script>
jQuery('.order_name .row-actions').hide();
jQuery(jQuery(".wrap h1")[0]).append("<a  id='export-csv' class='add-new-h2'>Export To CSV</a>");
jQuery('#export-csv').on('click', function() {
    jQuery(this).html('Proses..');

    jQuery.ajax({
        type: "POST",
        url: '<?php echo $ajax_url; ?>',
        data: {
            action: 'export_orders',
            nonce: '<?php echo $nonce; ?>',
            status: '<?php echo $status; ?>',
        },
        success: function(data) {
            console.log(data);
            jQuery('#export-csv').html('Export To CSV');
            window.open(data, '_blank');
        }
    });
})
</script>
<?php
    endif;
}

/**
 * marketpress ajax create order
 * @return [type] [description]
 */
function marketpress_order_create()
{
    global $wpdb;

    $input = file_get_contents("php://input");
    $respons = array(
        'status' => 'error',
        'message' => 'Lengkapi formulir pembelian',
        'data' => array()
    );

    /*
    no input ? return.
     */
    if (empty($input)) exit;
    $data = json_decode($input, true);

    /*
    Check nonce.
     */
    if (isset($data['nonce'])  && wp_verify_nonce($data['nonce'], 'marketpress')) :

        if (!isset($data['customer']) || !is_array($data['customer'])) {
            $respons['message'] = 'Data customer tidak valid';
            echo json_encode($respons);
            exit;
        }

        if (!isset($data['detail']) || !is_array($data['detail'])) {
            $respons['message'] = 'Data detail order tidak valid';
            echo json_encode($respons);
            exit;
        }

        $customer = $data['customer'];
        $detail = $data['detail'];
        $subtotal = 0;

        foreach ((array) $data['items'] as $i) {
            $price = intval($i['price']);
            $subsubtotal = intval($i['quantity']) * $price;
            $subtotal = $subtotal + $subsubtotal;

            $i['total'] = $subsubtotal;
        }
        $detail['subtotal'] = $subtotal;

        $order = new MarketPress_Order();
        $order->set_customer($customer);
        $order->set_detail($detail);
        $order->set_items($data['items']);

        $order_id = $order->create();

        //$new_order = marketpress_get_order($order_id);

        $respons['status'] = 'success';
        $respons['message'] = 'Order created';
        $respons['data'] = array(
            'key' => marketpress_crypt($order_id),
        );
        echo json_encode($respons);
        exit;
    endif;
}

add_filter('manage_marketpress-order_posts_columns', 'marketpress_order_column');
add_action('manage_marketpress-order_posts_custom_column', 'marketpress_order_content_column', 10, 2);
/**
 * order set custom column
 * @param  [type] $columns [description]
 * @return [type]          [description]
 */
function marketpress_order_column($columns)
{

    $new_columns['cb'] = '<input type="checkbox"/>';
    $new_columns['order_name'] = __('Title', 'marketpress');
    $new_columns['order_date'] = __('Date', 'marketpress');
    $new_columns['order_customer'] = __('Customer', 'marketpress');
    //$new_columns['order_product'] = __('Product', 'marketpress');
    $new_columns['order_status'] = __('Status', 'marketpress');
    $new_columns['order_followup'] = __('Follow Up', 'marketpress');
    $new_columns['order_action'] = __('&nbsp;', 'marketpress');

    return $new_columns;
}

/**
 * order manage custom column
 * @param  [type] $column  [description]
 * @param  [type] $post_id [description]
 * @return [type]          [description]
 */
function marketpress_order_content_column($column, $post_id)
{

    $phone = get_post_meta($post_id, 'customer_phone', true);

    $phone = preg_replace('/[^0-9]/', '', $phone);
    $phone = preg_replace('/^620/', '62', $phone);
    $phone = preg_replace('/^0/', '62', $phone);

    $product_id = get_post_meta($post_id, 'order_product_id', true);

    switch ($column):

        case 'order_name':
            echo '<span style="font-weight: bold">' . get_the_title($post_id) . '</span>';
            break;

        case 'order_date':
            echo get_the_date('Y-m-d H:i:s', $post_id);
            break;

        case 'order_customer':
            echo '<span>' . get_post_meta($post_id, 'customer_name', true) . '</span><br/>';
            echo '<span>( ' . $phone . ' )</span><br/>';
            break;

        case 'order_status':
            $statuse = get_post_status($post_id);
            $statuses = marketpress_order_statuses();

            $statuse = isset($statuses[$statuse]) ? $statuse : 'pending';
            $status = isset($statuses[$statuse]) ? $statuses[$statuse] : 'Pending';
            echo '<div class="order-status-' . $statuse . '">' . $status . '</div>';
            break;

        case 'order_followup':
            echo '<button type="button" class="button button-primary" onclick="customerFollowUp(\'' . $phone . '\');">Follow Up</button>';
            break;

        case 'order_action':
            echo '<div style="text-align:right">';
            echo '<a href="' . get_edit_post_link($post_id) . '" class="button">View Order</a>&nbsp';
            //echo '<a href="'.get_delete_post_link( $post_id ).'" class="button">Delete</a>';
            echo '</div>';
            break;

    endswitch;
}

/**
 * get order data
 */
function marketpress_get_order($order_id)
{
    $order = new MarketPress_Order($order_id);

    return $order;
}

/**
 * order statuses
 */
function marketpress_order_statuses()
{
    $statuses = array(
        'pending'   => 'Pending',
        'on_process'  => 'On Process',
        'on_shipping' => 'On Shipping',
        'completed'   => 'Completed',
        // 'cancelled'   => 'Cancelled',
        // 'refunded'    => 'Refunded',
    );

    $statuses = apply_filters('marketpress_order_statuses', $statuses);
    return $statuses;
}

/**
 * get order id by encrypted thanks id
 */
function marketpress_get_order_id_by_thanks_id($thanks_id)
{
    global $wpdb;

    $order_id = marketpress_crypt($thanks_id, true);

    if (empty($order_id)) return false;

    return $order_id;
}

function marketpress_change_order_status($order_id, $new_status)
{
    global $wpdb;

    $old_status = get_post_status($order_id);
    if ($old_status != $new_status) {

        $wpdb->update(
            $wpdb->posts,
            array(
                'post_status' => sanitize_text_field($new_status)
            ),
            array(
                'ID' => intval($order_id)
            )
        );

        update_post_meta($order_id, 'status', $new_status);
        do_action('marketpress_order_change_status', $order_id, $old_status, $new_status);

        clean_post_cache($order_id);
    }
}

add_action('wp_ajax_export_orders', 'ajax_export_orders');
function ajax_export_orders()
{
    $nonce = isset($_REQUEST['nonce']) ? $_REQUEST['nonce'] : '';
    if (!wp_verify_nonce($nonce, 'marketpress')) exit;

    $upload_dir   = wp_upload_dir();

    $args = array(
        'post_type' => 'marketpress-order',
        'posts_per_page' => -1,
        'fields' => 'ids',
    );

    if ($_POST['status'] !== 'all') :
        $args['post_status'] = sanitize_text_field($_POST['status']);
    endif;

    $posts = new WP_Query($args);

    $list = array();

    $list[] = array(
        'Invoice',
        'Date',
        'Customer Name',
        'Customer Phone',
        'Customer Email',
        'Customer Address',
        'Sub Total',
        'Shipping',
        'Discount',
        'Total',
        'Weight',
        'Payment Method',
        'Product 1',
        'Product 2',
        'Product 3',
        'Product 4',
        'Product 5',
        'Product 6',
        'Product 7',
        'Product 8',
        'Product 9',
        'Product 10',
        'Product 11',
        'Product 12',
        'Product 13',
        'Product 14',
        'Product 15',
        'Product 16',
        'Product 17',
        'Product 18',
        'Product 19',
        'Product 20',
    );

    foreach ((array)$posts->posts as $id) :
        $phone = get_post_meta($id, 'customer_phone', true);

        $phone = preg_replace('/[^0-9]/', '', $phone);
        $phone = preg_replace('/^620/', '62', $phone);
        $phone = preg_replace('/^0/', '62', $phone);

        $address = get_post_meta($id, 'customer_address', true);

        $subdistrict = get_post_meta($id, 'customer_subdistrict_name', true);
        if ($subdistrict) {
            $address .= ', Kecamatan: ' . $subdistrict;
        }

        $district = get_post_meta($id, 'customer_district_name', true);
        if ($district) {
            $address .= ', ' . get_post_meta($id, 'customer_district_type', true) . ': ' . $district;
        }

        $province = get_post_meta($id, 'customer_province_name', true);
        if ($province) {
            $address .= ', Provinsi: ' . $province;
        }

        $payment_method = '';

        if (get_post_meta($id, 'payment_method', true) == 'cod') {
            $payment_method = 'COD (Bayar ditempat)';
        } else {
            $payment_method = 'Transfer Bank';
        }

        $items = get_post_meta($id, 'items', true);

        $dd = array(
            get_the_title($id),
            get_the_date('Y-m-d H:i:s', $id),
            get_post_meta($id, 'customer_name', true),
            $phone,
            get_post_meta($id, 'customer_email', true),
            $address,
            get_post_meta($id, 'subtotal', true),
            get_post_meta($id, 'shipping_cost', true) . ' (' . get_post_meta($id, 'shipping_name', true) . ')',
            get_post_meta($id, 'discount_amount', true) . ' (' . get_post_meta($id, 'discount_code', true) . ')',
            get_post_meta($id, 'total', true),
            get_post_meta($id, 'weight', true),
            $payment_method,
        );

        $ddd = array();

        foreach ((array)$items as $item) {

            $i = $item['name'] . ' (' . $item['quantity'] . 'X)';
            $i .= ' ' . $item['variation_1_label'] . ': ' . $item['variation_1_value'];
            $i .= ' ' . $item['variation_2_label'] . ': ' . $item['variation_2_value'];
            $i .= ' Catatan: ' . $item['note'];

            $ddd[] = $i;
        }

        $list[] = array_merge($dd, $ddd);


    endforeach;

    $filename = 'orders-export-status-' . $_POST['status'] . '-' . date('y-m-d-H-i-s') . '.csv';

    $file = fopen(WP_CONTENT_DIR . '/uploads/' . $filename, 'w');

    foreach ($list as $line) {
        fputcsv($file, $line);
    }

    fclose($file);

    echo WP_CONTENT_URL . '/uploads/' . $filename;
    exit;
}