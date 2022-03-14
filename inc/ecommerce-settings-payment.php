<?php

add_action('marketpress_ecommerce_settings_option_page_payment_general', 'marketpress_payment_options_page_general');
function marketpress_payment_options_page_general()
{
    ob_start();
?>
<table>
    <tr>
        <th scope="row">
            <label><?php _e('Enable Payment?', 'marketpress'); ?></label>
        </th>
        <td>
            <input name="mes_payment_enable" type="hidden" value="no" />
            <input name="mes_payment_enable" type="checkbox" value="yes"
                <?php echo ('yes' == get_option('mes_payment_enable')) ? 'checked="chekced"' : ''; ?> />
            <?php echo __('Check this if enable') ?>
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

add_action('marketpress_ecommerce_settings_option_page_payment_cod', 'marketpress_ecommerce_settings_option_page_payment_cod');
function marketpress_ecommerce_settings_option_page_payment_cod()
{

    ob_start();
?>
<div class="marketpress-cod-payment">
    <table>
        <tr>
            <th scope="row">
                <label><?php _e('Enable COD?', 'marketpress'); ?></label>
            </th>
            <td>
                <input name="mes_payment_cod_enable" type="hidden" value="no" />
                <input name="mes_payment_cod_enable" type="checkbox" value="yes"
                    <?php echo ('yes' == get_option('mes_payment_cod_enable')) ? 'checked="chekced"' : ''; ?> />
                <?php echo __('Check this if enable') ?>
            </td>
        </tr>
        <tr>
            <th scope="row">
                <label><?php _e('Payment Cost', 'marketpress'); ?></label>
            </th>
            <td>
                <input type="text" class="small-text" name="mes_payment_cod_cost"
                    value="<?php echo get_option('mes_payment_cod_cost', '10'); ?>" />%
            </td>
        </tr>
        <tr>
            <th scope="row">
                <label><?php _e('Payment Label', 'marketpress'); ?></label>
            </th>
            <td>
                <input type="text" class="regular-text" name="mes_payment_cod_label"
                    value="<?php echo get_option('mes_payment_cod_label', 'COD (Bayar ditempat)'); ?>" />
            </td>
        </tr>
        <tr>
            <th scope="row">
                <label><?php _e('Payment Instruction', 'marketpress'); ?></label>
            </th>
            <td>
                <textarea class="regular-text"
                    name="mes_payment_cod_instruction"><?php echo get_option('mes_payment_cod_instruction', 'Metode pembayaran di tempat di kenakan biaya admin sebesar Rp 10.000'); ?></textarea>
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
</div>
<?php
    $content = ob_get_contents();
    ob_end_clean();

    echo $content;
}

add_action('marketpress_ecommerce_settings_option_page_payment_manual', 'marketpress_payment_options_page_manual');
function marketpress_payment_options_page_manual()
{

    $lists = get_option('mes_payment_manual_lists');

    if (empty($lists)) {
        $lists = [
            [
                'icon' => MARKETPRESS_URL . '/images/bank-bca.png',
                'bank' => 'BCA',
                'number' => '',
                'account' => '',
            ],
            [
                'icon' => MARKETPRESS_URL . '/images/bank-mandiri.png',
                'bank' => 'Mandiri',
                'number' => '',
                'account' => '',
            ],
            [
                'icon' => MARKETPRESS_URL . '/images/bank-bri.png',
                'bank' => 'BRI',
                'number' => '',
                'account' => '',
            ],
            [
                'icon' => MARKETPRESS_URL . '/images/bank-bni.png',
                'bank' => 'BNI',
                'number' => '',
                'account' => '',
            ]
        ];
    }

    ob_start();
?>
<div class="marketpress-manual-payment">
    <table>
        <tr>
            <th scope="row">
                <label><?php _e('Payment Label', 'marketpress'); ?></label>
            </th>
            <td>
                <input type="text" class="regular-text" name="mes_payment_manual_label"
                    value="<?php echo get_option('mes_payment_manual_label', 'Bank Transfer'); ?>" />
            </td>
        </tr>
        <tr>
            <th scope=" row">
                <label><?php _e('Bank Account', 'marketpress'); ?></label>
            </th>
            <td>
                <table class="marketpress-input-box">
                    <tbody class="append">
                        <?php foreach ((array)$lists as $key => $val) : ?>
                        <?php
                                $icon = isset($val['icon']) ? $val['icon'] : '';
                                ?>
                        <?php if (empty($val)) continue; ?>
                        <tr class="marketpress-shortcoe-field marketpressfield">
                            <td style="position:relative">
                                <input type="hidden" name="mes_payment_manual_lists[<?php echo $key; ?>][icon]"
                                    value="<?php echo $icon; ?>">
                                <?php if ($icon) { ?>
                                <img src="<?php echo $icon; ?>" style="max-height: 30px;width: auto" />
                                <?php } ?>
                                <span onclick="marketpressUploader(this);" class="dashicons dashicons-cloud-upload"
                                    style="position: absolute;right: 0;
                                top:15px;cursor:pointer;color:#0073aa;;"></span>
                            </td>
                            <td><input type="text" name="mes_payment_manual_lists[<?php echo $key; ?>][bank]"
                                    value="<?php echo $val['bank']; ?>" placeholder="Nama Bank"></td>
                            <td><input type="text" name="mes_payment_manual_lists[<?php echo $key; ?>][number]"
                                    value="<?php echo $val['number']; ?>" placeholder="Nomor Rekening"></td>
                            <td><input type="text" name="mes_payment_manual_lists[<?php echo $key; ?>][account]"
                                    value="<?php echo $val['account']; ?>" placeholder="Nama pemilik Rekening"></td>
                            <td>
                                <div style="text-align:center;height: 30px;line-height: 30px;">
                                    <span class="dashicons dashicons-trash marketpress-shortcoe-field-remove"
                                        style="cursor:pointer;margin-top: 9px;"></span>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td>
                                <button class="button add-more" type="button">Add Bank Account</button>
                            <td>
                            </td>
                            <td></td>
                        <tr>
                    </tfoot>
                </table>
            </td>
        </tr>
        <tr>
            <th scope="row">
                <label><?php _e('Payment Instruction', 'marketpress'); ?></label>
            </th>
            <td>
                <textarea class="regular-text"
                    name="mes_payment_manual_instruction"><?php echo get_option('mes_payment_manual_instruction', 'Silahkan pilih salah satu rekening berikut'); ?></textarea>
            </td>
        </tr>
    </table>

    <button class="button button-primary" type="submit" name="submit">Save Settings</button>
    <input type="hidden" name="marketpress_save_key" value="paymentmanual" />

    <script type="text/javascript">
    jQuery(document).ready(function($) {

        jQuery(".add-more").click(function() {
            let field_length = jQuery('.marketpress-input-box').find('tr.marketpressfield').length,
                field_number = field_length + 1;

            let html = '<tr class="marketpress-shortcoe-field marketpressfield">';
            html +=
                '<td style="position:relative"><input type="hidden" name="mes_payment_manual_lists[' +
                field_number +
                '][icon]"><img src="" style="max-height: 30px;width: auto" /><span onclick="marketpressUploader(this);" class="dashicons dashicons-cloud-upload" style="position: absolute;right: 0;top:15px;cursor:pointer;color:#0073aa;;"></span></td>';
            html += '<td><input type="text" name="mes_payment_manual_lists[' + field_number +
                '][bank]" placeholder="Bank Name"></td>';
            html += '<td><input type="text" name="mes_payment_manual_lists[' + field_number +
                '][number]" placeholder="Account Number"></td>';
            html += '<td><input type="text" name="mes_payment_manual_lists[' + field_number +
                '][account]" placeholder="Acocunt Name"></td>';
            html += '<td>';
            html += '<div style="text-align:center;height: 30px;line-height: 30px;">';
            html +=
                '<span class="dashicons dashicons-trash marketpress-shortcoe-field-remove" style="cursor:pointer;margin-top: 9px;"></span>';
            html += '</div></td>';
            html += '</tr>';
            jQuery('tbody.append').append(html);
        });

        jQuery("body").on("click", ".marketpress-shortcoe-field-remove", function() {
            jQuery(this).parents(".marketpress-shortcoe-field").remove();
        });

    });
    </script>
</div>
<?php
    $content = ob_get_contents();
    ob_end_clean();

    echo $content;
}