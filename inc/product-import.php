<?php

add_action('admin_menu',   'masrketpress_product_import_menu');
function masrketpress_product_import_menu()
{
    add_submenu_page(
        'edit.php?post_type=marketpress-product',
        __('Product Import', 'marketpress'),
        __('Product Import', 'marketpress'),
        'manage_options',
        'marketpress-product-import',
        'marketpress_product_import_page'
    );
}
/**
 * product import
 */

function marketpress_product_import_page()
{

    ob_start();
?>
<div class="wrap">
    <h1 class="wp-heading-inline"><?php echo esc_html(__('Product Import', 'marketpress')); ?></h1>
    <div style="">
        <p>* Untuk menghindari terjadinya error dan kesalahan yang di sebabkan oleh resource server, kami
            merekomendasikan data import tidak lebih dari 50 row dalam 1 atau sekali file import.</p>
        <p>
            <?php
                $demo_csv_url = get_template_directory_uri() . '/data/demo-import-data.csv';
                ?>
            * Download contoh file csv <a href="<?php echo $demo_csv_url; ?>" target="_blank">di sini</a><br />
        </p>
        <p>
            Cara Edit Data Import CSV:<br />
            1. Download file csv kemudian buka file csv menggunakan microsoft excel<br />
            2. Blok seluruh data dalam file csv copy dengan menekan tombol ctrl + C<br />
            3. Buka google spreadsheet <a href="https://docs.google.com/spreadsheets/u/0/" target="_blank">Di Sini</a>,
            buat spreadsheet baru kemduian paste data yang telah tercopy ke dalam google spreadhseet dengan menekan
            tombol ctrl + V<br />
            4. Blok kolom label A hingga Q, klik kanan pilih menu "Ubah Ukuran Kolom" pilih paskan sesuai data Klik
            Tombol Oke.<br />
            5. Lakukan editing, ubah atau tambah data produk sesuai ketentuan atau data contoh.<br />
            6. Setelah selesai, pilih menu File pada barisan menu bagian kiri atas, Pilih sub menu Download, Klik format
            csv atau Nilai yang di pisahkan Koma<br />
            7. Upload file di bawah ini.
        </p>
        <p style="color: red">
            * kesalahan dalam melakukan editing dapat menyebabkan kegagalan proses import<br />
            * Hindari menggunakan tanda baca koma saat melakukan input data, karena dapat menyebabkan data tidak
            beraturan.
        </p>
    </div>
    <form name="post" method="post" id="quick-press" class="initial-form hide-if-no-js ng-dirty"
        enctype="multipart/form-data">
        <div class="input-text-wrap" id="title-wrap">
            <input type="file" name="csv" required>
        </div>
        <p class="submit">
            <input type="hidden" name="_wpnonce" value="<?php echo wp_create_nonce('marketpress_nonce'); ?>">
            <button type="submit" class="button button-primary">Import Sekarang</button>
            <br class="clear">
        </p>
    </form>
    <?php
        marketpress_product_import_action();
        ?>
</div>
<?php
    $html = ob_get_contents();
    ob_end_clean();
    echo $html;
}

function marketpress_product_import_action()
{

    $post = isset($_POST) ? $_POST : array();

    if (isset($post['_wpnonce']) && wp_verify_nonce($post['_wpnonce'], 'marketpress_nonce')) {
        set_time_limit(0);

        $csv = $_FILES['csv'];
        if ($csv && isset($csv['tmp_name'])) {
            $file = fopen($csv['tmp_name'], 'r');
            $data = array();
            while (($row = fgetcsv($file, 0, ",")) !== FALSE) :
                $data[] = $row;
            endwhile;
            fclose($file);

            foreach ((array) $data as $key => $val) :
                if ($key == 0) continue;

                $c = count($val);

                if ($c < 12) continue;

                $result = marketpress_create_product($val);
                if ($result == 'error') {
                    echo '<br/>Error Import<br/>';
                } else {
                    $l = get_edit_post_link($result);
                    echo '<a href="' . $l . '" taget="_blankk">Success product ID ' . $result . '</a><br/>';
                }
            endforeach;
        }
    }
}

