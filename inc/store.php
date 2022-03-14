<?php

/**
 * MarketPress Store function
 * @package marketpress/inc
 */

add_action('init', 'marketpress_store', 99);
/**
 * register Store post type
 * @return [type] [description]
 */
function marketpress_store()
{
    $lic = new marketpress_License();
    if ($lic->license == 'valid') {
        $icon = '<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" aria-hidden="true" role="img" width="24" height="24" preserveAspectRatio="xMidYMid meet" viewBox="0 0 20 20"><path d="M6 13h9c.55 0 1 .45 1 1s-.45 1-1 1H5c-.55 0-1-.45-1-1V4H2c-.55 0-1-.45-1-1s.45-1 1-1h3c.55 0 1 .45 1 1v2h13l-4 7H6v1zm-.5 3c.83 0 1.5.67 1.5 1.5S6.33 19 5.5 19S4 18.33 4 17.5S4.67 16 5.5 16zm9 0c.83 0 1.5.67 1.5 1.5s-.67 1.5-1.5 1.5s-1.5-.67-1.5-1.5s.67-1.5 1.5-1.5z" fill="white"/></svg>';

        register_post_type(
            'marketpress-store',
            array(
                'labels' => array(
                    'name'               => __('Shop', 'marketpress'),
                    'singular_name'      => __('Shop', 'marketpress'),
                    'add_new'            => __('Add New', 'marketpress'),
                    'add_new_item'       => __('Add New Shop', 'marketpress'),
                    'edit'               => __('Edit', 'marketpress'),
                    'edit_item'          => __('Edit Shop', 'marketpress'),
                    'new_item'           => __('New Shop', 'marketpress'),
                    'view'               => __('View Shop', 'marketpress'),
                    'view_item'          => __('View Shop', 'marketpress'),
                    'search_items'       => __('Search Shop', 'marketpress'),
                    'not_found'          => __('No Shop found', 'marketpress'),
                    'not_found_in_trash' => __('No Shop found in Trash', 'marketpress')
                ),
                'public' => true,
                'hierarchical' => false,
                //'show_in_menu' => 'admin.php?page=marketpress',
                'has_archive' => true,
                'supports' => array(
                    'title',
                    'editor',
                    'thumbnail'
                ),
                'can_export' => true,
                'menu_icon' =>
                'data:image/svg+xml;base64,' . base64_encode($icon),
                'rewrite' =>  array(
                    'slug' => 'shop',
                    'width_front' => false
                ),
            )
        );
    }
}

add_action('init', 'marketpress_store_category_taxonomy', 0);
/**
 * register Store category taxonomy
 * @return [type] [description]
 */
function marketpress_store_category_taxonomy()
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
        'marketpress-store-category',
        array('marketpress-store'),
        array(
            'hierarchical'      => true,
            'labels'            => $labels,
            'show_ui'           => true,
            'show_admin_column' => true,
            'query_var'         => true,
            'rewrite'           => array('slug' => 'shop-category'),
        )
    );
}


add_filter('manage_marketpress-store_posts_columns', 'marketpress_store_column');

/**
 * register Store custom column
 * @param  [type] $columns [description]
 * @return [type]          [description]
 */
function marketpress_store_column($columns)
{

    $columns = array_slice($columns, 0, 1, true) + array('store_thumb' => 'Image') + array_slice($columns, 1, count($columns) - 1, true);

    $columns = array_slice($columns, 0, 3, true) + array('store_price' => 'Price') + array_slice($columns, 1, count($columns) - 1, true);

    $columns['store_label'] = 'Label';

    return $columns;
}

add_action('manage_marketpress-store_posts_custom_column', 'marketpress_store_content_column', 10, 2);
/**
 * Store custom column value
 * @param  [type] $column  [description]
 * @param  [type] $post_id [description]
 * @return [type]          [description]
 */
function marketpress_store_content_column($column, $post_id)
{

    $store_images = array();

    if (get_post_thumbnail_id($post_id)) {
        $store_images[] = get_post_thumbnail_id($post_id);
    }

    if (get_post_meta($post_id, 'store_images', true)) {
        $galeries = get_post_meta($post_id, 'store_images', true);
        $galery_ids = array_keys($galeries);
        $store_images = array_merge($store_images, $galery_ids);
    }

    $thumb_url = '';

    if ($store_images) {
        $thumb_url = wp_get_attachment_url($store_images[0], 'full');
    }

    switch ($column):

        case 'store_thumb':
            echo '<img width="100" height="100" src="' . $thumb_url . '" alt="">';
            break;

        case 'store_price':
            $price = intval(get_post_meta($post_id, 'price', true));
            $promo_price = intval(get_post_meta($post_id, 'promo_price', true));
            if ($promo_price) {
                echo '<del>' . number_format($price, 0, ',', '.') . '</del><br/>';
                echo 'Promo ' . number_format($promo_price, 0, ',', '.');
            } else {
                echo number_format($price, 0, ',', '.') . '<br/>';
            }
            break;
        case 'store_label':
            $label = get_post_meta($post_id, 'label', true);
            if (empty($label)) {
                echo 'Default';
            } else {
                echo ucfirst($label);
            }
    endswitch;
}

add_action('admin_footer', 'marketpress_store_admin_footer');
/**
 * admin Store footer script
 * @return [type] [description]
 */
