<?php

add_action('marketpress_ecommerce_settings_option_page_admin-setting_flashsale', 'marketpress_order_options_page_flashsale');
function marketpress_order_options_page_flashsale()
{
    ob_start();
?>
<table>
    <tr>
        <th scope="row">
            <label><?php _e('Enable Flashsale', 'marketpress'); ?></label>
        </th>
        <td>
            <input name="mes_flashsale_enable" type="hidden" value="no" />
            <input name="mes_flashsale_enable" type="checkbox" value="yes"
                <?php echo ('yes' == get_option('mes_flashsale_enable')) ? 'checked="chekced"' : ''; ?> />
            <?php echo __('Check this if enable') ?>
        </td>
    </tr>
    <tr>
        <th scope="row">
            <label><?php _e('Date Start', 'marketpress'); ?></label>
        </th>
        <td>
            <input type="text" class="regular-text marketpress-field__datetimepicker" name="mes_flashsale_date_start"
                value="<?php echo get_option('mes_flashsale_date_start', ''); ?>" />
        </td>
    </tr>
    <tr>
        <th scope="row">
            <label><?php _e('Date End', 'marketpress'); ?></label>
        </th>
        <td>
            <input type="text" class="regular-text marketpress-field__datetimepicker" name="mes_flashsale_date_end"
                value="<?php echo get_option('mes_flashsale_date_end', ''); ?>" />
        </td>
    </tr>
    <tr>
        <th scope="row">
            <label><?php _e('Flashsale End Message', 'marketpress'); ?></label>
        </th>
        <td>
            <?php
                $args = array(
                    'textarea_rows' => 15
                );
                wp_editor(get_option('mes_flashsale_end_message', 'Flashsale telah berakhir, nantikan flashsale berikutnya'), 'mes_flashsale_end_message', $args);
                ?>
        </td>
    </tr>
    <tr>
        <th scope="row">
        </th>
        <td>
            <button class="button button-primary" type="submit"
                name="submit"><?php echo __('Save Settings', 'marketpress'); ?></button>
        </td>
    </tr>
</table>
<?php
    $content = ob_get_contents();
    ob_end_clean();

    echo $content;
}


add_action('marketpress_ecommerce_settings_option_page_admin-setting_general', 'marketpress_order_options_page_general');
function marketpress_order_options_page_general()
{

    $lists = get_option('mes_order_admin_phones');
    $new_lists = array();
    foreach ((array)$lists as $key => $val) {
        if (isset($val['number']) && $val['number']) {
            $new_lists[] = $val;
        }
    }
    ob_start();
?>
<table>
    <tr>
        <th scope="row">
            <label><?php _e('Enable Direct Thanks You Page?', 'marketpress'); ?></label>
        </th>
        <td>
            <input name="mes_thanks_enable" type="hidden" value="no" />
            <input name="mes_thanks_enable" type="checkbox" value="yes"
                <?php echo ('yes' == get_option('mes_thanks_enable', 'yes')) ? 'checked="chekced"' : ''; ?> />
            <?php echo __('Check this if enable') ?>
        </td>
    </tr>
    <tr>
        <th scope="row">
            <label><?php _e('Admin Phones', 'marketpress'); ?></label>
        </th>
        <td>
            <?php
                $phones = get_option('mes_order_admin_phones');
                if (is_array($phones)) {
                    $ph = [];
                    foreach ((array)$phones as $p) {
                        $ph[] = $p['number'];
                    }

                    $phones = implode(',', $ph);
                }
                ?>
            <textarea class="regular-text" name="mes_order_admin_phones"
                style="min-height: 100px;"><?php echo $phones; ?></textarea>
            <div class="marketpress-ecommerce-setting-desc">
                Masukan nomor whatsapp misal 6281313045656, "pisahkan dengan koma jika punya banyak cs"
            </div>
        </td>
    </tr>
    <tr>
        <th scope="row">
            <label><?php _e('Invoice Format', 'marketpress'); ?></label>
        </th>
        <td>
            <input type="text" class="regular-text" name="mes_order_invoice_format"
                value="<?php echo get_option('mes_order_invoice_format', 'INV/{year}/{month}/{date}/{number}'); ?>" />
            <div class="marketpress-ecommerce-settings-desc">
                {year} -&gt; Replaced with order year
                <hr>{month} -> Replaced with order month
                <hr>{date} -> Replaced with order date
                <hr>{number} -> Replaced with order number
                <hr>
            </div>
        </td>
    </tr>
    <tr>
        <th scope="row">
            <label><?php _e('Confirmation Instruction', 'marketpress'); ?></label>
        </th>
        <td>
            <textarea class="regular-text" name="mes_order_confirmation_instruction"
                style="min-height: 100px;"><?php echo get_option('mes_order_confirmation_instruction', 'Silahkan melakukan pembayaran sesuai dengan nominal dan methode pembayaran yang Anda pilih, Jika sudah jangan lupa klik konfirmasi'); ?></textarea>
        </td>
    </tr>
    <tr>
        <th scope="row">
            <label><?php _e('"Belanja Sekarang" button link"', 'marketpress'); ?></label>
        </th>
        <td>
            <input type="text" class="regular-text" name="mes_shop_now_link"
                value="<?php echo get_option('mes_shop_now_link', ''); ?>" />
        </td>
    </tr>
    <tr>
        <th scope="row">
            <label><?php _e('Buy Button text', 'marketpress'); ?></label>
        </th>
        <td>
            <input type="text" class="regular-text" name="mes_shop_buy_now"
                value="<?php echo get_option('mes_shop_buy_now', 'Beli sekarang'); ?>" />
        </td>
    </tr>
    <tr>
        <th scope="row">
        </th>
        <td>
            <button class="button button-primary" type="submit"
                name="submit"><?php echo __('Save Settings', 'marketpress'); ?></button>
        </td>
    </tr>

</table>
<?php
    $content = ob_get_contents();
    ob_end_clean();

    echo $content;
}