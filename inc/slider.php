<?php

/**
 * Marketpress sliders
 */

add_action('init', 'marketpress_slider');
function marketpress_slider()
{
    $lic = new marketpress_License();
    if ($lic->license == 'valid') {
        register_post_type(
            'slider', // Register Custom Post Type
            array(
                'labels' => array(
                    'name'               => __('Slider', 'marketpress'), // Rename these to suit
                    'singular_name'      => __('Slider', 'marketpress'),
                    'add_new'            => __('Add New', 'marketpress'),
                    'add_new_item'       => __('Add New Slider', 'marketpress'),
                    'edit'               => __('Edit', 'marketpress'),
                    'edit_item'          => __('Edit Slider', 'marketpress'),
                    'new_item'           => __('New Slider', 'marketpress'),
                    'view'               => __('View Slider', 'marketpress'),
                    'view_item'          => __('View Slider', 'marketpress'),
                    'search_items'       => __('Search Slider', 'marketpress'),
                    'not_found'          => __('No Sliders found', 'marketpress'),
                    'not_found_in_trash' => __('No Sliders found in Trash', 'marketpress')
                ),
                'public' => false,
                'show_ui' => true,
                'publicly_queryable' => false,
                'exclude_from_search' => true,
                'hierarchical' => false,
                'has_archive' => false,
                'show_in_menu' => 'admin.php?page=marketpress',
                'supports' => array(
                    'title',
                ),
                'can_export' => true,
                'menu_icon' => 'dashicons-images-alt2',
            )
        );
    }
}

/*
 * Add a meta box
 */
add_action('add_meta_boxes', 'marketpress_slider_metabox', 10, 2);
function marketpress_slider_metabox($post_type, $post)
{

    add_meta_box(
        'marketpress_slider_save',
        'Publish',
        'marketpress_slider_publish_metabox_view',
        'slider',
        'side',
        'low'
    );
}
function marketpress_slider_publish_metabox_view($post)
{
    wp_nonce_field('marketpress', '__nonce');
?>
    <div class="marketpress">
        <div class="order-customer marketpress-clearfix">
            <p>
                <label style="font-weight:bold">Setatus</label><br />
                <select name="slider_status" style="width: 100%">
                    <option value="publish" <?php if ($post->post_status == 'publish') {
                                                echo 'selected="selected"';
                                            } ?>>Publish</option>
                    <option value="draft" <?php if ($post->post_status == 'draft') {
                                                echo 'selected="selected"';
                                            } ?>>Draft</option>
                </select>
            </p>
            <p>
                <button type="submit" style="width: 100%;" class="button button-primary button-hero">Simpan
                    Perubahan</button>
            </p>
        </div>
    </div>
    <?php
}

/*
 * Save Meta Box data
 */
add_action('save_post', 'marketpress_slider_metabox_save');
function marketpress_slider_metabox_save($post_id)
{

    global $wpdb;

    if (!isset($_POST['__nonce'])) {
        return $post_id;
    }

    if (!wp_verify_nonce($_POST['__nonce'], 'marketpress')) {
        return $post_id;
    }

    if (isset($_POST['slider_status'])) {

        $wpdb->update($wpdb->posts, array('post_status' => sanitize_text_field($_POST['slider_status'])), array('ID' => $post_id));

        clean_post_cache($post_id);

        delete_option('marketpress_slider');
    }
}

add_action('delete_post', 'marketpress_on_delete_slider', 99);
add_action('trashed_post', 'marketpress_on_delete_slider', 99);

function marketpress_on_delete_slider($pid)
{

    delete_option('marketpress_slider');
}

add_filter('manage_slider_posts_columns', 'marketpress_slider_column');
add_action('manage_slider_posts_custom_column', 'marketpress_slider_content_column', 10, 2);

function marketpress_slider_column($columns)
{

    $new_columns['cb'] = '<input type="checkbox"/>';
    $new_columns['slider_image'] = __('Image', 'marketpress');
    $new_columns['slider_title'] = __('Title', 'marketpress');
    $new_columns['slider_status'] = __('Status', 'marketpress');
    $new_columns['slider_action'] = __('&nbsp;', 'marketpress');

    return $new_columns;
}

function marketpress_slider_content_column($column, $post_id)
{

    switch ($column):

        case 'slider_image':
            echo '<img style="width: 200px; height: auto;" src="' . get_post_meta($post_id, 'slider_image_url',  true) . '"/>';
            break;

        case 'slider_title':
            echo '<span>' . get_the_title($post_id) . '</span> ';
            break;

        case 'slider_status':
            $statuse = get_post_status($post_id);

            echo '<span>' . $statuse . '</span>';
            break;

        case 'slider_action':
            echo '<div style="text-align:right">';
            echo '<a href="' . get_edit_post_link($post_id) . '" class="button">Edit</a>&nbsp';
            echo '<a href="' . get_delete_post_link($post_id) . '" class="button">Delete</a>';
            echo '</div>';
            break;

    endswitch;
}



add_action('admin_footer', 'marketpress_slider_admin_footer');
function marketpress_slider_admin_footer()
{
    $current_screen = get_current_screen();
    if ($current_screen->parent_file == 'edit.php?post_type=marketpress-slider') :
    ?>
        <script>
            jQuery('.slider_image .row-actions').hide();
        </script>
<?php
    endif;
}


add_action('admin_menu', function () {
    remove_meta_box('submitdiv', 'slider', 'side');
});
