<?php

/**
 * order classes
 */
class MarketPress_Order
{
    /**
     * define inserted argument
     */
    private $args = array();

    /**
     * define output order data
     */
    public $data = array();

    public $meta = array();

    public $_customer = array(
        'name'    => '',
        'phone'   => '',
        'email' => '',
        'address' => '',
        'subdistrict_id' => '',
        'subdistrict_name' => '',
        'district_id' => '',
        'district_name' => '',
        'district_type' => '',
        'province_id' => '',
        'province_name' => '',
        'postal' => ''
    );

    public $_detail = array(
        'payment_method' => '',
        'discount_amount' => 0,
        'discount_code' => '',
        'shipping_cost' => 0,
        'shipping_etd' => '',
        'shipping_name' => '',
        'subtotal' => 0,
        'total' => 0,
        'weight' => 0,
    );

    public $_items = array();

    /**
     * construction
     */
    public function __construct($order_id = false)
    {
        $post = get_post($order_id);

        if ($post && 'marketpress-order' == $post->post_type) {
            $data = array(
                'id' => $post->ID,
                'title' => $post->post_title,
                'status' => $post->post_status,
                'date' => $post->post_date,
            );

            $get_meta = get_post_meta($post->ID);
            $meta = array();
            foreach ((array)$get_meta as $key => $val) {
                $value = maybe_unserialize($val[0]);
                $value = is_array($value) || is_object($value) ? $value : sanitize_text_field($value);
                $meta[$key] = $value;
            }

            $data = wp_parse_args($meta, $data);

            $this->data = $data;
        }
    }

    /**
     * getter
     * @param  string $key [description]
     * @return [type]      [description]
     */
    public function __get($key)
    {
        $data = is_object($this->data) ? $this->data : (object)$this->data;
        $data = get_object_vars($data);

        if (array_key_exists($key, $data))
            return $data[$key];

        return NULL;
    }

    /**
     * get customer
     */
    public function get_customer()
    {
        $customer = array();
        foreach ($this->_customer as $key => $val) {
            $customer[$key] = isset($this->data['customer_' . $key]) ? $this->data['customer_' . $key] : '';
        }

        return $customer;
    }

    /**
     * get detail order
     */
    public function get_detail()
    {
        $detail = array();
        foreach ($this->_detail as $d => $val) {
            $detail[$d] = isset($this->data[$d]) ? $this->data[$d] : '';
        }

        unset($detail['items']);

        return $detail;
    }

    /**
     * get order items
     */
    public function get_items()
    {
        return $this->data['items'];
    }

    /**
     * set customer detail
     */
    public function set_customer($args = array())
    {
        $this->_customer = wp_parse_args($args, $this->_customer);

        return $this;
    }

    /**
     * set order detail
     */
    public function set_detail($args = array())
    {
        $this->_detail = wp_parse_args($args, $this->_detail);

        return $this;
    }

    /**
     * set order items
     */
    public function set_items($args = array())
    {
        $this->_items = wp_parse_args($args, $this->_items);

        return $this;
    }

    /**
     * order created
     */
    public function create()
    {
        global $wpdb;

        if (empty($this->_items)) return false;

        $invoice_last_number = get_option('invoice_number', 0);
        $invoice_number = intval($invoice_last_number) + 1;

        $inv = get_option('mes_order_invoice_format', 'INV/{year}/{month}/{date}/{number}');
        list($y, $m, $d) = explode('-', date('Y-m-d', strtotime('now')));

        $inv = str_replace('{year}', $y, $inv);
        $inv = str_replace('{month}', $m, $inv);
        $inv = str_replace('{date}', $d, $inv);

        $inv = str_replace('{number}', $invoice_number, $inv);

        $post_data = array(
            'post_title'   => '#' . $inv,
            //'post_name'    => sanitize_title('order-' . $invoice_number),
            'post_content' => '',
            'post_status'  => 'pending',
            'post_author'  => 1,
            'post_type'    => 'marketpress-order',
        );
        $post_id = wp_insert_post($post_data);
        if (is_wp_error($post_id)) {
            return new WP_Error('failed_create_order', $post_id->get_error_message());
        }
        update_option('invoice_number', $invoice_number);
        $wpdb->update(
            $wpdb->posts,
            array(
                'post_name' => sanitize_title($inv)
            ),
            array(
                'ID' => $post_id
            )
        );

        foreach ((array) $this->_customer as $key => $val) {
            update_post_meta($post_id, 'customer_' . $key, sanitize_text_field($val));
        }

        foreach ((array) $this->_detail as $key => $val) {
            update_post_meta($post_id, $key, sanitize_text_field($val));
        }

        update_post_meta($post_id, 'invoice_number', $inv);

        update_post_meta($post_id, 'items', $this->_items);
        update_post_meta($post_id, 'status', 'pending');

        $coupon = marketpress_get_coupon($this->_detail['discount_code']);

        $usage = intval(get_post_meta($coupon->id, 'coupon_usage', true));
        $usage = $usage + 1;

        update_post_meta($coupon->id, 'coupon_usage', $usage);

        do_action('marketpress_order_created', $post_id);

        return $post_id;
    }
}