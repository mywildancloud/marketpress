<?php
add_action('cmb2_admin_init', 'marketpress_register_metabox');
/**
 * Hook in and add a demo metabox. Can only happen on the 'cmb2_admin_init' or 'cmb2_init' hook.
 */
function marketpress_register_metabox()
{

    $cmb_scripts = new_cmb2_box(array(
        'id'            => 'marketpress_script_metabox',
        'title'         => esc_html__('Head and Footer Custom Scripts', 'marketpress'),
        'object_types'  => array('page', 'post', 'marketpress-product', 'marketpress-store'), // Post type
        // 'show_on_cb' => 'yourprefix_show_if_front_page', // function should return a bool value
        // 'context'    => 'normal',
        'priority'   => 'low',
        // 'show_names' => true, // Show field names on the left
        // 'cmb_styles' => false, // false to disable the CMB stylesheet
        // 'closed'     => true, // true to keep the metabox closed by default
        // 'classes'    => 'extra-class', // Extra cmb2-wrap classes
        // 'classes_cb' => 'yourprefix_add_some_classes', // Add classes through a callback.

        /*
		 * The following parameter is any additional arguments passed as $callback_args
		 * to add_meta_box, if/when applicable.
		 *
		 * CMB2 does not use these arguments in the add_meta_box callback, however, these args
		 * are parsed for certain special properties, like determining Gutenberg/block-editor
		 * compatibility.
		 *
		 * Examples:
		 *
		 * - Make sure default editor is used as metabox is not compatible with block editor
		 *      [ '__block_editor_compatible_meta_box' => false/true ]
		 *
		 * - Or declare this box exists for backwards compatibility
		 *      [ '__back_compat_meta_box' => false ]
		 *
		 * More: https://wordpress.org/gutenberg/handbook/extensibility/meta-box/
		 */
        // 'mb_callback_args' => array( '__block_editor_compatible_meta_box' => false ),
    ));

    $cmb_scripts->add_field(array(
        'name' => esc_html__('Custom Head Scripts', 'marketpress'),
        'desc' => esc_html__('Insert custom script to head', 'marketpress'),
        'id'   => 'head_script_code',
        'type' => 'textarea',
        'sanitization_cb' => false,
        'escape_cb' => false,
    ));

    $cmb_scripts->add_field(array(
        'name' => esc_html__('Custom Footer Scripts', 'marketpress'),
        'desc' => esc_html__('Insert custom script to footer', 'marketpress'),
        'id'   => 'footer_script_code',
        'type' => 'textarea',
        'sanitization_cb' => false,
        'escape_cb' => false,
    ));
}

add_action('cmb2_admin_init', 'marketpress_hidden_link_register_metabox');
/**
 * Hook in and add a demo metabox. Can only happen on the 'cmb2_admin_init' or 'cmb2_init' hook.
 */
function marketpress_hidden_link_register_metabox()
{

    $cmb_scripts = new_cmb2_box(array(
        'id'            => 'marketpress_hiddden_affiliate_metabox',
        'title'         => esc_html__('Hidden Affiliate Link', 'marketpress'),
        'object_types'  => array('page', 'post', 'marketpress-product', 'marketpress-store'), // Post type
        // 'show_on_cb' => 'yourprefix_show_if_front_page', // function should return a bool value
        // 'context'    => 'normal',
        'priority'   => 'low',
        // 'show_names' => true, // Show field names on the left
        // 'cmb_styles' => false, // false to disable the CMB stylesheet
        // 'closed'     => true, // true to keep the metabox closed by default
        // 'classes'    => 'extra-class', // Extra cmb2-wrap classes
        // 'classes_cb' => 'yourprefix_add_some_classes', // Add classes through a callback.

        /*
		 * The following parameter is any additional arguments passed as $callback_args
		 * to add_meta_box, if/when applicable.
		 *
		 * CMB2 does not use these arguments in the add_meta_box callback, however, these args
		 * are parsed for certain special properties, like determining Gutenberg/block-editor
		 * compatibility.
		 *
		 * Examples:
		 *
		 * - Make sure default editor is used as metabox is not compatible with block editor
		 *      [ '__block_editor_compatible_meta_box' => false/true ]
		 *
		 * - Or declare this box exists for backwards compatibility
		 *      [ '__back_compat_meta_box' => false ]
		 *
		 * More: https://wordpress.org/gutenberg/handbook/extensibility/meta-box/
		 */
        // 'mb_callback_args' => array( '__block_editor_compatible_meta_box' => false ),
    ));

    $cmb_scripts->add_field(array(
        'name' => esc_html__('Hidden Affiliate Link', 'marketpress'),
        'desc' => esc_html__('Used for cookie stuf', 'marketpress'),
        'id'   => 'hidden_affiliate_link',
        'type' => 'text'
    ));

    $cmb_scripts->add_field(array(
        'name' => esc_html__('Hidden Affiliate Link Tag', 'marketpress'),
        'id'   => 'hidden_affiliate_link_tag',
        'type' => 'radio',
        'options' => array(
            'img' => 'IMG tag (recomended for faster load)',
            'iframe' => 'Iframe tag',
        ),
        'default' => 'img'
    ));
}