function marketpress_create_product($data)
{

    $d = array(
        'title'                 => isset($data[0]) ? sanitize_text_field($data[0]) : '',
        'thumbnail'             => isset($data[1]) ? sanitize_text_field($data[1]) : '',
        'excerpt'               => isset($data[2]) ? sanitize_text_field($data[2]) : '',
        'cta'                   => isset($data[3]) ? sanitize_text_field($data[3]) : '',
        'price'                 => isset($data[4]) ? intval($data[4]) : '',
        'promo_price'                   => isset($data[5]) ? intval($data[5]) : '',
        'discount'                      => isset($data[6]) ? sanitize_text_field($data[6]) : '',
        'salespage_affiliate_link'      => isset($data[7]) ? sanitize_text_field($data[7]) : '',
        'salespage_affiliate_link_open' => isset($data[8]) ? sanitize_text_field($data[8]) : '',
        'checkout_affiliate_link'       => isset($data[9]) ? sanitize_text_field($data[9]) : '',
        'seller_contact_link'           => isset($data[10]) ? sanitize_text_field($data[10]) : '',
        'categories'                    => isset($data[11]) ? sanitize_text_field($data[11]) : '',
    );

    $categories = explode('*', $d['categories']);

    $post_data = array(
        'post_title'   => $d['title'],
        'post_name'    => sanitize_title($d['title']),
        'post_content' => '',
        'post_status'  => 'publish',
        'post_excerpt' => $d['excerpt'],
        //'post_author'  => 1,
        'post_type'    => 'marketpress-product',
        // 'tax-input' => array(
        //     'product-category' => $categories,
        // ),
    );
    $post_id = wp_insert_post($post_data);

    if (is_wp_error($post_id)) {
        return 'error';
    } else {

        update_post_meta($post_id, 'call_to_action', $d['cta']);
        update_post_meta($post_id, 'price', intval($d['price']));
        update_post_meta($post_id, 'promo_price', intval($d['promo_price']));

        update_post_meta($post_id, 'discount', $d['dicsount']);
        update_post_meta($post_id, 'salespage_affiliate_link', $d['salespage_affiliate_link']);

        update_post_meta($post_id, 'salespage_affiliate_link_open', $d['salespage_affiliate_link_open']);
        update_post_meta($post_id, 'checkout_affiliate_link', $d['checkout_affiliate_link']);
        update_post_meta($post_id, 'seller_contact_link', $d['seller_contact_link']);

        marketpress_upload_product_thumbnail($d['thumbnail'], $post_id);

        $term_ids = array();

        foreach ((array)$categories as $key => $val) {
            $term = term_exists($val, 'marketpress-product-category');
            if (!$term) {
                $term = wp_insert_term($val, 'marketpress-product-category');
            }
            $term_ids[] = $term['term_id'];
        }


        if ($term_ids) {
            wp_set_post_terms($post_id, $term_ids, 'marketpress-product-category');
        }

        return $post_id;
    }
}

function marketpress_upload_product_thumbnail($url, $post_id)
{
    $image = '';
    if ($url != '') {

        $file = array();
        $file['name'] = $url;
        $file['tmp_name'] = download_url($url);

        if (is_wp_error($file['tmp_name'])) {
            @unlink($file['tmp_name']);
            //var_dump( $file['tmp_name']->get_error_messages( ) );
        } else {
            $attachmentId = media_handle_sideload($file, $post_id);

            if (is_wp_error($attachmentId)) {
                @unlink($file['tmp_name']);
                //var_dump( $attachmentId->get_error_messages( ) );
            } else {
                $image = wp_get_attachment_url($attachmentId);
                set_post_thumbnail($post_id, $attachmentId);
                @unlink($file['tmp_name']);
            }
        }
    }
    return $image;
}

add_action('wp_ajax_export_products', 'marketpress_ajax_export_products');
/**
 * ajax export product
 * @return [type] [description]
 */
function marketpress_ajax_export_products()
{
    $nonce = isset($_REQUEST['nonce']) ? $_REQUEST['nonce'] : '';
    if (!wp_verify_nonce($nonce, 'marketpress')) exit;

    $args = array(
        'post_type' => 'marketpress-product',
        'posts_per_page' => -1,
        'fields' => 'ids',
    );

    $posts = new WP_Query($args);

    $list = array();

    $list[] = array(
        'Nama Produk',
        'Gambar Produk',
        'Keterangan',
        'Call To Action (button or form)',
        'Harga Coret',
        'Harga',
        'Diskon',
        'Salespage Affiliate Link',
        'Salespage Affiliate Link open with (iframe or redirect)',
        'Checkout Affiliate Link',
        'Seller Contact Link',
        'Category',
    );

    foreach ((array)$posts->posts as $id) :

        $post = get_post($id);

        if (!$post) continue;

        $thumbnail_id = get_post_thumbnail_id($post->ID);

        $excerpt =  '';
        if (has_excerpt($post->ID)) {
            $excerpt = get_the_excerpt($post->ID);
        }

        $terms = get_the_terms($post->ID, 'marketpress-product-category');

        $categories = array();
        foreach ((array)$terms as $t) {
            $categories[] = $t->name;
        }

        $cta = get_post_meta($post->ID, 'call_to_action', true);
        if (empty($cta)) {
            $cta = 'button';
        }

        $open = get_post_meta($post->ID, 'salespage_affiliate_link_open', true);
        if (empty($open)) {
            $open = 'redirect';
        }

        $list[] = array(
            strip_tags($post->post_title),
            wp_get_attachment_url($thumbnail_id, 'full'),
            strip_tags($excerpt),
            $cta,
            get_post_meta($post->ID, 'price', true),
            get_post_meta($post->ID, 'promo_price', true),
            get_post_meta($post->ID, 'discount', true),
            get_post_meta($post->ID, 'salespage_affiliate_link', true),
            $open,
            get_post_meta($post->ID, 'checkout_affiliate_link', true),
            get_post_meta($post->ID, 'seller_contact_link', true),
            implode(',', $categories),
        );
    endforeach;

    $filename = 'marketpress-product-export-' . date('y-m-d-H-i-s') . '.csv';

    $file = fopen(WP_CONTENT_DIR . '/uploads/' . $filename, 'w');

    foreach ($list as $line) {
        fputcsv($file, $line);
    }

    fclose($file);

    echo WP_CONTENT_URL . '/uploads/' . $filename;
    exit;
}