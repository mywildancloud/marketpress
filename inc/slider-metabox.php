<?php

/**
 * Marketpress slider
 */

add_action('cmb2_admin_init', 'marketpress_slider_data_metabox');
/**
 * InstaOrder slider data
 */
function marketpress_slider_data_metabox()
{
    /**
     * Sample metabox to demonstrate each field type included
     */
    $cmb_options = array(
        'id'            => 'marketpress_slider_metabox',
        'title'         => esc_html__('Slider Data', 'marketpress'),
        'object_types'  => array('slider'), // Post type
        // 'show_on_cb' => 'yourprefix_show_if_front_page', // function should return a bool value
        'context'    => 'normal',
        'priority'   => 'high',
        // 'show_names' => true, // Show field names on the left
        // 'cmb_styles' => false, // false to disable the CMB stylesheet
        // 'closed'     => true, // true to keep the metabox closed by default
        // 'classes'    => 'extra-class', // Extra cmb2-wrap classes
        // 'classes_cb' => 'yourprefix_add_some_classes', // Add classes through a callback.

    );

    $cmb = new_cmb2_box($cmb_options);

    $cmb->add_field(
        array(
            'name'        => 'Image',
            'id'          => 'slider_image_url',
            'type'        => 'file',
            'description' => 'Ukuran banner slider 1140px * 512px rekomendasi dan batas maximal ukuran'
        )
    );

    $cmb->add_field(
        array(
            'name'    => 'Link',
            'id'      => 'slider_image_link',
            'type'    => 'text',
        )
    );
}
