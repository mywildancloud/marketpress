<?php

/**
 * Store metabox function
 * @package marketpress/inc
 */

add_action('cmb2_admin_init', 'marketpress_store_metabox');
function marketpress_store_metabox()
{
    $cmb = new_cmb2_box(array(
        'id'            => 'marketpress_store_metabox',
        'title'         => esc_html__('Store Attribution', 'marketpress'),
        'object_types'  => array('marketpress-store'),
        'priority'   => 'low',
    ));

    $cmb->add_field(array(
        'name'       => esc_html__('Harga Coret', 'marketpress'),
        'desc'       => esc_html__('(Isi nominal tanpa titik dan spasi)', 'marketpress'),
        'id'         => 'price',
        'type'       => 'text',
        'attributes' => array(
            'type' => 'number',
            'pattern' => '\d*',
        ),
        'sanitization_cb' => 'absint',
        'escape_cb'       => 'absint',
    ));

    $cmb->add_field(array(
        'name'       => esc_html__('Harga', 'marketpress'),
        'desc'       => esc_html__('(Isi nominal tanpa titik dan spasi)', 'marketpress'),
        'id'         => 'promo_price',
        'type'       => 'text',
        'attributes' => array(
            'type' => 'number',
            'pattern' => '\d*',
        ),
        'sanitization_cb' => 'absint',
        'escape_cb'       => 'absint',
    ));

    $cmb->add_field(array(
        'name'       => esc_html__('Discount', 'marketpress'),
        'desc'       => esc_html__('example: 20% or Rp 50.000 or $5', 'marketpress'),
        'id'         => 'discount',
        'type'       => 'text',
    ));

    $cmb->add_field(array(
        'name'       => esc_html__('Variation 1 Label', 'marketpress'),
        'id'         => 'variation1_label',
        'type'       => 'text',
    ));

    $cmb->add_field(array(
        'name'       => esc_html__('Variation 1 Option', 'marketpress'),
        'id'         => 'variation1_option',
        'type'       => 'text',
        'repeatable' => true,
    ));

    $cmb->add_field(array(
        'name'       => esc_html__('Variation 2 Label', 'marketpress'),
        'id'         => 'variation2_label',
        'type'       => 'text',
    ));

    $group_color = $cmb->add_field(array(
        'name'    => 'Variation 2 Options',
        'id'      => 'variation2_option',
        'type'    => 'group',
        'options' => array(
            'remove_button' => 'Remove Options',
            'add_button' => 'Add Options',
            'sortable' => true,
        ),
    ));

    $cmb->add_group_field(
        $group_color,
        array(
            'name'    => 'Option Name',
            'id'      => 'text',
            'type'    => 'text',
        )
    );

    $cmb->add_group_field(
        $group_color,
        array(
            'name'        => 'Custom Price',
            'id'          => 'price',
            'type'        => 'text',
            'description' => 'Empty this field if no additional price',
            'attributes' => array(
                'type' => 'number',
                'pattern' => '\d*',
            ),
            'sanitization_cb' => 'absint',
            'escape_cb'       => 'absint',
        )
    );

    $cmb->add_field(array(
        'name'       => esc_html__('Weight', 'marketpress'),
        'desc'       => esc_html__('Grams Unit', 'marketpress'),
        'id'         => 'store_weight',
        'type'       => 'text',
        'attributes' => array(
            'type' => 'number',
            'pattern' => '\d*',
        ),
        'default' => 1000,
    ));

    $cmb->add_field(array(
        'name'             => 'Label',
        'id'               => 'label',
        'type'             => 'radio',
        'options'          => array(
            'default' => __('Default', 'cmarketpress'),
            'bestseller'   => __('Terlaris', 'marketpress'),
            'flashsale'     => __('Flashsale', 'marketpress'),
        ),
        'default' => 'default'
    ));

    $cmb->add_field(array(
        'name'             => 'Scarcity countdown type',
        'id'               => 'scarcity_type',
        'type'             => 'radio',
        'options'          => array(
            'fixed'   => __('Fixed', 'marketpress'),
            'evergreen'     => __('Evergreen', 'marketpress'),
        ),
        'default' => 'fixed'
    ));

    $cmb->add_field(array(
        'name' => 'Scarcity Fixed Date',
        'id'   => 'scarcity_fixed_date',
        'type' => 'text_date',
        'attributes' => array(
            'data-conditional-id'    => 'label',
            'data-conditional-value' => wp_json_encode(array('bestseller', 'default')),
        ),
        'date_format' => 'Y-m-d',
    ));

    $cmb->add_field(array(
        'name' => 'Scarcity Fixed Time',
        'id'   => 'scarcity_fixed_time',
        'type' => 'text_time',
        'attributes' => array(
            'data-conditional-id'    => 'label',
            'data-conditional-value' => wp_json_encode(array('bestseller', 'default')),
            'data-timepicker' => json_encode(array(
                'timeOnlyTitle' => __('Choose your Time', 'cmb2'),
                'timeFormat' => 'HH:mm',
            )),
        ),
        'time_format' => 'H:i',
    ));

    $cmb->add_field(array(
        'name' => 'Scarcity Evergreen Time',
        'id'   => 'scarcity_evergreen_time',
        'type' => 'text_time',
        'attributes' => array(
            'data-conditional-id'    => 'label',
            'data-conditional-value' => wp_json_encode(array('bestseller', 'default')),
            'data-timepicker' => json_encode(array(
                'timeOnlyTitle' => __('Choose your Time', 'cmb2'),
                'timeFormat' => 'HH:mm',
            )),
        ),
        'time_format' => 'H:i',
    ));

    $cmb->add_field(array(
        'name' => 'Scarcity Label',
        'id'   => 'scarcity_label',
        'type' => 'text',
        'default' => 'Penawaran Terbatas',
        'attributes' => array(
            'data-conditional-id'    => 'label',
            'data-conditional-value' => wp_json_encode(array('bestseller', 'default')),
        ),
    ));

    $cmb->add_field(array(
        'name' => esc_html__('Out On Stock', 'marketpress'),
        'desc' => esc_html__('check if this product is out on stock', 'marketpress'),
        'id'   => 'out_on_stock',
        'type' => 'checkbox',
    ));
}


