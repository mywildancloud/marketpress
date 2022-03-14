<?php
$favicon = get_site_icon_url();
if (empty($favicon)) {
    $favicon = MARKETPRESS_URL . '/images/favicon.png';
}

$hashed_order_id = get_query_var('order_id');
$order_id = marketpress_crypt($hashed_order_id, true);
$order = marketpress_get_order($order_id);
if (empty($order)) {
    wp_redirect(site_url());
}


if ($order->payment_method == 'cod') {
    $payment_info = get_option('mes_payment_cod_instruction', 'Metode pembayaran di tempat di kenakan biaya admin sebesar Rp 10.000');
    $payment = get_option('mes_payment_cod_label', 'COD (Bayar ditempat)');
} else if ($order->payment_method == 'manual') {
    $payment = 'Bank Transfer';
    $payment_info = get_option('mes_payment_manual_instruction', 'Silahkan pilih salah satu rekening berikut');
    $rekenings = get_option('mes_payment_manual_lists');
    foreach ((array)$rekenings as $key => $val) {
        $payment .= '<div>' . $val['bank'] . ' | ' . $val['number'] . ' | ' . $val['account'] . '</div>';
    }
} else {
    $payment = $order->payment_method;
    $payment_info = get_option('mes_payment_manual_instruction', 'Silahkan lakukan pembayaran ke rekening berikut');
    $rekenings = get_option('mes_payment_manual_lists');

    if (strpos($payment, 'COD') !== false || strpos($payment, 'cod') !== false || strpos($payment, 'Bayar ditempat') !== false) {
        $payment_info = get_option('mes_payment_cod_instruction', 'Metode pembayaran di tempat di kenakan biaya admin sebesar Rp 10.000');
    } else {

        $payment_explode = explode(' - ', $payment);
        $bank = isset($payment_explode[1]) ? sanitize_text_field($payment_explode[1]) : '';
        $u_bank = strtoupper($bank);
        $l_bank = strtolower($bank);

        foreach ((array)$rekenings as $key => $val) {
            if (empty($bank) && $key == 0) {
                $payment .= '<div>' . $val['bank'] . ' | ' . $val['number'] . ' | ' . $val['account'] . '</div>';
                break;
            } else {
                if (strpos($val['bank'], $bank) !== false || strpos($val['bank'], $u_bank) !== false || strpos($val['bank'], $l_bank) !== false) {
                    $bank_name = '';
                    if (isset($val['icon']) && $val['icon']) {
                        $bank_name = '<img src="' . $val['icon'] . '"/>';
                    }
                    $payment .= '<div class="bank">' . $bank_name . ' <p class="bank_account">' . $val['account'] . '</p> <p class="bank_number"><input type="text" value="' . $val['number'] . '"> <svg id="copyBankNumber" width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
<path d="M15 2.5H8.33333C7.8731 2.5 7.5 2.8731 7.5 3.33333V13.3333C7.5 13.7936 7.8731 14.1667 8.33333 14.1667H15C15.4602 14.1667 15.8333 13.7936 15.8333 13.3333V3.33333C15.8333 2.8731 15.4602 2.5 15 2.5Z" stroke="#A2B6BC" stroke-width="1.25" stroke-linecap="round" stroke-linejoin="round"/>
<path d="M4.16675 5V16.6667C4.16675 16.8877 4.25455 17.0996 4.41083 17.2559C4.56711 17.4122 4.77907 17.5 5.00008 17.5H12.5001" stroke="#A2B6BC" stroke-width="1.25" stroke-linecap="round" stroke-linejoin="round"/>
</svg></p></div>';
                    break;
                }
            }
        }
    }
}
?>
<!doctype html>
<html <?php language_attributes(); ?>>

<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="profile" href="https://gmpg.org/xfn/11">
    <link href="<?php echo $favicon; ?>" rel="shortcut icon">
    <title>Thanks &#8211; <?php echo bloginfo('name'); ?></title>
    <?php wp_head(); ?>
    <?php

    $detail = $order->get_detail();

    $phpjs = array(
        'link_wa' => 'https://web.whatsapp.com/send?phone=' . marketpress_get_admin_phone() . '&',
        'items' => json_encode($order->get_items()),
        'customer' => json_encode($order->get_customer()),
        'detail' => json_encode($detail)
    );

    echo '<script type="text/javascript">';
    echo 'const marketpress_thanks = {';
    $items = array();
    foreach ($phpjs as $key => $value) {
        $val = '"' . $value . '"';

        $bools = array(
            'items',
            'customer',
            'detail'
        );

        if (in_array($key, $bools)) {
            $val = $value;
        }
        $items[] = '"' . $key . '" : ' . $val;
    }

    echo implode(',', $items);
    echo '}';
    echo '</script>';
    ?>
</head>

