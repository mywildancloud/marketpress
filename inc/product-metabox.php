<?php

/**
 * product metabox function
 * @package marketpress/inc
 */

add_action('cmb2_admin_init', 'marketpress_product_metabox');
function marketpress_product_metabox()
{
    $cmb = new_cmb2_box(array(
        'id'            => 'marketpress_product_metabox',
        'title'         => esc_html__('Product Attribution', 'marketpress'),
        'object_types'  => array('marketpress-product'),
        'priority'   => 'low',
    ));

    // $cmb->add_field(array(
    //     'name' => esc_html__('Qurency', 'marketpress'),
    //     'id' => 'qurency',
    //     'type' => 'select',
    //     'options' => array(
    //         'idr' => 'Indonesian Rupiah (Rp)',
    //         'usd' => 'US Dollar ($)',
    //     )
    // ));

    $cmb->add_field(array(
        'name'       => esc_html__('Call To Action', 'marketpress'),
        'id'         => 'call_to_action',
        'type'       => 'select',
        'options' => array(
            'button' => 'Button Call To Action',
            'form' => 'Embeded Form Order',
            //'atc' => 'Add To Cart'
        ),
        'default' => 'button',
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
        'name'       => esc_html__('Salespage affiliate link', 'marketpress'),
        'id'         => 'salespage_affiliate_link',
        'type'       => 'text',
        'attributes' => array(
            'data-conditional-id'    => 'call_to_action',
            'data-conditional-value' => 'button',
        ),
    ));

    $cmb->add_field(array(
        'name'       => esc_html__('Salespage affiliate link Open With', 'marketpress'),
        'id'         => 'salespage_affiliate_link_open',
        'type'       => 'radio',
        'options' => array(
            'redirect' => 'Redirect',
            'iframe' => 'Iframe',
        ),
        'default' => 'redirect',
        'attributes' => array(
            'data-conditional-id'    => 'call_to_action',
            'data-conditional-value' => 'button',
        ),
    ));

    $cmb->add_field(array(
        'name'       => esc_html__('Checkout affiliate link', 'marketpress'),
        'id'         => 'checkout_affiliate_link',
        'type'       => 'text',
        'attributes' => array(
            'data-conditional-id'    => 'call_to_action',
            'data-conditional-value' => 'button',
        ),
    ));

    $cmb->add_field(array(
        'name'       => esc_html__('Seller Contact link', 'marketpress'),
        'id'         => 'seller_contact_link',
        'type'       => 'text',
        'attributes' => array(
            'data-conditional-id'    => 'call_to_action',
            'data-conditional-value' => 'button',
        ),
    ));


    $cmb->add_field(array(
        'name'       => esc_html__('Variation 1 Label', 'marketpress'),
        'id'         => 'variation1_label',
        'type'       => 'text',
        'attributes' => array(
            'data-conditional-id'    => 'call_to_action',
            'data-conditional-value' => 'atc',
        ),
    ));

    $cmb->add_field(array(
        'name'       => esc_html__('Weight', 'marketpress'),
        'desc'       => esc_html__('Grams Unit', 'marketpress'),
        'id'         => 'product_weight',
        'type'       => 'text',
        'attributes' => array(
            'type' => 'number',
            'pattern' => '\d*',
            'data-conditional-id'    => 'call_to_action',
            'data-conditional-value' => 'atc',
        ),
        'default' => 1000,
        // 'sanitization_cb' => 'absint',
        // 'escape_cb'       => 'absint',
    ));
}

add_filter('gettext', 'wpse22764_gettext', 10, 2);

/**
 * Change excerpt label
 * source https://www.webnetcreatives.net/change-excerpt-box-label-wordpress/
 */
function wpse22764_gettext($translation, $original)
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

add_action('cmb2_admin_init', 'marketpress_register_taxonomy_metabox');
/**
 * Hook in and add a metabox to add fields to taxonomy terms
 */
function marketpress_register_taxonomy_metabox()
{

    /**
     * Metabox to add fields to categories and tags
     */
    $cmb_term = new_cmb2_box(array(
        'id'               => 'marketpress_term_edit',
        'title'            => esc_html__('Category Metabox', 'cmb2'), // Doesn't output for term boxes
        'object_types'     => array('term'), // Tells CMB2 to use term_meta vs post_meta
        'taxonomies'       => array('marketpress-product-category'), // Tells CMB2 which taxonomies should have these fields
        'new_term_section' => true,
    ));

    $cmb_term->add_field(array(
        'name' => esc_html__('Icon', 'cmb2'),
        'desc' => esc_html__('Upload category icon', 'cmb2'),
        'id'   => 'icon',
        'type' => 'file',
    ));
}