add_action('cmb2_admin_init', 'marketpress_store_galery_metabox');
function marketpress_store_galery_metabox()
{
    $cmb = new_cmb2_box(array(
        'id'            => 'marketpress_store_galrey_metabox',
        'title'         => esc_html__('Store Images', 'marketpress'),
        'object_types'  => array('marketpress-store'),
        'priority'   => 'low',
        'context' => 'side'
    ));

    $cmb->add_field(array(
        'name' => 'Store Images',
        'desc' => '',
        'id'   => 'store_images',
        'type' => 'file_list',
        // 'preview_size' => array( 100, 100 ), // Default: array( 50, 50 )
        'query_args' => array('type' => 'image'), // Only images attachment
    ));
}

add_filter('gettext', 'wpse227645_gettext', 10, 2);

/**
 * Change excerpt label
 * source https://www.webnetcreatives.net/change-excerpt-box-label-wordpress/
 */
function wpse227645_gettext($translation, $original)
{
    if ('Excerpt' == $original) {
        return 'Keterangan'; //Change here to what you want Excerpt box to be called
    } else {
        $pos = strpos($original, 'Excerpts are optional hand-crafted summaries of your');

        if ($pos !== false) {
            return  'Isi keterangan produk anda disini...'; //Change the default text you see below the box with link to learn more...
        }
    }
    return $translation;
}

add_action('cmb2_admin_init', 'marketpress_store_register_taxonomy_metabox');
/**
 * Hook in and add a metabox to add fields to taxonomy terms
 */
function marketpress_store_register_taxonomy_metabox()
{

    /**
     * Metabox to add fields to categories and tags
     */
    $cmb_term = new_cmb2_box(array(
        'id'               => 'marketpress_store_term_edit',
        'title'            => esc_html__('Category Metabox', 'cmb2'), // Doesn't output for term boxes
        'object_types'     => array('term'), // Tells CMB2 to use term_meta vs post_meta
        'taxonomies'       => array('marketpress-store-category'), // Tells CMB2 which taxonomies should have these fields
        'new_term_section' => true,
    ));

    $cmb_term->add_field(array(
        'name' => esc_html__('Icon', 'cmb2'),
        'desc' => esc_html__('Upload category icon', 'cmb2'),
        'id'   => 'icon',
        'type' => 'file',
    ));
}

add_action('cmb2_admin_init', 'marketpress_product_mp_metabox');

function marketpress_product_mp_metabox()
{
    /**
     * Sample metabox to demonstrate each field type included
     */
    $cmb_options = array(
        'id'            => 'marketpress_product_mp_metabox',
        'title'         => esc_html__('Marketplace Link', 'marketpress'),
        'object_types'  => array('marketpress-store'), // Post type
        // 'show_on_cb' => 'yourprefix_show_if_front_page', // function should return a bool value
        'context'    => 'side',
        'priority'   => 'low',
        // 'show_names' => true, // Show field names on the left
        // 'cmb_styles' => false, // false to disable the CMB stylesheet
        // 'closed'     => true, // true to keep the metabox closed by default
        // 'classes'    => 'extra-class', // Extra cmb2-wrap classes
        // 'classes_cb' => 'yourprefix_add_some_classes', // Add classes through a callback.

    );

    $cmb = new_cmb2_box($cmb_options);

    $cmb->add_field(array(
        'name' => 'Product Link on Tokopedia',
        'id'      => 'product_mp_tokopedia',
        'type'    => 'text',
    ));

    $cmb->add_field(array(
        'name' => 'Product Link on Bukalapak',
        'id'      => 'product_mp_bukalapak',
        'type'    => 'text',
    ));

    $cmb->add_field(array(
        'name' => 'Product Link on Lazada',
        'id'      => 'product_mp_lazada',
        'type'    => 'text',
    ));

    $cmb->add_field(array(
        'name' => 'Product Link on Shoppe',
        'id'      => 'product_mp_shoppe',
        'type'    => 'text',
    ));
}