<body>
    <div id="thanks">
        <div class="thanks-wrapper">
            <div class="thanks-head">
                <svg width="304" height="304" viewBox="0 0 304 304" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <circle cx="146.163" cy="149.888" r="87.8882" fill="#D6F8FF" />
                    <circle cx="153.5" cy="167.5" r="47.5" fill="white" />
                    <path
                        d="M96.5 166C112.069 166 130.755 170.897 145.835 190.772C146.89 192.162 149.159 191.75 149.598 190.061C158.746 154.885 180.042 114.826 233 106"
                        stroke="#6B899A" stroke-width="7.20812" stroke-linecap="round" />
                    <circle cx="230.873" cy="80.9699" r="7.20812" stroke="#DCDCDC" stroke-width="1.44162" />
                    <circle cx="29.0457" cy="134.309" r="4.32487" stroke="#DCDCDC" stroke-width="1.44162" />
                    <circle cx="184.741" cy="253.965" r="5.7665" stroke="#DCDCDC" stroke-width="1.44162" />
                    <path
                        d="M125.724 40.6538C126.189 39.3987 127.964 39.3987 128.428 40.6538L129.377 43.218C129.523 43.6126 129.834 43.9237 130.229 44.0697L132.793 45.0185C134.048 45.483 134.048 47.2582 132.793 47.7226L130.229 48.6714C129.834 48.8174 129.523 49.1285 129.377 49.5231L128.428 52.0873C127.964 53.3424 126.189 53.3424 125.724 52.0873L124.775 49.5231C124.629 49.1285 124.318 48.8174 123.924 48.6714L121.359 47.7226C120.104 47.2582 120.104 45.483 121.359 45.0185L123.924 44.0697C124.318 43.9237 124.629 43.6126 124.775 43.218L125.724 40.6538Z"
                        fill="#6B899A" />
                    <path
                        d="M243.12 197.546C243.37 196.87 244.326 196.87 244.576 197.546L245.087 198.926C245.165 199.139 245.333 199.306 245.545 199.385L246.926 199.896C247.602 200.146 247.602 201.102 246.926 201.352L245.545 201.863C245.333 201.941 245.165 202.109 245.087 202.321L244.576 203.702C244.326 204.378 243.37 204.378 243.12 203.702L242.609 202.321C242.53 202.109 242.363 201.941 242.15 201.863L240.77 201.352C240.094 201.102 240.094 200.146 240.77 199.896L242.15 199.385C242.363 199.306 242.53 199.139 242.609 198.926L243.12 197.546Z"
                        fill="#6B899A" />
                    <path
                        d="M46.8506 176.485C47.1721 175.616 48.4011 175.616 48.7226 176.485L49.3795 178.26C49.4806 178.533 49.696 178.748 49.9692 178.849L51.7444 179.506C52.6133 179.828 52.6133 181.057 51.7444 181.378L49.9692 182.035C49.696 182.136 49.4806 182.352 49.3795 182.625L48.7226 184.4C48.4011 185.269 47.1721 185.269 46.8506 184.4L46.1937 182.625C46.0926 182.352 45.8772 182.136 45.6041 182.035L43.8289 181.378C42.96 181.057 42.96 179.828 43.8289 179.506L45.6041 178.849C45.8772 178.748 46.0926 178.533 46.1937 178.26L46.8506 176.485Z"
                        fill="#6B899A" />
                </svg>

                <h1>Terima kasih</h1>
                <p>Terima kasih telah belanja di Online Shop kami. Berikut detail pesanan anda</p>
            </div>
            <div class="thanks-label">
                Detail Pesanan
            </div>
            <div class="thanks-detail">
                <table>
                    <tbody>
                        <tr>
                            <td class="labele">No. Invoice</td>
                            <td class="titik">:</td>
                            <td class="valuee"><?php echo $order->invoice_number; ?></td>
                        </tr>
                        <tr>
                            <td class="labele">Nama Penerima</td>
                            <td class="titik">:</td>
                            <td class="valuee"><?php echo $order->customer_name; ?></td>
                        </tr>
                        <tr>
                            <td class="labele">Alamat Penerima</td>
                            <td class="titik">:</td>
                            <td class="valuee">
                                <?php echo $order->customer_address; ?>
                                <br />
                                Kec. <?php echo $order->customer_subdistrict_name; ?>,
                                <?php echo $order->customer_district_type; ?>.
                                <?php echo $order->customer_district_name; ?>
                                <br />
                                <?php echo $order->customer_province_name; ?> - Indonesia
                                <?php echo $order->customer_postal; ?>
                            </td>
                        </tr>
                        <tr>
                            <td class="labele">
                                Metode Pembayaran
                                <div class="labele-info"><?php echo $payment_info; ?></div>
                            </td>
                            <td class="titik">:</td>
                            <td class="valuee"><?php echo $payment; ?></td>
                        </tr>
                        <tr>
                            <td class="labele">Total yang harus di bayar</td>
                            <td class="titik">:</td>
                            <td class="valuee" style="font-weight: bold">Rp.
                                <?php echo number_format($order->total, 0, '.', '.'); ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="thanks-confirm-instruction">
                <?php echo get_option('mes_order_confirmation_instruction', 'Silahkan melakukan pembayaran sesuai dengan nominal dan methode pembayaran yang Anda pilih, Jika sudah jangan lupa klik konfirmasi'); ?>
            </div>
            <div class="thanks-submit">
                <button id="confirm" class="button-bg button-color">KONFIRMASI</button>
            </div>
        </div>
    </div>

    <?php wp_footer(); ?>
</body>

</html>