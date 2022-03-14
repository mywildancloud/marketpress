<?php
function marketpress_rajaongkir_api_url()
{

    $type = get_option('mes_rajaongkir_account_type');

    $url = 'https://api.rajaongkir.com/starter/cost';

    if ($type == 'pro') :
        $url = 'https://pro.rajaongkir.com/api/cost';
    elseif ($type == 'basic') :
        $url = 'https://api.rajaongkir.com/basic/cost';
    endif;

    return $url;
}

function marketpress_get_rajaongkir_ongkir($api_key, $account_type, $origin, $destination, $weight, $courier)
{

    if (empty($api_key)) return false;

    if (empty($origin) || empty($destination) || empty($weight) || empty($courier)) return false;

    $url = marketpress_rajaongkir_api_url();

    if ($account_type == 'pro') :
        $originType = 'subdistrict';
        $destinationType = 'subdistrict';
    else :
        $originType = 'city';
        $destinationType = 'city';
    endif;

    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => "origin=$origin&originType=$originType&destination=$destination&destinationType=$destinationType&weight=$weight&courier=$courier",
        CURLOPT_HTTPHEADER => array(
            "content-type: application/x-www-form-urlencoded",
            "key: $api_key"
        ),
    ));

    $respons = curl_exec($curl);
    $err = curl_error($curl);

    curl_close($curl);

    $result = json_decode($respons, true);

    $return = false;

    if (isset($result['rajaongkir']['status']['code']) && $result['rajaongkir']['status']['code'] == 200) :

        if (isset($result['rajaongkir']['results'][0])) :
            $data_couriers = $result['rajaongkir']['results'];
            $data = array();
            foreach ((array)$data_couriers as $data_courier) {
                $services = [];
                foreach ((array)$data_courier['costs'] as $service) {
                    foreach ((array)$service['cost'] as $cost) {
                        $services[] = [
                            'service' => $service['service'],
                            'description' => $service['description'],
                            'value' => intval($cost['value']),
                            'etd' => $cost['etd'],
                            'note' => $cost['note']
                        ];
                    }
                }

                if (empty($service)) continue;

                $data_courier['costs'] = $services;

                if ($data_courier['code'] == 'jne') {
                    $data_courier['icon'] = '<img src="' . MARKETPRESS_URL . '/images/jne.png">';
                } else if ($data_courier['code'] == 'J&T') {
                    $data_courier['icon'] = '<img src="' . MARKETPRESS_URL . '/images/jnt.png">';
                } else if ($data_courier['code'] == 'sicepat') {
                    $data_courier['icon'] = '<img src="' . MARKETPRESS_URL . '/images/sicepat.png">';
                } else {
                    $data_courier['icon'] = '
<svg id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 512 512" style="enable-background:new 0 0 512 512" width="35" height="35"><g><g><path d="M476.158 231.363l-13.259-53.035c3.625-.77 6.345-3.986 6.345-7.839v-8.551c0-18.566-15.105-33.67-33.67-33.67h-60.392V110.63c0-9.136-7.432-16.568-16.568-16.568H50.772c-9.136.0-16.568 7.432-16.568 16.568V256c0 4.427 3.589 8.017 8.017 8.017 4.427.0 8.017-3.589 8.017-8.017V110.63c0-.295.239-.534.534-.534h307.841c.295.0.534.239.534.534v145.372c0 4.427 3.589 8.017 8.017 8.017 4.427.0 8.017-3.589 8.017-8.017v-9.088h94.569c.008.0.014.002.021.002.008.0.015-.001.022-.001 11.637.008 21.518 7.646 24.912 18.171h-24.928c-4.427.0-8.017 3.589-8.017 8.017v17.102c0 13.851 11.268 25.119 25.119 25.119h9.086v35.273h-20.962c-6.886-19.883-25.787-34.205-47.982-34.205s-41.097 14.322-47.982 34.205h-3.86v-60.393c0-4.427-3.589-8.017-8.017-8.017-4.427.0-8.017 3.589-8.017 8.017v60.391H192.817c-6.886-19.883-25.787-34.205-47.982-34.205s-41.097 14.322-47.982 34.205H50.772c-.295.0-.534-.239-.534-.534v-17.637h34.739c4.427.0 8.017-3.589 8.017-8.017s-3.589-8.017-8.017-8.017H8.017C3.59 316.39.0 319.979.0 324.407s3.589 8.017 8.017 8.017h26.188v17.637c0 9.136 7.432 16.568 16.568 16.568h43.304c-.002.178-.014.355-.014.534.0 27.996 22.777 50.772 50.772 50.772s50.772-22.776 50.772-50.772c0-.18-.012-.356-.014-.534h180.67c-.002.178-.014.355-.014.534.0 27.996 22.777 50.772 50.772 50.772 27.995.0 50.772-22.776 50.772-50.772.0-.18-.012-.356-.014-.534h26.203c4.427.0 8.017-3.589 8.017-8.017v-85.511C512 251.989 496.423 234.448 476.158 231.363zM375.182 144.301h60.392c9.725.0 17.637 7.912 17.637 17.637v.534h-78.029V144.301zM375.182 230.881v-52.376h71.235l13.094 52.376H375.182zM144.835 401.904c-19.155.0-34.739-15.583-34.739-34.739s15.584-34.739 34.739-34.739c19.155.0 34.739 15.583 34.739 34.739S163.99 401.904 144.835 401.904zm282.188.0c-19.155.0-34.739-15.583-34.739-34.739s15.584-34.739 34.739-34.739c19.155.0 34.739 15.583 34.739 34.739S446.178 401.904 427.023 401.904zM495.967 299.29h-9.086c-5.01.0-9.086-4.076-9.086-9.086v-9.086h18.171V299.29z"/></g></g><g><g><path d="M144.835 350.597c-9.136.0-16.568 7.432-16.568 16.568.0 9.136 7.432 16.568 16.568 16.568 9.136.0 16.568-7.432 16.568-16.568C161.403 358.029 153.971 350.597 144.835 350.597z"/></g></g><g><g><path d="M427.023 350.597c-9.136.0-16.568 7.432-16.568 16.568.0 9.136 7.432 16.568 16.568 16.568 9.136.0 16.568-7.432 16.568-16.568C443.591 358.029 436.159 350.597 427.023 350.597z"/></g></g><g><g><path d="M332.96 316.393H213.244c-4.427.0-8.017 3.589-8.017 8.017s3.589 8.017 8.017 8.017H332.96c4.427.0 8.017-3.589 8.017-8.017S337.388 316.393 332.96 316.393z"/></g></g><g><g><path d="M127.733 282.188H25.119c-4.427.0-8.017 3.589-8.017 8.017s3.589 8.017 8.017 8.017h102.614c4.427.0 8.017-3.589 8.017-8.017S132.16 282.188 127.733 282.188z"/></g></g><g><g><path d="M278.771 173.37c-3.13-3.13-8.207-3.13-11.337.001l-71.292 71.291-37.087-37.087c-3.131-3.131-8.207-3.131-11.337.0-3.131 3.131-3.131 8.206.0 11.337l42.756 42.756c1.565 1.566 3.617 2.348 5.668 2.348s4.104-.782 5.668-2.348l76.96-76.96C281.901 181.576 281.901 176.501 278.771 173.37z"/></g></g><g/><g/><g/><g/><g/><g/><g/><g/><g/><g/><g/><g/><g/><g/><g/></svg>';
                }

                $data[] = $data_courier;
            }

            $return = $data;
        else :
            $return = array(
                'code' => $result['rajaongkir']['status']['code'],
                'message' => $result['rajaongkir']['status']['description']
            );
        endif;
    else :
        $return = array(
            'code' => $result['rajaongkir']['status']['code'],
            'message' => $result['rajaongkir']['status']['description']
        );

    endif;

    return $return;
}

