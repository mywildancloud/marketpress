jQuery(document).ready(function() {
    jQuery('.cmb2_select').parent().append('<div id="marketpress-notice"></div>');
    jQuery('.cmb2_select').on('change', function() {
        let jQueryvalue = jQuery(this).val(),
            jQueryhtml = '';

        if (jQueryvalue == 'form') {
            jQueryhtml = '<br/><br/><span style="color:red">Silahkan anda masukan script form checkout order online atau layanan pihak ketiga ke text editor</span>';
            jQuery(this).parent().find('#marketpress-notice').append(jQueryhtml);
        } else if (jQueryvalue == 'atc') {
            jQueryhtml = '<br/><br/><span style="color:red">silahkan anda mengisi keterangan di text editor</span>';
            jQuery(this).parent().find('#marketpress-notice').append(jQueryhtml);
        } else {
            jQuery(this).parent().find('#marketpress-notice').empty();
        }
    });

    let jQuerymenu_li = jQuery('#toplevel_page_marketpress').find('li');

    for (i = 0; jQuerymenu_li.length > i; i++) {
        if (i == 4) {
            jQuery(jQuerymenu_li[i]).find('a').attr('target', '__blank');
        }
        if (i == 5) {
            jQuery(jQuerymenu_li[i]).find('a').attr('target', '__blank');
        }
    }

    jQuery('.marketpress-field__datetimepicker').each(function() {
        let hasOptions = jQuery(this).attr('data-options'),
            parseOptions = {},
            defaultOptions = {
                minDate: new Date()
            },
            options = {};
        if (typeof hasOptions !== 'undefined' && hasOptions !== false) {
            parseOptions = JSON.parse(hasOptions);
        }

        options = {...defaultOptions, ...parseOptions };
        jQuery(this).datetimepicker(options);
    });


    jQuery('.rajaongkirtype').on('change', function() {
        let val = jQuery(this).val();

        if (val == 'starter') {
            jQuery('.basic').prop('checked', false);
            jQuery('.basic').prop('disabled', true);

            jQuery('.pro').prop('checked', false);
            jQuery('.pro').prop('disabled', true);
        } else if (val == 'basic') {
            jQuery('.basic').prop('disabled', false);

            jQuery('.pro').prop('checked', false);
            jQuery('.pro').prop('disabled', true);
        } else {
            jQuery('.basic').prop('disabled', false);

            jQuery('.pro').prop('disabled', false);
        }
    });

    const $selectProvince = jQuery('#provinceSelect');

    let loadSelectSubDistrict = function() {

        let $id = 'subdistrictSelect',
            $current_district_id = jQuery('#mes_shipping_zone_district_id').val(),
            $subdistrict_id = jQuery('#mes_shipping_zone_subdistrict_id'),
            $subdistrict_name = jQuery('#mes_shipping_zone_subdistrict_name'),
            $current_subdistrict_id = $subdistrict_id.val(),
            $current_value = 99999,
            $index = 0;

        if (jQuery('#' + $id).length) {
            jQuery('#' + $id).remove();
        }

        if ($current_district_id == 99999) {
            $subdistrict_id.val(99999);
            $subdistrict_name.val('Semua Kecamatan');
            return;
        }

        let $element = jQuery('<select>', {
            id: $id,
            class: 'regular-text'
        });

        $element.append(new Option('Semua Kecamatan', 99999));

        jQuery('#shippingzone').append($element);

        jQuery.getJSON(marketpress_admin.regions_source + 'subdistrict.json', function(data) {

            const ddata = data.filter(function(d) {
                return d.city_id == $current_district_id;
            });

            for (let i = 0; ddata.length > i; i++) {
                $element.append(new Option(ddata[i].subdistrict_name, ddata[i].subdistrict_id));
                if (ddata[i].subdistrict_id == $current_subdistrict_id) {
                    $current_value = $current_subdistrict_id;
                    $index = i;
                }
            }

            if ($current_value == 99999) {
                $subdistrict_id.val(99999);
                $subdistrict_name.val('Semua Kecamatan');
            } else {
                jQuery('#' + $id).val($current_value);

                $subdistrict_id.val(ddata[$index].subdistrcit_id);
                $subdistrict_name.val(ddata[$index].subdistrcit_name);
            }

            $element.on('change', function(e) {

                let iindex = parseInt(e.target.selectedIndex) - 1;
                if (iindex < 0) {
                    $subdistrict.val(99999);
                    $subdistrict_name.val('Semua Kecamatan');
                } else {
                    $subdistrict_id.val(ddata[iindex].subdistrict_id);
                    $subdistrict_name.val(ddata[iindex].subdistrict_name);
                }

            })


        });

    }

    let loadSelectDistrict = function() {

        let id = 'districtSelect',
            $current_province_id = jQuery('#mes_shipping_zone_province_id').val(),
            $district_id = jQuery('#mes_shipping_zone_district_id'),
            $district_name = jQuery('#mes_shipping_zone_district_name'),
            $district_type = jQuery('#mes_shipping_zone_district_type'),
            $current_district_id = $district_id.val(),
            $current_value = 99999,
            $index = 0;

        if (jQuery('#' + id).length) {
            jQuery('#' + id).remove();
        }

        if ($current_province_id == 99999) {
            $district_id.val(99999);
            $district_name.val('Semua Kota/Kabupaten');
            $district_type.val('');

            loadSelectSubDistrict();

            return;
        }

        let $element = jQuery('<select>', {
            id: id,
            class: 'regular-text'
        });

        $element.append(new Option('Semua Kota/Kabupaten', 99999));

        jQuery('#shippingzone').append($element);

        jQuery.getJSON(marketpress_admin.regions_source + 'district.json', function(data) {
            const ddata = data.filter(function(d) {
                return d.province_id == $current_province_id;
            });

            for (let i = 0; ddata.length > i; i++) {
                $element.append(new Option(ddata[i].type + ' ' + ddata[i].city_name, ddata[i].city_id));
                if (ddata[i].city_id == $current_district_id) {
                    $current_value = $current_district_id;
                    $index = i;
                }
            }

            if ($current_value == 99999) {
                $district_id.val(99999);
                $district_name.val('Semua Kota/Kabupaten');
                $district_type.val('');
            } else {
                jQuery('#' + id).val($current_value);

                $district_id.val(ddata[$index].city_id);
                $district_name.val(ddata[$index].city_name);
                $district_type.val(ddata[$index].type);
            }

            loadSelectSubDistrict();

            $element.on('change', function(e) {

                let iindex = parseInt(e.target.selectedIndex) - 1;
                if (iindex < 0) {
                    $district_id.val(99999);
                    $district_name.val('Semua Kota/Kabupaten');
                    $district_type.val('');
                } else {
                    $district_id.val(ddata[iindex].city_id);
                    $district_name.val(ddata[iindex].city_name);
                    $district_type.val(ddata[iindex].type);
                }

                loadSelectSubDistrict();
            })
        });
    }

    let loadSelectProvince = function() {
        let $province_id = jQuery('#mes_shipping_zone_province_id'),
            $province_name = jQuery('#mes_shipping_zone_province_name'),
            $current_province_id = $province_id.val(),
            $current_value = 99999,
            $index = 0;

        jQuery.getJSON(marketpress_admin.regions_source + 'province.json', function(data) {
            for (let i = 0; data.length > i; i++) {
                $selectProvince.append(new Option(data[i].province, data[i].province_id));
                if (data[i].province_id == $current_province_id) {
                    $current_value = $current_province_id;
                    $index = i;
                }
            }

            if ($current_value == 99999) {
                $province_id.val(99999);
                $province_name.val('Semua Provinsi');
            } else {
                $selectProvince.val($current_value);

                $province_id.val(data[$index].province_id);
                $province_name.val(data[$index].province);
            }

            loadSelectDistrict();

            $selectProvince.on('change', function(e) {

                let iindex = parseInt(e.target.selectedIndex) - 1;
                if (iindex < 0) {
                    $province_id.val(99999);
                    $province_name.val('Semua Provinsi');
                } else {
                    $province_id.val(data[iindex].province_id);
                    $province_name.val(data[iindex].province);
                }

                loadSelectDistrict();
            })
        });
    }

    if ($selectProvince.length > 0) {
        loadSelectProvince();
    }
})

