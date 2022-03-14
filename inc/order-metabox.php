<?php

/**
 * marketpress metabox function
 * @package marketpress/inc
 */


add_action('cmb2_admin_init', 'marketpress_order_customer_metabox');
function marketpress_order_customer_metabox()
{
    $post_id = null;
    if (isset($_GET['post'])) {
        $post_id = $_GET['post'];
    } else if (isset($_POST['post_ID'])) {
        $post_id = $_POST['post_ID'];
    }

    /**
     * customer detail order metabox
     */
    $cmb = new_cmb2_box(array(
        'id'            => 'marketpress_order_customer_metabox',
        'title'         => esc_html__('Customer', 'marketpress'),
        'object_types'  => array('marketpress-order'),
        'context'    => 'side',
    ));

    $cmb->add_field(array(
        'name'       => esc_html__('Name', 'marketpress'),
        'desc'       => '',
        'id'         => 'customer_name',
        'type'       => 'text',
    ));

    $cmb->add_field(array(
        'name'       => esc_html__('Phone', 'marketpress'),
        'desc'       => '',
        'id'         => 'customer_phone',
        'type'       => 'text',
    ));

    $cmb->add_field(array(
        'name'       => esc_html__('Email', 'marketpress'),
        'desc'       => '',
        'id'         => 'customer_email',
        'type'       => 'text',
    ));

    $cmb->add_field(array(
        'name'       => esc_html__('Address', 'marketpress'),
        'desc'       => '',
        'id'         => 'customer_address',
        'type'       => 'textarea_small',
    ));

    $cmb->add_field(array(
        'name'       => esc_html__('Kecamatan', 'marketpress'),
        'desc'       => '',
        'id'         => 'customer_subdistrict_name',
        'type'       => 'text',
    ));

    $cmb->add_field(array(
        'name'       => esc_html__('Kabupaten/Kota', 'marketpress'),
        'desc'       => '',
        'id'         => 'customer_district_name',
        'type'       => 'text',
    ));

    $cmb->add_field(array(
        'name'       => esc_html__('Provinsi', 'marketpress'),
        'desc'       => '',
        'id'         => 'customer_province_name',
        'type'       => 'text',
    ));

    $phone = get_post_meta($post_id, 'customer_phone', true);
    $phone = preg_replace('/[^0-9]/', '', $phone);
    $phone = preg_replace('/^620/', '62', $phone);
    $phone = preg_replace('/^0/', '62', $phone);

    $cmb->add_field(array(
        'name'       => '<button type="button" style="width: 100%;" class="button button-primary" onclick="customerFollowUp(\'' . $phone . '\');"> Follow Up</button>',
        'desc'       => '',
        'id'         => 'submit',
        'type'       => 'title',
    ));
}

add_action('admin_menu', function () {
    remove_meta_box('submitdiv', 'marketpress-order', 'side');
});

add_action('cmb2_save_field', 'marketpress_save_order_status', 10, 4);
function marketpress_save_order_status($field_id, $updated, $action, $ini)
{

    if ($field_id == 'status') :

        $order_id = intval($ini->data_to_save['post_ID']);
        $new_status = sanitize_text_field($ini->value);

        marketpress_change_order_status($order_id, $new_status);
    endif;
}


add_action('cmb2_admin_init', 'marketpress_order_detail_metabox');
/**
 * Hook in and add a demo metabox. Can only happen on the 'cmb2_admin_init' or 'cmb2_init' hook.
 */
