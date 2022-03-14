<?php

/**
 * MarketPress product function
 * @package marketpress/inc
 */

add_action('init', 'marketpress_product', 99);
/**
 * register product post type
 * @return [type] [description]
 */
function marketpress_product()
{
    $lic = new marketpress_License();
    if ($lic->license == 'valid') {

        $icon = '<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" aria-hidden="true" role="img" width="24" height="24" preserveAspectRatio="xMidYMid meet" viewBox="0 0 20 20"><path d="M14.01 4v6h2V2H4v8h2.01V4h8zm-2 2v6h3l-5 6l-5-6h3V6h4z" fill="white"/></svg>
';
        register_post_type(
            'marketpress-product',
            array(
                'labels' => array(
                    'name'               => __('Product', 'marketpress'),
                    'singular_name'      => __('Product', 'marketpress'),
                    'add_new'            => __('Add New', 'marketpress'),
                    'add_new_item'       => __('Add New Product', 'marketpress'),
                    'edit'               => __('Edit', 'marketpress'),
                    'edit_item'          => __('Edit Product', 'marketpress'),
                    'new_item'           => __('New Product', 'marketpress'),
                    'view'               => __('View Product', 'marketpress'),
                    'view_item'          => __('View Product', 'marketpress'),
                    'search_items'       => __('Search Product', 'marketpress'),
                    'not_found'          => __('No Products found', 'marketpress'),
                    'not_found_in_trash' => __('No Products found in Trash', 'marketpress')
                ),
                'public' => true,
                'hierarchical' => false,
                //'show_in_menu' => 'admin.php?page=marketpress',
                'has_archive' => true,
                'supports' => array(
                    'title',
                    'editor',
                    'thumbnail',
                    'excerpt'
                ),
                'can_export' => true,
                'menu_icon' => 'data:image/svg+xml;base64,' . base64_encode($icon),
                'rewrite' =>  array(
                    'slug' => 'product',
                    'width_front' => false
                ),
            )
        );
    }
}

add_action('init', 'marketpress_product_category_taxonomy', 0);
/**
 * register product category taxonomy
 * @return [type] [description]
 */
function marketpress_product_category_taxonomy()
{
    $labels = array(
        'name'              => _x('Categories', 'marketpress'),
        'singular_name'     => _x('Category', 'marketpress'),
        'search_items'      => __('Search Categories', 'marketpress'),
        'all_items'         => __('All Categories', 'marketpress'),
        'parent_item'       => __('Parent Category', 'marketpress'),
        'parent_item_colon' => __('Parent Category:', 'marketpress'),
        'edit_item'         => __('Edit Category', 'marketpress'),
        'update_item'       => __('Update Category', 'marketpress'),
        'add_new_item'      => __('Add New Category', 'marketpress'),
        'new_item_name'     => __('New Category Name', 'marketpress'),
        'menu_name'         => __('Categories', 'marketpress'),
    );

    register_taxonomy(
        'marketpress-product-category',
        array('marketpress-product'),
        array(
            'hierarchical'      => true,
            'labels'            => $labels,
            'show_ui'           => true,
            'show_admin_column' => true,
            'query_var'         => true,
            'rewrite'           => array('slug' => 'product-category'),
        )
    );
}


add_filter('manage_marketpress-product_posts_columns', 'marketpress_product_column');
/**
 * register product custom column
 * @param  [type] $columns [description]
 * @return [type]          [description]
 */
function marketpress_product_column($columns)
{

    $columns = array_slice($columns, 0, 1, true) + array('product_thumb' => 'Image') + array_slice($columns, 1, count($columns) - 1, true);

    $columns = array_slice($columns, 0, 3, true) + array('product_price' => 'Price') + array_slice($columns, 1, count($columns) - 1, true);

    return $columns;
}

add_action('manage_marketpress-product_posts_custom_column', 'marketpress_product_content_column', 10, 2);
/**
 * product custom column value
 * @param  [type] $column  [description]
 * @param  [type] $post_id [description]
 * @return [type]          [description]
 */
function marketpress_product_content_column($column, $post_id)
{

    $product_images = array();

    if (get_post_thumbnail_id($post_id)) {
        $product_images[] = get_post_thumbnail_id($post_id);
    }

    if (get_post_meta($post_id, 'product_images', true)) {
        $galeries = get_post_meta($post_id, 'product_images', true);
        $galery_ids = array_keys($galeries);
        $product_images = array_merge($product_images, $galery_ids);
    }

    $thumb_url = '';

    if ($product_images) {
        $thumb_url = wp_get_attachment_url($product_images[0], 'full');
    }

    switch ($column):

        case 'product_thumb':
            echo '<img width="100" height="100" src="' . $thumb_url . '" alt="">';
            break;

        case 'product_price':
            $price = intval(get_post_meta($post_id, 'price', true));
            $promo_price = intval(get_post_meta($post_id, 'promo_price', true));
            if ($promo_price) {
                echo '<del>' . number_format($price, 0, ',', '.') . '</del><br/>';
                echo 'Promo ' . number_format($promo_price, 0, ',', '.');
            } else {
                echo number_format($price, 0, ',', '.') . '<br/>';
            }
            break;
    endswitch;
}

add_action('admin_footer', 'marketpress_product_admin_footer');
/**
 * admin product footer script
 * @return [type] [description]
 */
function marketpress_product_admin_footer()
{
    $current_screen = get_current_screen();

    if ($current_screen->post_type == 'marketpress-product' && empty($current_screen->taxonomy) && !isset($_GET['page']) && !isset($_GET['action'])) :

        $ajax_url = admin_url('admin-ajax.php');
        $nonce = wp_create_nonce('marketpress');
        $status = isset($_GET['post_status']) ? $_GET['post_status'] : 'all';

        $import_link = admin_url('admin.php?page=marketpress-product-import');
        $import = '<a href="' . $import_link . '" class="add-new-h2"> Import From CSV </a>';
?>
        <script>
            jQuery(jQuery(".wrap h1")[0]).append("<a  id='export-csv' class='add-new-h2'>Export To CSV</a>");
            jQuery(jQuery(".wrap h1")[0]).append('<?php echo $import; ?>');
            jQuery('#export-csv').on('click', function() {
                jQuery(this).html('Proses..');

                jQuery.ajax({
                    type: "POST",
                    url: '<?php echo $ajax_url; ?>',
                    data: {
                        action: 'export_products',
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
    if (isset($current_screen->taxonomy) && $current_screen->taxonomy == 'product-category') :
    ?>
        <script>
            //jQuery('.row-actions .view').hide();
        </script>
<?php
    endif;
}
