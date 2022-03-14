<?php


add_action('marketpress_ecommerce_settings_option_page_shipping_general', 'marketpress_shipping_options_page_general');
function marketpress_shipping_options_page_general()
{

    $ongkir_provider = array(
        'rajaongkir' => 'Raja Ongkir API',
        'flatshipping' => 'Flat Shipping'
    );

    $ongkir_provider = apply_filters('marketpress_ecommerce_settings_ongkir_provider', $ongkir_provider);
    ob_start();
?>
<table>
    <tr>
        <th scope="row">
            <label><?php _e('Enable Shipping?', 'marketpress'); ?></label>
        </th>
        <td>
            <input name="mes_shipping_enable" type="hidden" value="no" />
            <input name="mes_shipping_enable" type="checkbox" value="yes"
                <?php echo ('yes' == get_option('mes_shipping_enable')) ? 'checked="chekced"' : ''; ?> />
            <?php echo __('Check this if enable') ?>
        </td>
    </tr>
    <tr>
        <th scope="row">
            <label><?php _e('Shipping Provider', 'marketpress'); ?></label>
        </th>
        <td>
            <select name="mes_shipping_provider" class="regular-text">

                <?php foreach ((array) $ongkir_provider as $key => $val) : ?>
                <option value="<?php echo $key; ?>" <?php if (get_option('mes_shipping_provider') == $key) {
                                                                echo 'selected';
                                                            } ?>><?php echo $val; ?></option>
                <?php endforeach; ?>
            </select>
        </td>
    </tr>
    <tr>
        <th scope="row">
            <label><?php _e('Shipping Zone', 'marketpress'); ?></label>
        </th>
        <td id="shippingzone">
            <?php

                $zone_options = [
                    'mes_shipping_zone_province_id',
                    'mes_shipping_zone_province_name',
                    'mes_shipping_zone_district_id',
                    'mes_shipping_zone_district_name',
                    'mes_shipping_zone_district_type',
                    'mes_shipping_zone_subdistrict_id',
                    'mes_shipping_zone_subdistrict_name'
                ];

                foreach ((array) $zone_options as $opt) {
                    //echo get_option($opt) . '------<br/>';
                    echo '<input type="hidden" name="' . $opt . '" id="' . $opt . '" value="' . get_option($opt) . '" />';
                }
                ?>

            <select class="regular-text" id="provinceSelect">
                <option value="99999">Semua Provinsi</option>
            </select>
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

add_action('marketpress_ecommerce_settings_option_page_shipping_rajaongkir', 'marketpress_ecommerce_settings_option_page_shipping_rajaongkir');
function marketpress_ecommerce_settings_option_page_shipping_rajaongkir()
{

    ob_start();
?>
<table>
    <tr>
        <th scope="row">
            <label><?php _e('Api Key', 'marketpress'); ?></label>
        </th>
        <td>
            <input name="mes_shipping_rajaongkir_api_key" type="text"
                value="<?php echo get_option('mes_shipping_rajaongkir_api_key'); ?>" class="regular-text" />
            <div class="marketpress-ecommerce-settings-desc">Get your rajaongkir api key <a
                    href="https://rajaongkir.com/akun/panel" target="_blank">here</a></div>
        </td>
    </tr>
    <tr>
        <th scope="row">
            <label><?php _e('Shipping Origin', 'marketpress'); ?></label>
        </th>
        <td style="position:relative">
            <?php
                $origin_id = get_option('mes_rajaongkir_origin_id');
                $origin_name = get_option('mes_rajaongkir_origin_name');
                ?>
            <input type="hidden" name="mes_rajaongkir_origin_id" value="<?php echo $origin_id; ?>" />
            <input type="hidden" name="mes_rajaongkir_origin_name" value="<?php echo $origin_name; ?>" />
            <input type="text" class="regular-text" id="autoComplete" value="<?php echo $origin_name; ?>" />
            <?php
                $regions = MARKETPRESS_URL . '/data/regions/regions.json';
                ?>
            <script>
            new autoComplete({
                data: {
                    src: async () => {
                        const source = await fetch('<?php echo $regions; ?>');
                        const data = await source.json();
                        return data;
                    },
                    key: ["text"],
                    cache: false
                },
                sort: (a, b) => {
                    if (a.match < b.match) return -1;
                    if (a.match > b.match) return 1;
                    return 0;
                },
                placeHolder: "Cari Kecamatan",
                selector: "#autoComplete",
                threshold: 1,
                debounce: 300,
                searchEngine: "strict",
                resultsList: {
                    render: true,
                    container: source => {
                        source.setAttribute('class', 'region_list');
                    },
                    destination: document.querySelector("#autoComplete"),
                    position: "afterend",
                    element: "ul"
                },
                maxResults: 10,
                highlight: true,
                resultItem: {
                    content: (data, source) => {
                        source.innerHTML = data.match;
                    },
                    element: "li"
                },
                noResults: () => {
                    const result = document.createElement("li");
                    result.setAttribute("class", "autoComplete_result");
                    result.setAttribute("tabindex", "1");
                    result.innerHTML = "Tidak di temukan hasil";
                    document.querySelector("#autoComplete_list").appendChild(result);
                },
                onSelection: feedback => {
                    document.querySelector('[name="mes_rajaongkir_origin_id"]').value = feedback
                        .selection.value.id;
                    document.querySelector('[name="mes_rajaongkir_origin_name"]').value = feedback
                        .selection.value.text;
                    document.querySelector("#autoComplete").value = feedback
                        .selection.value.text
                }
            });
            </script>
        </td>
    </tr>
    <tr>
        <th scope="row">
            <label><?php _e('Account Type', 'marketpress'); ?></label>
        </th>
        <td>
            <select name="mes_rajaongkir_account_type" class="regular-text rajaongkirtype">
                <?php
                    $type = get_option('mes_rajaongkir_account_type');
                    $list = array(
                        'starter' => 'Starter',
                        'basic' => 'Basic',
                        'pro' => 'Pro'
                    );

                    foreach ($list as $key => $val) {
                        $selected = '';
                        if ($key == $type) {
                            $selected = ' selected="selected"';
                        }
                        echo '<option value="' . $key . '"' . $selected . '>' . $val . '</option>';
                    }
                    ?>
            </select>
        </td>
    </tr>
    <tr>
        <th scope="row">
            <label><?php _e('Kurir', 'marketpress'); ?></label>
        </th>
        <td style="position: relative;" class="marketpress-cleafix">
            <?php
                $rajaongkir_account_type = get_option('mes_rajaongkir_account_type', 'starter');

                $kurir = get_option('mes_rajaongkir_kurir') ? get_option('mes_rajaongkir_kurir') : array();
                ?>
            <label class="kurir starter">
                <input name="mes_rajaongkir_kurir[]" type="checkbox" value="pos"
                    <?php echo (in_array('pos', $kurir)) ? 'checked="chekced"' : ''; ?> />
                <?php echo __('Pos') ?>
            </label>
            <label class="kurir starter">
                <input name="mes_rajaongkir_kurir[]" type="checkbox" value="jne"
                    <?php echo (in_array('jne', $kurir)) ? 'checked="chekced"' : ''; ?> />
                <?php echo __('JNE') ?>
            </label>
            <label class="kurir starter">
                <input name="mes_rajaongkir_kurir[]" type="checkbox" value="tiki"
                    <?php echo (in_array('tiki', $kurir)) ? 'checked="chekced"' : ''; ?> />
                <?php echo __('Tiki') ?>
            </label>

            <br />
            <br />
            <?php
                $basic = array(
                    array(
                        'id' => 'pcp',
                        'name' => 'Priority Cargo and Package',
                    ),
                    array(
                        'id' => 'esl',
                        'name' => 'Eka Sari Lorena',
                    ),
                    array(
                        'id' => 'rpx',
                        'name' => 'RPX Holding',
                    )
                )
                ?>

            <?php foreach ((array)$basic as $courier) :
                    $disabled = '';
                    if ($rajaongkir_account_type == 'starter') {
                        $disabled = 'disabled="disabled"';
                    }
                ?>
            <label class="kurir">
                <input class="basic" name="mes_rajaongkir_kurir[]" type="checkbox" value="<?php echo $courier['id']; ?>"
                    <?php echo (in_array($courier['id'], $kurir)) ? 'checked="chekced"' : ''; ?>
                    <?php echo $disabled; ?> />
                <?php echo $courier['name']; ?>
            </label>
            <?php endforeach; ?>


            <br />
            <br />
            <?php
                $pro = array(
                    array(
                        'id' => 'pandu',
                        'name' => 'Pandu Logistics',
                    ),
                    array(
                        'id' => 'wahana',
                        'name' => 'Wahana Prestasi Logistik'
                    ),
                    array(
                        'id' => 'sicepat',
                        'name' => 'SiCepat Express',
                    ),
                    array(
                        'id' => 'jnt',
                        'name' => 'JnT Express',
                    ),
                    array(
                        'id' => 'pahala',
                        'name' => 'PAHALA KENCANA',
                    ),
                    array(
                        'id' => 'sap',
                        'name' => 'SAP Express',
                    ),
                    array(
                        'id' => 'jet',
                        'name' => 'JET Express',
                    ),
                    array(
                        'id' => 'slis',
                        'name' => 'Solusi Express',
                    ),
                    array(
                        'id' => 'dse',
                        'name' => '21 Express'
                    ),
                    array(
                        'id' => 'first',
                        'name' => 'First Express',
                    ),
                    array(
                        'id' => 'ncs',
                        'name' => 'Nusantara Card Semesta',
                    ),
                    array(
                        'id' => 'star',
                        'name' => 'Star Cargo',
                    ),
                    array(
                        'id' => 'lion',
                        'name' => 'Lion Parcel',
                    ),
                    array(
                        'id' => 'ninja',
                        'name' => 'Ninja Express',
                    ),
                    array(
                        'id' => 'idl',
                        'name' => 'IDL Cargo',
                    ),
                    array(
                        'id' => 'rex',
                        'name' => 'Royal Express Indonesia'
                    )
                );
                ?>
            <?php foreach ((array)$pro as $courier) :
                    $disabled = '';
                    if ($rajaongkir_account_type == 'starter' || $rajaongkir_account_type == 'basic') {
                        $disabled = 'disabled="disabled"';
                    }
                ?>
            <label class="kurir">
                <input class="pro" name="mes_rajaongkir_kurir[]" type="checkbox" value="<?php echo $courier['id']; ?>"
                    <?php echo (in_array($courier['id'], $kurir)) ? 'checked="chekced"' : ''; ?>
                    <?php echo $disabled; ?> />
                <?php echo $courier['name']; ?>
            </label>
            <?php endforeach; ?>
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


add_action('marketpress_ecommerce_settings_option_page_shipping_flatshipping', 'marketpress_shipping_options_page_flatshipping');
function marketpress_shipping_options_page_flatshipping()
{

    $lists = get_option('mes_flatshipping_lists');

    ob_start();
?>
<div class="marketpress-flatongkir">
    <table class="marketpress-input-box">
        <tbody>
            <?php foreach ((array)$lists as $key => $val) : ?>
            <?php if (empty($val)) continue; ?>
            <tr class="marketpress-shortcoe-field marketpressfield">
                <td><input type="text" name="mes_flatshipping_lists[<?php echo $key; ?>][location]"
                        value="<?php echo $val['location']; ?>"></td>
                <td><input type="text" name="mes_flatshipping_lists[<?php echo $key; ?>][cost]"
                        value="<?php echo $val['cost']; ?>"></td>
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
                    <button class="button add-more" type="button">Add Option</button>
                <td>
                </td>
                <td></td>
            <tr>
        </tfoot>
    </table>

    <button class="button button-primary" type="submit" name="submit">Save Settings</button>
    <input type="hidden" name="marketpress_save_key" value="flatshipping" />

    <script type="text/javascript">
    jQuery(document).ready(function($) {

        jQuery(".add-more").click(function() {
            let field_length = jQuery('.marketpress-input-box').find('tr.marketpressfield').length,
                field_number = field_length + 1;

            let html = '<tr class="marketpress-shortcoe-field marketpressfield">';
            html += '<td><input type="text" name="mes_flatshipping_lists[' + field_number +
                '][location]" placeholder="Label"></td>';
            html += '<td><input type="text" name="mes_flatshipping_lists[' + field_number +
                '][cost]" placeholder="Cost"></td>';
            html += '<td>';
            html += '<div style="text-align:center;height: 30px;line-height: 30px;">';
            html +=
                '<span class="dashicons dashicons-trash marketpress-shortcoe-field-remove" style="cursor:pointer;margin-top: 9px;"></span>';
            html += '</div></td>';
            html += '</tr>';
            jQuery('tbody').append(html);
        });

        jQuery("body").on("click", ".marketpress-shortcoe-field-remove", function() {
            jQuery(this).parents(".marketpress-shortcoe-field").remove();
        });

    });

    function marketpressCopy(ini) {
        let input = jQuery(ini).parent().find('input');
        input.select();
        document.execCommand('copy');
    }
    </script>
</div>
<?php
    $content = ob_get_contents();
    ob_end_clean();

    echo $content;
}