function marketpress_order_detail_metabox()
{
    $post_id = null;
    if (isset($_GET['post'])) {
        $post_id = $_GET['post'];
    } else if (isset($_POST['post_ID'])) {
        $post_id = $_POST['post_ID'];
    }

    $items = get_post_meta($post_id, 'items', true);

    ob_start();
?>
<table>
    <thead>
        <tr>
            <th>Produk</th>
            <th style="text-align: right">Qty</th>
            <th style="text-align: right">Price (@)</th>
            <th style="text-align: right">Sub Total</th>
        </tr>
        </tehad>
    <tbody>
        <?php foreach ((array)$items as $item) : ?>
        <?php
                if (!is_array($item)) continue;
                ?>
        <tr>
            <td>
                <?php
                        echo $item['name'];
                        if ($item['variation_1_label']) {
                            echo '<br/>';
                            echo '-<span style="font-size:12px;color:#666">' . $item['variation_1_label'] . ': ' . $item['variation_1_value'] . '</span>';
                        }
                        if ($item['variation_2_label']) {
                            echo '<br/>';
                            echo '-<span style="font-size:12px;color:#666">' . $item['variation_2_label'] . ': ' . $item['variation_2_value'] . '</span>';
                        }
                        if ($item['note']) {
                            echo '<br/>';
                            echo '<br/>';
                            echo '<span style="font-size:12px;color:#666">Note: ' . $item['note'] . '</span>';
                        }
                        ?>
            </td>
            <td style="text-align: right"><?php echo $item['quantity']; ?></td>
            <td style="text-align: right">
                <?php
                        echo number_format($item['price'], 0, '.', '.');
                        if ($item['price_plus']) {
                            echo '<br/>';
                            echo '+<span style="font-size:12px;color:#666">' . number_format($item['price_plus'], 0, '.', '.') . '</span>';
                        }
                        ?>
            </td>
            <td style="text-align: right">
                <?php
                        echo number_format($item['total'], 0, '.', '.');
                        ?>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<?php
    $d = ob_get_contents();
    ob_end_clean();

    /**
     * Sample metabox to demonstrate each field type included
     */
    $cmb = new_cmb2_box(array(
        'id'            => 'marketpress_order_detail_metabox',
        'title'         => esc_html__('Detail', 'marketpress'),
        'object_types'  => array('marketpress-order'),
    ));

    function marketpress_metabox_number_format($value, $field_args, $field)
    {
        $value = intval($value);
        return number_format($value, 0, '.', '.');
    }

    $cmb->add_field(array(
        'name'       => esc_html__('Items', 'marketpress'),
        'desc'       => '',
        'id'         => 'test',
        'before'     => '<p>' . $d . '</p>',
        'type'       => 'text',
        'save_field' => false,
        'default' => '',
        'attributes' => array(
            'readonly' => 'readonly',
            'disabled' => 'disabled',
            'style' => 'display:none'
        )
    ));

    $cmb->add_field(array(
        'name'       => esc_html__('Sub Total', 'marketpress'),
        'desc'       => '',
        'id'         => 'subtotal',
        'type'       => 'text',
        'save_field' => false,
        'attributes' => array(
            'readonly' => 'readonly',
        ),
        'escape_cb' => 'marketpress_metabox_number_format',
    ));

    $cmb->add_field(array(
        'name'       => esc_html__('Weight Total (gram)', 'marketpress'),
        'desc'       => '',
        'id'         => 'weight',
        'type'       => 'text',
        'save_field' => false,
        'attributes' => array(
            'readonly' => 'readonly',
        ),
        'escape_cb' => function ($value, $field_args, $field) {

            //$value = (int) ceil($value / 1000) * 1000;

            return $value;
        }
    ));

    $cmb->add_field(array(
        'name'       => esc_html__('Shipping Cost', 'marketpress'),
        'desc'       => '',
        'id'         => 'shipping_cost',
        'type'       => 'text',
        'save_field' => false,
        'attributes' => array(
            'readonly' => 'readonly',
        ),
        'escape_cb' => 'marketpress_metabox_number_format',
    ));

    $cmb->add_field(array(
        'name'       => esc_html__('COD Cost (Biaya COD)', 'marketpress'),
        'desc'       => '',
        'id'         => 'payment_cod_cost',
        'type'       => 'text',
        'save_field' => false,
        'attributes' => array(
            'readonly' => 'readonly',
        ),
        'escape_cb' => 'marketpress_metabox_number_format',
    ));

    $cmb->add_field(array(
        'name'       => esc_html__('Discount', 'marketpress'),
        'desc'       => '',
        'id'         => 'discount_amount',
        'type'       => 'text',
        'save_field' => false,
        'attributes' => array(
            'readonly' => 'readonly',
        ),
        'escape_cb' => 'marketpress_metabox_number_format',
    ));

    $cmb->add_field(array(
        'name'       => esc_html__('Total', 'marketpress'),
        'desc'       => '',
        'id'         => 'total',
        'type'       => 'text',
        'save_field' => false,
        'attributes' => array(
            'readonly' => 'readonly',
        ),
        'escape_cb' => 'marketpress_metabox_number_format',
    ));
}

add_action('cmb2_admin_init', 'marketpress_order_action_metabox');
function marketpress_order_action_metabox()
{
    $order = null;
    $post_id = null;
    if (isset($_GET['post'])) {
        $post_id = $_GET['post'];
    } else if (isset($_POST['post_ID'])) {
        $post_id = $_POST['post_ID'];
    }

    $order = marketpress_get_order($post_id);

    /**
     * Sample metabox to demonstrate each field type included
     */
    $cmb = new_cmb2_box(array(
        'id'            => 'marketpress_order_action_metabox',
        'title'         => esc_html__('Action', 'marketpress'),
        'object_types'  => array('marketpress-order'),
        'context'    => 'normal',
    ));

    $cmb->add_field(array(
        'name'       => esc_html__('Discount Code', 'marketpress'),
        'desc'       => '',
        'id'         => 'discount_code',
        'type'       => 'text',
        'save_field' => false,
        'attributes' => array(
            'readonly' => 'readonly',
        )
    ));

    $cmb->add_field(array(
        'name'       => esc_html__('Payment Method', 'marketpress'),
        'desc'       => '',
        'id'         => 'payment_method',
        'type'       => 'text',
        'save_field' => false,
        'attributes' => array(
            'readonly' => 'readonly',
        ),
        'escape_cb' => function ($value, $field_args, $field) {

            if ($value == 'cod') {
                $value = 'COD (Bayar ditempat)';
            } else if ($value == 'manual') {
                $value = 'Transfer Bank';
            }

            return $value;
        }
    ));

    $cmb->add_field(array(
        'name'       => esc_html__('Shipping Service', 'marketpress'),
        'desc'       => '',
        'id'         => 'shipping_name',
        'type'       => 'text',
        'save_field' => false,
        'default' => 'Flat Ongkir',
    ));

    $cmb->add_field(array(
        'name'       => esc_html__('Shipping Bill Number', 'marketpress'),
        'desc'       => '',
        'id'         => 'shipping_bill_number',
        'type'       => 'text',
    ));

    $cmb->add_field(array(
        'name'       => esc_html__('Status', 'marketpress'),
        'desc'       => '',
        'id'         => 'status',
        'type'       => 'select',
        'options' => marketpress_order_statuses(),
    ));

    $cmb->add_field(array(
        'name'       => '<button type="submit" style="width: 100%;" class="button button-primary button-hero">Simpan Order</button>',
        'desc'       => '',
        'id'         => 'submit',
        'type'       => 'title',
    ));
}