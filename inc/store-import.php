<?php

add_action('admin_menu',   'masrketpress_store_import_menu');
function masrketpress_store_import_menu()
{
    add_submenu_page(
        'edit.php?post_type=marketpress-store',
        __('Shop Import', 'marketpress'),
        __('Shop Import', 'marketpress'),
        'manage_options',
        'marketpress-store-import',
        'marketpress_store_import_page'
    );
}
/**
 * product import
 */

function marketpress_store_import_page()
{

    ob_start();
?>
<div class="wrap">
    <h1 class="wp-heading-inline"><?php echo esc_html(__('Shop Import', 'marketpress')); ?></h1>
    <div style="">
        <p>* Untuk menghindari terjadinya error dan kesalahan yang di sebabkan oleh resource server, kami
            merekomendasikan data import tidak lebih dari 50 row dalam 1 atau sekali file import.</p>
        <p>
            <?php
                $demo_csv_url = get_template_directory_uri() . '/data/demo-import-store-data.csv';
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
        marketpress_store_import_action();
        ?>
</div>
<?php
    $html = ob_get_contents();
    ob_end_clean();
    echo $html;
}

function marketpress_store_import_action()
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

                if ($c < 10) continue;

                $result = marketpress_create_store($val);
                if ($result == 'error') {
                    echo '<br/>Error Import<br/>';
                } else {
                    $l = get_edit_post_link($result);
                    echo '<a href="' . $l . '" taget="_blankk">Success Product store ID ' . $result . '</a><br/>';
                }
            endforeach;
        }
    }
}

function marketpress_create_store($data)
{

    $d = array(
        'title'                 => isset($data[0]) ? sanitize_text_field($data[0]) : '',
        'thumbnails'             => isset($data[1]) ? explode('*', $data[1]) : array(),
        'content'               => isset($data[2]) ? sanitize_text_field($data[2]) : '',
        'price'                 => isset($data[3]) ? intval($data[3]) : '',
        'promo_price'                   => isset($data[4]) ? intval($data[4]) : '',
        'discount'                      => isset($data[5]) ? intval($data[5]) : '',
        'variant1_label'      => isset($data[6]) ? sanitize_text_field($data[6]) : '',
        'variant1_option' => isset($data[7]) ? explode('*', $data[7]) : '',
        'variant2_label'       => isset($data[8]) ? sanitize_text_field($data[8]) : '',
        'variant2_option'           => isset($data[9]) ? explode('*', $data[9]) : array(),
        'categories'                    => isset($data[10]) ? explode('*', $data[10]) : array(),
    );

    $post_data = array(
        'post_title'   => $d['title'],
        'post_name'    => sanitize_title($d['title']),
        'post_content' => $d['content'],
        'post_status'  => 'publish',
        //'post_author'  => 1,
        'post_type'    => 'marketpress-store',
        // 'tax-input' => array(
        //     'product-category' => $categories,
        // ),
    );
    $post_id = wp_insert_post($post_data);

    if (is_wp_error($post_id)) {
        return 'error';
    } else {

        update_post_meta($post_id, 'price', intval($d['price']));
        update_post_meta($post_id, 'promo_price', intval($d['promo_price']));

        update_post_meta($post_id, 'discount', $d['discount']);
        update_post_meta($post_id, 'variation1_label', $d['variant1_label']);
        update_post_meta($post_id, 'variation1_option', $d['variant1_option']);

        update_post_meta($post_id, 'variation2_label', $d['variant2_label']);

        $var2 = array();
        foreach ((array)$d['variant2_option'] as $key => $val) {
            $vv = explode(':', $val);
            $var2[] = array(
                'text' => $vv[0],
                'price' => isset($vv[1]) ? intval($vv[1]) : 0
            );
        }
        update_post_meta($post_id, 'variation2_option', $var2);

        $galeries = array();

        foreach ((array)$d['thumbnails'] as $key => $val) {
            $attachmentId = marketpress_upload_store_thumbnail($val, $post_id);

            if ($key == 0) {
                set_post_thumbnail($post_id, $attachmentId);
            } else {
                $galeries[$attachmentId] = wp_get_attachment_url($attachmentId);
            }
        }

        update_post_meta($post_id, 'store_images', $galeries);

        $term_ids = array();

        foreach ((array)$d['categories'] as $key => $val) {
            $term = term_exists($val, 'marketpress-store-category');
            if (!$term) {
                $term = wp_insert_term($val, 'marketpress-store-category');
            }
            $term_ids[] = $term['term_id'];
        }


        if ($term_ids) {
            wp_set_post_terms($post_id, $term_ids, 'marketpress-store-category');
        }

        return $post_id;
    }
}

function marketpress_upload_store_thumbnail($url, $post_id)
{
    $attachmentId = '';
    if ($url != '') {

        $file = array();
        $file['name'] = $url;
        $file['tmp_name'] = download_url($url);

        if (is_wp_error($file['tmp_name'])) {
            @unlink($file['tmp_name']);
        } else {
            $attachmentId = media_handle_sideload($file, $post_id);

            @unlink($file['tmp_name']);
        }
    }
    return $attachmentId;
}