function customerFollowUp(nomor) {

    let wa = 'https://web.whatsapp.com/send';

    if (/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent)) {
        wa = 'whatsapp://send';
    }

    let url = wa + '?phone=' + nomor;

    let w = 960,
        h = 540,
        left = Number((screen.width / 2) - (w / 2)),
        top = Number((screen.height / 2) - (h / 2)),
        popupWindow = window.open(url, '', 'toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=yes, resizable=1, copyhistory=no, width=' + w + ', height=' + h + ', top=' + top + ', left=' + left);

    popupWindow.focus();
    return false;
}

function marketpressUploader(ini) {

    let td = jQuery(ini).parent(),
        field = td.find('input'),
        img = td.find('img'),
        marketpressImageFrame;

    // Sets up the media library frame
    marketpressImageFrame = wp.media.frames.marketpressImageFrame = wp.media({
        title: 'Upload Image',
        button: { text: 'Use this file' },
    });

    // Runs when an image is selected.
    marketpressImageFrame.on('select', function() {

        // Grabs the attachment selection and creates a JSON representation of the model.
        var media_attachment = marketpressImageFrame.state().get('selection').first().toJSON();

        // Sends the attachment URL to our custom image input field.
        field.val(media_attachment.url);
        img.attr('src', media_attachment.url);

    });

    // Opens the media library frame.
    marketpressImageFrame.open();
}