function marketpress_store_admin_footer()
{
    $current_screen = get_current_screen();

    if ($current_screen->post_type == 'marketpress-store' && empty($current_screen->taxonomy) && !isset($_GET['page']) && !isset($_GET['action'])) :

        $ajax_url = admin_url('admin-ajax.php');
        $nonce = wp_create_nonce('marketpress');
        $status = isset($_GET['post_status']) ? $_GET['post_status'] : 'all';

        $import_link = admin_url('admin.php?page=marketpress-store-import');
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
                        action: 'export_stores',
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
    if (isset($current_screen->taxonomy) && $current_screen->taxonomy == 'Store-category') :
    ?>
        <script>
            //jQuery('.row-actions .view').hide();
        </script>
<?php
    endif;
}

add_action('wp_ajax_export_stores', 'marketpress_ajax_export_stores');
/**
 * ajax export product
 * @return [type] [description]
 */
function marketpress_ajax_export_stores()
{
    $nonce = isset($_REQUEST['nonce']) ? $_REQUEST['nonce'] : '';
    if (!wp_verify_nonce($nonce, 'marketpress')) exit;

    $args = array(
        'post_type' => 'marketpress-store',
        'posts_per_page' => -1,
        'fields' => 'ids',
    );

    $posts = new WP_Query($args);

    $list = array();

    $list[] = array(
        'Nama Produk',
        'Gambar Produk',
        'description',
        'Harga Coret',
        'Harga',
        'Diskon',
        'Varian 1 Label',
        'Varian 1 Value',
        'Varian 2 Label',
        'Varian 2 Value',
        'Category',
    );

    foreach ((array)$posts->posts as $id) :

        $post = get_post($id);

        if (!$post) continue;

        $product_images = array();

        if (get_post_thumbnail_id($post->ID)) {
            $product_images[] = get_post_thumbnail_id($post->ID);
        }

        if (get_post_meta($post->ID, 'store_images', true)) {
            $galeries = get_post_meta($post->ID, 'store_images', true);
            $galery_ids = array_keys($galeries);
            $product_images = array_merge($product_images, $galery_ids);
        }

        $images = array();
        foreach ($product_images as $key => $val) {
            $images[] = wp_get_attachment_url($val, 'full');
        }


        $terms = get_the_terms($post->ID, 'marketpress-store-category');

        $categories = array();
        foreach ((array)$terms as $t) {
            $categories[] = $t->name;
        }

        $variation1 = get_post_meta($post->ID, 'variation1_option', true);

        $get_variation2 = get_post_meta($post->ID, 'variation2_option', true);
        $variation2 = array();

        foreach ((array) $get_variation2 as $k => $v) {

            $variation2[] = $v['text'] . ':' . intval($v['price']);
        }


        $list[] = array(
            strip_tags($post->post_title),
            implode('*', $images),
            strip_tags($post->post_content),
            get_post_meta($post->ID, 'price', true),
            get_post_meta($post->ID, 'promo_price', true),
            get_post_meta($post->ID, 'discount', true),
            get_post_meta($post->ID, 'variation1_label', true),
            implode('*', $variation1),
            get_post_meta($post->ID, 'variation2_label', true),
            implode('*', $variation2),
            implode('*', $categories),
        );
    endforeach;

    $filename = 'marketpress-store-export-' . date('y-m-d-H-i-s') . '.csv';

    $file = fopen(WP_CONTENT_DIR . '/uploads/' . $filename, 'w');

    foreach ($list as $line) {
        fputcsv($file, $line);
    }

    fclose($file);

    echo WP_CONTENT_URL . '/uploads/' . $filename;
    exit;
}

add_action('wp_ajax_search_product', 'marketpress_ajax_search_product');
add_action('wp_ajax_nopriv_search_product', 'marketpress_ajax_search_product');
function marketpress_ajax_search_product()
{
    global $wpdb;

    $input = file_get_contents("php://input");
    $respons = array(
        'status' => 'error',
        'message' => 'Tidak di temukan hasil',
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

        $args = array(
            'post_type'      => 'marketpress-store',
            'posts_per_page' => -1,
            'fields'         => 'ids',
            's'              => sanitize_text_field($data['s']),
            'post_status'    => 'publish'
        );

        $products = [];

        $posts = new WP_Query($args);

        foreach ($posts->posts as $post_id) {

            $cats = get_the_terms($post_id, 'marketpress-store-category');
            $category = '';

            foreach ((array)$cats as $c) {
                $category = $c->name;
                break;
            }
            $product_images = array();

            if (get_post_thumbnail_id($post_id)) {
                $product_images[] = get_post_thumbnail_id($post_id);
            }

            if (get_post_meta($post_id, 'store_images', true)) {
                $galeries = get_post_meta($post_id, 'store_images', true);
                $galery_ids = array_keys($galeries);
                $product_images = array_merge($product_images, $galery_ids);
            }

            $product_images_url = array();
            foreach ($product_images as $key => $val) {
                $product_images_url[] = wp_get_attachment_url($val, 'full');
            }

            $products[] = [
                'post_id'       => $post_id,
                'permalink' => get_the_permalink($post_id),
                'thumbnail_url' => isset($product_images_url[0]) ? esc_url($product_images_url[0]) : '',
                'title'         => get_the_title($post_id),
                'category'      => $category,
            ];
        }

        if ($products) {
            $respons = array(
                'status' => 'success',
                'message' => 'Hasil Pencarian',
                'data' => $products
            );
        }


        echo json_encode($respons);
        exit;
    endif;
}
