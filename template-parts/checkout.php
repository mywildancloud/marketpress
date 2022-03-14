<?php
$favicon = get_site_icon_url();
if (empty($favicon)) {
    $favicon = MARKETPRESS_URL . '/images/favicon.png';
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
    <title>Checkout &#8211; <?php echo bloginfo('name'); ?></title>
    <?php wp_head(); ?>
</head>

<body>
    <div id="checkout">
        <div class="wrapper-checkout">
            <div class="checkout-heading hide-if-empty-item">
                <h1>Form Checkout</h1>
                <p>
                    <a href="<?php echo site_url(); ?>">Home</a> / Checkout
                </p>
            </div>
            <div class="checkout-label hide-if-empty-item">
                Rincian Pemesanan
            </div>
            <div class="checkout-add-item hide-if-empty-item">
                kamu ingin menambahkan item lain ke dalam keranjang ? <a style="color: #000000" href="<?php echo get_option('mes_shop_now_link', ''); ?>">tambah item</a>
            </div>
            <div class="checkout-items">
            </div>
            <div class="checkout-label hide-if-empty-item">
                Informasi Pemesan
            </div>
            <div class="checkout-customer-field hide-if-empty-item">
                <div class="customer-field-box">
                    <div class="customer-field">
                        <label>Nama Lengkap :</label>
                        <input type="text" name="name" value="" placeholder="Misal: Jhon Doe" />
                    </div>
                </div>
                <div class="customer-field-box">
                    <div class="customer-field">
                        <label>Nomor Handphone :</label>
                        <input type="number" name="phone" value="" placeholder="Misal: 081234567890" />
                    </div>
                    <div class="customer-field">
                        <label>Email :</label>
                        <input type="email" name="email" value="" placeholder="Misal: name@email.com" />
                    </div>
                </div>
                <div class="customer-field-box">
                    <div class="customer-field">
                        <label>Alamat Lengkap :</label>
                        <textarea name="address" oninput='this.style.height = "";this.style.height = this.scrollHeight + "px"'></textarea>
                    </div>
                </div>
                <div class="customer-field-box">
                    <div class="customer-field">
                        <label>Provinsi :</label>
                        <select id="provinceSelect"></select>
                    </div>
                    <div class="customer-field">
                        <label>Kabupaten/Kota :</label>
                        <select id="districtSelect"></select>
                    </div>
                </div>
                <div class="customer-field-box">
                    <div class="customer-field">
                        <label>Kecamatan :</label>
                        <select id="subdistrictSelect"></select>
                    </div>
                    <div class="customer-field">
                        <label>Kode pos :</label>
                        <input type="text" name="postal" value="" />
                    </div>
                </div>
            </div>
            <?php if (get_option('mes_shipping_enable') == 'yes') : ?>
                <div class="checkout-label hide-if-empty-item">
                    Pengiriman
                </div>
                <div class="checkout-shipping hide-if-empty-item">
                    <?php
                    $provider = get_option('mes_shipping_provider', 'flatshipping');
                    ?>
                    <?php if ($provider == 'flatshipping') {
                        $list = get_option('mes_flatshipping_lists');
                        if ($list) {
                            echo '<div class="shipping shipping-flatshipping clear">';

                            foreach ((array)$list as $l) {
                                echo '<div class="flatshipping" data-name="' . $l['location'] . '" data-cost="' . $l['cost'] . '">';
                                echo '<div class="service-column service-name">' . $l['location'] . '</div>';
                                echo '<div class="service-column service-cost">Rp ' . number_format($l['cost'], 0, '.', '.') . '</div>';
                                echo '<div class="service-column service-radio"><div class="radios"></div></div>';
                                echo '</div>';
                            }
                        }
                    } ?>
                    <?php if ($provider == 'rajaongkir') { ?>
                        <div class="shipping shipping-rajaongkir clear">
                        </div>
                    <?php } ?>
                </div>
            <?php endif; ?>
            <?php if (get_option('mes_payment_enable') == 'yes') : ?>
                <div class="checkout-label hide-if-empty-item">
                    Metode Pembayaran
                </div>
                <div class="checkout-payment hide-if-empty-item">
                    <div class="payment">
                        <div class="payment-detail">
                            <div class="payment-detail-column payment-detail-icon">
                                <svg width="28" height="28" viewBox="0 0 28 28" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M23.3333 5.8335H4.66667C4.02233 5.8335 3.5 6.35583 3.5 7.00016V21.0002C3.5 21.6445 4.02233 22.1668 4.66667 22.1668H23.3333C23.9777 22.1668 24.5 21.6445 24.5 21.0002V7.00016C24.5 6.35583 23.9777 5.8335 23.3333 5.8335Z" stroke="#333333" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" />
                                    <path d="M3.5 12H24.5" stroke="#333333" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" />
                                    <path d="M16.1667 18.4998C16.811 18.4998 17.3333 17.9775 17.3333 17.3332C17.3333 16.6888 16.811 16.1665 16.1667 16.1665C15.5223 16.1665 15 16.6888 15 17.3332C15 17.9775 15.5223 18.4998 16.1667 18.4998Z" fill="#333333" />
                                    <path d="M19.6667 18.4998C20.311 18.4998 20.8333 17.9775 20.8333 17.3332C20.8333 16.6888 20.311 16.1665 19.6667 16.1665C19.0223 16.1665 18.5 16.6888 18.5 17.3332C18.5 17.9775 19.0223 18.4998 19.6667 18.4998Z" fill="#333333" />
                                </svg>

                            </div>
                            <div class="payment-detail-column payment-detail-name">
                                <?php echo get_option('mes_payment_manual_label', 'Bank Transfer'); ?>
                            </div>
                            <div class="payment-detail-column payment-detail-arrow">
                                <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M3 6L7.5 11L12 6" stroke="black" />
                                </svg>
                            </div>
                        </div>
                        <div class="payment-lists">
                            <?php
                            $lists = get_option('mes_payment_manual_lists');
                            foreach ((array)$lists as $key => $val) {
                                $icon = isset($val['icon']) ? $val['icon'] : '';
                                $data_name = get_option('mes_payment_manual_label', 'Bank Transfer') . ' - ' . $val['bank'];

                                echo '<div class="payment-lists-box" data-name="' . $data_name . '" data-key="manual">';
                                echo '<div class="payment-column payment-icon"><img src="' . $icon . '" /></div>';
                                echo '<div class="payment-column payment-name">' . $val['bank'] . '</div>';
                                echo '<div class="payment-column payment-radio"><div class="radios"></div></div>';
                                echo '</div>';
                            }
                            ?>
                        </div>
                    </div>
                    <?php if (get_option('mes_payment_cod_enable') == 'yes') : ?>
                        <div class="payment">
                            <div class="payment-detail">
                                <div class="payment-detail-column payment-detail-icon">
                                    <svg width="28" height="28" viewBox="0 0 28 28" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M23.3333 3.5H4.66667C4.02233 3.5 3.5 4.02233 3.5 4.66667V23.3333C3.5 23.9777 4.02233 24.5 4.66667 24.5H23.3333C23.9777 24.5 24.5 23.9777 24.5 23.3333V4.66667C24.5 4.02233 23.9777 3.5 23.3333 3.5Z" stroke="black" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" />
                                        <path d="M18.6668 3.5H9.3335V14L14.0002 12.8333L18.6668 14V3.5Z" stroke="black" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                </div>
                                <div class="payment-detail-column payment-detail-name">
                                    <?php echo get_option('mes_payment_cod_label', 'COD (Bayar ditempat)'); ?>
                                </div>
                                <div class="payment-detail-column payment-detail-arrow">
                                    <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M3 6L7.5 11L12 6" stroke="black" />
                                    </svg>
                                </div>
                            </div>
                            <div class="payment-lists">
                                <div class="payment-lists-box" data-name="<?php echo get_option('mes_payment_cod_label', 'COD (Bayar ditempat)'); ?>" data-key="cod">
                                    <div class="payment-column payment-desc">
                                        <?php echo get_option('mes_payment_cod_instruction', 'Metode pembayaran di tempat di kenakan biaya admin sebesar 10%'); ?>
                                    </div>
                                    <div class="payment-column payment-radio">
                                        <div class="radios"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
            <div class="checkout-label hide-if-empty-item">
                Kode Promo
            </div>
            <div class="checkout-coupon hide-if-empty-item">
                <div class="couponbox clear">
                    <input type="text" placeholder="Masukan kode promo di sini">
                    <button>Terapkan</button>
                </div>
                <div class="couponbox-notice">Error Mas</div>
            </div>
            <div class="checkout-detail hide-if-empty-item">
            </div>
            <div class="checkout-submit hide-if-empty-item">
                <div id="show-if-cod" class="submit-alert">
                    <?php echo get_option('mes_payment_cod_instruction', 'Metode pembayaran di tempat di kenakan biaya admin sebesar 10%'); ?>
                </div>
                <button id="order" style="background:#61ce70; color: #ffffff;">LANJUT PEMBAYARAN</button>
            </div>
        </div>
    </div>

    <?php wp_footer(); ?>
    <script type="text/template" id="checkout-shipping-rajaongkir">
        <div class="rajaongkir">
            <div class="rajaongkir-detail">
                <div class="rajaongkir-detail-column rajaongkir-detail-icon">
                    {{courier-icon}}
                </div>
                <div class="rajaongkir-detail-column rajaongkir-detail-name">
                    {{courier-name}}
                </div>
                <div class="rajaongkir-detail-column rajaongkir-detail-arrow">
                    <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M3 6L7.5 11L12 6" stroke="black" />
                    </svg>
                </div>
            </div>
            <div class="rajaongkir-services{{classes}}">
            {{services}}
            </div>
        </div>
    </script>
    <script type="text/template" id="checkout-shipping-rajaongkir-service">
        <div class="rajaongkir-services-service" data-name="{{data-name}}" data-cost="{{data-cost}}" data-etd="{{data-etd}}">
            <div class="service-column service-name">
            {{service-name}}
            </div>
            <div class="service-column service-etd">
            {{service-etd}}
            </div>
            <div class="service-column service-cost">
            {{service-cost}}
            </div>
            <div class="service-column service-radio">
                <div class="radios{{classes}}">
                </div>
            </div>
        </div>
    </script>
    <script type="text/template" id="checkout-loader">
        <div class="checkout-loader">
            <svg xmlns:svg="http://www.w3.org/2000/svg" xmlns="http://www.w3.org/2000/svg"
                xmlns:xlink="http://www.w3.org/1999/xlink" version="1.0" width="160px" height="20px"
                viewBox="0 0 128 16" xml:space="preserve">
                <rect x="0" y="0" width="100%" height="100%" fill="#FFFFFF" />
                <path fill="#949494" fill-opacity="0.42"
                    d="M6.4,4.8A3.2,3.2,0,1,1,3.2,8,3.2,3.2,0,0,1,6.4,4.8Zm12.8,0A3.2,3.2,0,1,1,16,8,3.2,3.2,0,0,1,19.2,4.8ZM32,4.8A3.2,3.2,0,1,1,28.8,8,3.2,3.2,0,0,1,32,4.8Zm12.8,0A3.2,3.2,0,1,1,41.6,8,3.2,3.2,0,0,1,44.8,4.8Zm12.8,0A3.2,3.2,0,1,1,54.4,8,3.2,3.2,0,0,1,57.6,4.8Zm12.8,0A3.2,3.2,0,1,1,67.2,8,3.2,3.2,0,0,1,70.4,4.8Zm12.8,0A3.2,3.2,0,1,1,80,8,3.2,3.2,0,0,1,83.2,4.8ZM96,4.8A3.2,3.2,0,1,1,92.8,8,3.2,3.2,0,0,1,96,4.8Zm12.8,0A3.2,3.2,0,1,1,105.6,8,3.2,3.2,0,0,1,108.8,4.8Zm12.8,0A3.2,3.2,0,1,1,118.4,8,3.2,3.2,0,0,1,121.6,4.8Z" />
                <g>
                    <path fill="#000000" fill-opacity="1"
                        d="M-42.7,3.84A4.16,4.16,0,0,1-38.54,8a4.16,4.16,0,0,1-4.16,4.16A4.16,4.16,0,0,1-46.86,8,4.16,4.16,0,0,1-42.7,3.84Zm12.8-.64A4.8,4.8,0,0,1-25.1,8a4.8,4.8,0,0,1-4.8,4.8A4.8,4.8,0,0,1-34.7,8,4.8,4.8,0,0,1-29.9,3.2Zm12.8-.64A5.44,5.44,0,0,1-11.66,8a5.44,5.44,0,0,1-5.44,5.44A5.44,5.44,0,0,1-22.54,8,5.44,5.44,0,0,1-17.1,2.56Z" />
                    <animateTransform attributeName="transform" type="translate"
                        values="23 0;36 0;49 0;62 0;74.5 0;87.5 0;100 0;113 0;125.5 0;138.5 0;151.5 0;164.5 0;178 0"
                        calcMode="discrete" dur="1170ms" repeatCount="indefinite" />
                </g>
            </svg>
            <br />
            Loading data pengiriman
        </div>
    </script>
    <script type="text/template" id="checkout-empty-item-template">
        <div class="empty-item">
        <svg width="284" height="284" viewBox="0 0 284 284" fill="none" xmlns="http://www.w3.org/2000/svg">
<circle cx="144.162" cy="139.888" r="87.8882" fill="#D6F8FF"/>
<path d="M79.3694 143.091C79.1501 140.965 80.8177 139.117 82.9544 139.117H206.154C208.279 139.117 209.943 140.947 209.742 143.063L202.465 219.469C202.289 221.318 200.735 222.731 198.877 222.731H90.8376C88.9903 222.731 87.4422 221.334 87.2526 219.497L79.3694 143.091Z" fill="white" stroke="#6B899A" stroke-width="7.20812"/>
<rect x="65.5938" y="139.117" width="157.137" height="15.8579" rx="3.60406" fill="white" stroke="#6B899A" stroke-width="7.20812"/>
<path d="M177.32 168.67C177.32 186.982 162.475 201.827 144.162 201.827C125.85 201.827 111.005 186.982 111.005 168.67" stroke="#6B899A" stroke-width="7.20812" stroke-linecap="round"/>
<circle cx="227.056" cy="77.1272" r="7.20812" stroke="#DCDCDC" stroke-width="1.44162"/>
<circle cx="25.2283" cy="130.467" r="4.32487" stroke="#DCDCDC" stroke-width="1.44162"/>
<circle cx="180.924" cy="250.122" r="5.7665" stroke="#DCDCDC" stroke-width="1.44162"/>
<path d="M121.907 36.811C122.371 35.5559 124.146 35.5559 124.611 36.811L125.56 39.3752C125.706 39.7698 126.017 40.0809 126.411 40.2269L128.975 41.1758C130.231 41.6402 130.231 43.4154 128.975 43.8798L126.411 44.8286C126.017 44.9747 125.706 45.2858 125.56 45.6804L124.611 48.2445C124.146 49.4996 122.371 49.4996 121.907 48.2445L120.958 45.6804C120.812 45.2858 120.501 44.9747 120.106 44.8286L117.542 43.8798C116.287 43.4154 116.287 41.6402 117.542 41.1758L120.106 40.2269C120.501 40.0809 120.812 39.7698 120.958 39.3752L121.907 36.811Z" fill="#6B899A"/>
<path d="M239.303 193.704C239.553 193.028 240.508 193.028 240.759 193.704L241.269 195.084C241.348 195.297 241.516 195.464 241.728 195.543L243.109 196.054C243.785 196.304 243.785 197.26 243.109 197.51L241.728 198.021C241.516 198.1 241.348 198.267 241.269 198.48L240.759 199.86C240.508 200.536 239.553 200.536 239.303 199.86L238.792 198.48C238.713 198.267 238.545 198.1 238.333 198.021L236.952 197.51C236.276 197.26 236.276 196.304 236.952 196.054L238.333 195.543C238.545 195.464 238.713 195.297 238.792 195.084L239.303 193.704Z" fill="#6B899A"/>
<path d="M43.0332 172.642C43.3548 171.773 44.5837 171.773 44.9053 172.642L45.5621 174.417C45.6632 174.69 45.8786 174.906 46.1518 175.007L47.927 175.664C48.7959 175.985 48.7959 177.214 47.927 177.536L46.1518 178.193C45.8786 178.294 45.6632 178.509 45.5621 178.782L44.9053 180.557C44.5837 181.426 43.3548 181.426 43.0332 180.557L42.3763 178.782C42.2753 178.509 42.0599 178.294 41.7867 178.193L40.0115 177.536C39.1426 177.214 39.1426 175.985 40.0115 175.664L41.7867 175.007C42.0599 174.906 42.2753 174.69 42.3763 174.417L43.0332 172.642Z" fill="#6B899A"/>
<path d="M111.005 126.864C108.362 111.726 95.7236 77.2717 66.3145 60.5488" stroke="#6B899A" stroke-width="2.88325" stroke-linecap="round" stroke-dasharray="5.77 5.77"/>
<path d="M170.111 126.863C172.754 111.726 178.905 93.8498 208.314 77.127" stroke="#6B899A" stroke-width="2.88325" stroke-linecap="round"/>
<path d="M150.649 127.584C151.37 106.2 159.732 60.5486 187.411 49.0156" stroke="#6B899A" stroke-width="2.88325" stroke-linecap="round" stroke-dasharray="5.77 5.77"/>
<path d="M131.908 127.584C132.389 115.09 130.323 85.9211 118.213 69.1982" stroke="#6B899A" stroke-width="2.88325" stroke-linecap="round"/>
</svg>
<br/>

            Ups, keranjang belanja Anda kosong!
            <br />
            <p>
            Anda belum menentukan pilihan. Cari lebih lanjut tentang produk kami dan tambahkan produk yang anda suka ke dalam keranjang ini.
            </p>
            <br/>
            <a class="button button-bg button-color" href="<?php echo get_option('mes_shop_now_link', ''); ?>">Belanja Sekarang</a>
        </div>
    </script>
    <script type="text/template" id="checkout-item-template">
        <div class="item clear" data-id="{{id}}">
            <div class="item-column item-image">
                <div class="item-image-box">
                    <img src="{{image}}" />
                </div>
            </div>
            <div class="item-column item-detail">
                <div class="item-detail-box">
                    <div class="item-detail-name">{{name}}</div>
                    <div class="item-detail-variant">
                        Varian : <span style="color:#EB5757;">{{variation}}</span>
                    </div>
                    <div class="item-detail-variant">
                        Qty : <span style="color:#EB5757;">{{quantity}}</span>
                    </div>
                    <div class="item-detail-price-box">
                        <div class="item-att-price">{{price}}</div>
                        <div class="item-att-price-stik">{{price_stik}}</div>
                    </div>
                </div>
            </div>
            <div class="item-column item-att">
                <div class="item-att-delete flex clear">
                    <svg width="28" height="28" viewBox="0 0 28 28" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M18.6667 8.16667V4.66667C18.6667 4.35725 18.5438 4.0605 18.325 3.84171C18.1062 3.62292 17.8095 3.5 17.5001 3.5H10.5001C10.1907 3.5 9.89392 3.62292 9.67512 3.84171C9.45633 4.0605 9.33341 4.35725 9.33341 4.66667V8.16667M4.66675 8.16667H23.3334H4.66675Z"
                            stroke="#A6B3B7" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" />
                        <path
                            d="M7 8.16675H21V23.3334C21 23.6428 20.8771 23.9396 20.6583 24.1584C20.4395 24.3772 20.1428 24.5001 19.8333 24.5001H8.16667C7.85725 24.5001 7.5605 24.3772 7.34171 24.1584C7.12292 23.9396 7 23.6428 7 23.3334V8.16675Z"
                            stroke="#A6B3B7" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" />
                        <path d="M11.6667 12.8333V19.8333" stroke="#A6B3B7" stroke-width="1.75" stroke-linecap="round"
                            stroke-linejoin="round" />
                        <path d="M16.3333 12.8333V19.8333" stroke="#A6B3B7" stroke-width="1.75" stroke-linecap="round"
                            stroke-linejoin="round" />
                    </svg>

                </div>
            </div>
        </div>
    </script>
    <template id="error-field-template">
        <div class="error-field">
        </div>
    </template>

</body>

</html>