add_action('wp_ajax_get_ongkir', 'marketpress_ajax_get_ongkir');
add_action('wp_ajax_nopriv_get_ongkir', 'marketpress_ajax_get_ongkir');
function marketpress_ajax_get_ongkir()
{

    $nonce = isset($_REQUEST['nonce']) ? $_REQUEST['nonce'] : '';
    if (!wp_verify_nonce($nonce, 'marketpress')) exit;

    $api_key      = get_option('mes_shipping_rajaongkir_api_key');
    $account_type = get_option('mes_rajaongkir_account_type');
    $couriers     = get_option('mes_rajaongkir_kurir');
    $origin_id    = explode('-', get_option('mes_rajaongkir_origin_id'));

    if ($account_type == 'pro') :
        $origin = isset($origin_id[0]) ? $origin_id[0] : '';
    else :
        $origin = isset($origin_id[1]) ? $origin_id[1] : '';
    endif;

    $destination_id = isset($_GET['destination']) ? sanitize_text_field($_GET['destination']) : '';
    $weight = isset($_GET['weight']) ? intval($_GET['weight']) : 1000;

    if (!$destination_id) exit;

    $destination_id = explode('-', $destination_id);

    if ($account_type == 'pro') :
        $destination = isset($destination_id[0]) ? $destination_id[0] : '';
    else :
        $destination = isset($destination_id[1]) ? $destination_id[1] : '';
    endif;


    $datas = array();
    $error = array(
        'code' => 404,
        'message' => 'Tidak adapt membuat data ongkir silahkan kontak admin'
    );

    foreach ($couriers as $key => $val) :

        $data = marketpress_get_rajaongkir_ongkir($api_key, $account_type, $origin, $destination, $weight, $val);

        if (isset($data['code']) && isset($data['message'])) {
            $error = $data;
            continue;
        }

        if (is_array($data)) :
            $datas = array_merge((array)$data, $datas);
        endif;

    endforeach;

    if (!$datas) :
        echo json_encode($error);
        exit;
    endif;

    echo json_encode($datas);
    exit;
}

//add_action('wp', 'test');
function test()
{
    if (is_admin()) return;

    $api_key      = get_option('mes_shipping_rajaongkir_api_key');
    $account_type = get_option('mes_rajaongkir_account_type');
    $couriers     = get_option('mes_rajaongkir_kurir');
    $origin_id    = explode('-', get_option('mes_rajaongkir_origin_id'));

    if ($account_type == 'pro') :
        $origin = isset($origin_id[0]) ? $origin_id[0] : '';
    else :
        $origin = isset($origin_id[1]) ? $origin_id[1] : '';
    endif;

    $destination_id = '2471-177';
    $weight = 1000;

    if (!$destination_id) exit;

    $destination_id = explode('-', $destination_id);

    if ($account_type == 'pro') :
        $destination = isset($destination_id[0]) ? $destination_id[0] : '';
    else :
        $destination = isset($destination_id[1]) ? $destination_id[1] : '';
    endif;


    $datas = array();
    $error = '';

    foreach ($couriers as $key => $val) :

        $data = marketpress_get_rajaongkir_ongkir($api_key, $account_type, $origin, $destination, $weight, $val);

        if (isset($data['code']) && isset($data['message'])) {
            $error = $data['code'] . ': ' . $data['message'];
            continue;
        }

        if (is_array($data)) :
            $datas = array_merge((array)$data, $datas);
        endif;

    endforeach;

    if (!$datas) :
        echo $error;
        exit;
    endif;

    __debug($datas);
    exit;
}