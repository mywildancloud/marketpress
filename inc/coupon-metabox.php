<?php

/**
 * marketpress metabox function
 * @package marketpress/inc
 */


add_action('cmb2_admin_init', 'marketpress_coupon_metabox');
/**
 * marketpress coupon Attribution metabox
 */
function marketpress_coupon_metabox()
{
    /**
     * Sample metabox to demonstrate each field type included
     */
    $cmb_options = array(
        'id'            => 'marketpress_coupon_metabox',
        'title'         => esc_html__('Coupon Data', 'marketpress'),
        'object_types'  => array('marketpress-coupon'), // Post type
        // 'show_on_cb' => 'yourprefix_show_if_front_page', // function should return a bool value
        'context'    => 'normal',
        'priority'   => 'high',
        // 'show_names' => true, // Show field names on the left
        // 'cmb_styles' => false, // false to disable the CMB stylesheet
        // 'closed'     => true, // true to keep the metabox closed by default
        // 'classes'    => 'extra-class', // Extra cmb2-wrap classes
        // 'classes_cb' => 'yourprefix_add_some_classes', // Add classes through a callback.
        'vertical_tabs' => true, // Set vertical tabs, default false
        'tabs' => array(
            array(
                'id'    => 'tab-1',
                'icon' => 'dashicons-star-filled',
                'title' => 'General',
                'fields' => array(
                    'coupon_type',
                    'coupon_discount',
                    'coupon_expired'
                ),
            ),
            array(
                'id'    => 'tab-2',
                'icon' => 'dashicons-admin-page',
                'title' => 'Limitations',
                'fields' => array(
                    'coupon_amount',
                    'coupon_min_cart',
                ),
            )
        )

    );

    $cmb = new_cmb2_box($cmb_options);

    $cmb->add_field(array(
        'name'       => esc_html__('Type', 'marketpress'),
        'id'         => 'coupon_type',
        'type'       => 'select',
        'options' => array(
            'fixed' => 'Fixed Discount',
            'percent' => 'Percentage Discount'
        )
    ));

    $cmb->add_field(array(
        'name' => esc_html__('Discount Value', 'marketpress'),
        'id'   => 'coupon_discount',
        'description' => 'Input discount value, if percentage input value without % simbol',
        'type' => 'text',
        'attributes' => array(
            'type' => 'number',
            'pattern' => '\d*',
        ),
        'sanitization_cb' => 'absint',
        'escape_cb'       => 'absint',
    ));

    $cmb->add_field(array(
        'name'       => esc_html__('Expired', 'marketpress'),
        'id'         => 'coupon_expired',
        'type'       => 'text_date',
        'date_format' => 'd-m-Y'
    ));

    $cmb->add_field(array(
        'name'       => esc_html__('Number of coupons', 'marketpress'),
        'id'         => 'coupon_amount',
        'type'       => 'text',
        'attributes' => array(
            'type' => 'number',
            'pattern' => '\d*',
        )
    ));

    $cmb->add_field(array(
        'name'       => esc_html__('Minimum Cart', 'marketpress'),
        'id'         => 'coupon_min_cart',
        'type'       => 'text',
        'attributes' => array(
            'type' => 'number',
            'pattern' => '\d*',
        ),
        'sanitization_cb' => 'absint',
        'escape_cb'       => 'absint',
    ));
}