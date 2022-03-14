<?php
add_action('admin_menu',   'marketpress_ecommerce_setting_menu', 99);
function marketpress_ecommerce_setting_menu()
{
    add_submenu_page(
        'marketpress',
        __('Ecommerce Settings', 'marketpress'),
        __('Ecommerce Settings', 'marketpress'),
        'manage_options',
        'marketpress_ecommerce_settings',
        'marketpress_ecommerce_settings'
    );
}

add_action('admin_init', 'marketpress_ecommerce_settings_save_action');
function marketpress_ecommerce_settings_save_action()
{

    if (!isset($_POST['__marketpressecommercesettingsnonce'])) return;

    if (!wp_verify_nonce($_POST['__marketpressecommercesettingsnonce'], 'noncenonce')) return;

    $data = $_POST;

    unset($data['__marketpressecommercesettingsnonce']);

    if (isset($data['marketpress_save_key']) && $data['marketpress_save_key'] == 'flatshipping') {
        if (!isset($data['mes_flatshipping_lists'])) {
            update_option('mes_flatshipping_lists', '');
        }
    }

    if (isset($data['marketpress_save_key']) && $data['marketpress_save_key'] == 'paymentmanual') {
        if (!isset($data['mes_payment_manual_lists'])) {
            update_option('mes_payment_manual_lists', '');
        }
    }

    foreach ((array)$data as $key => $value) {
        $whitelist = array(
            //'mes_flashsale_end_message'
        );


        if (is_array($value) || in_array($key, $whitelist)) :
            update_option($key, $value);
        else :
            update_option($key, wp_kses_post(stripcslashes($value)));
        endif;
    }
}


function marketpress_ecommerce_settings()
{

    $tabs = array(
        'shipping' => __('Shipping', 'marketpress'),
        'payment' => __('Payment', 'marketpress'),
        'admin-setting' => __('Admin Setting', 'marketpress')
    );

    $tabs = apply_filters('marketpress_ecommerce_settings_tabs', $tabs);

    $current_tab = (isset($_GET['tab']) && array_key_exists($_GET['tab'], $tabs)) ? trim($_GET['tab']) : 'shipping';

    $sections = array(
        'general' => 'General',
    );

    $sections = apply_filters('marketpress_ecommerce_settings_tab_section', $sections, $current_tab);

    $current_section = (isset($_GET['section']) && array_key_exists($_GET['section'], $sections)) ? trim($_GET['section']) : 'general';

?>
<style>
.marketpress-ecommerce-settings {
    text-align: left;
}

.marketpress-ecommerce-settings table th {
    width: 150px;
}

.marketpress-ecommerce-settings table td {
    padding: 10px 0;
}

.marketpress-ecommerce-settings-desc {
    margin: 20px 0;
    font-style: italic;
    font-size: 12px;
}

#autoComplete {
    position: relative;
    transition: all 0.4s ease;
    -webkit-transition: all -webkit-transform 0.4s ease;
    text-overflow: ellipsis;
}

#autoComplete_list {
    position: absolute;
    background: #ffffff;
    z-index: 1000;
    padding: 0;
    left: 0;
    right: 0;
    margin-top: 0;
    margin-left: auto;
    margin-right: auto;
    width: 100%;
    transition: all 0.1s ease-in-out;
    -webkit-transition: all -webkit-transform 0.1s ease;
    box-shadow: 0px 1px 10px rgba(0, 0, 0, .09);
}

.autoComplete_result {
    margin: 0.15rem auto;
    padding: 5px 10px;
    width: 100%;
    border: 1px solid rgba(0, 0, 0, .03);
    list-style: none;
    text-align: left;
    font-size: 14px;
    color: #7b7b7b;
    transition: all 0.1s ease-in-out;
    background-color: #fff;
}

.autoComplete_result::selection {
    color: rgba(#fff, 0);
    background-color: rgba(#fff, 0);
}

.autoComplete_result:last-child {
    border-radius: 0 0 3px 3px;
}

.autoComplete_result:hover {
    cursor: pointer;
    background-color: rgba(255, 248, 248, 0.9);
    border: 1px solid #000000;
}

.autoComplete_result:focus {
    outline: 0;
    background-color: rgba(255, 248, 248, 0.9);
    border: 1px solid #000000;
}

.autoComplete_highlighted {
    opacity: 1;
    color: #000000;
    font-weight: 700;
}

.autoComplete_highlighted::selection {
    color: rgba(#fff, 0);
    background-color: rgba(#fff, 0);
}

.autoComplete_selected {
    cursor: pointer;
    background-color: rgba(255, 248, 248, 0.9);
    border: 1px solid #000000;
}

.kurir {
    position: relative;
    display: inline-block;
    margin-right: 10px;
    margin-bottom: 10px;
    background: #ffffff;
    border: 1px solid rgba(0, 0, 0, .09);
    padding: 10px;
}
</style>
<div class="wrap">
    <h1 class="wp-heading-inline"><?php echo esc_html(__('Ecommerce Settings', 'marketpress')); ?></h1>

    <h2 class="nav-tab-wrapper wp-clearfix">
        <?php foreach ($tabs as $url => $title) : ?>
        <a href="<?php echo add_query_arg('tab', $url); ?>"
            class="nav-tab <?php echo ($current_tab === $url) ? 'nav-tab-active' : '' ?>">
            <?php echo $title ?>
        </a>
        <?php endforeach; ?>
    </h2>

    <div class="wp-clearfix" style="position:relative;width: 100%;margin-bottom: 10px;">
        <ul class="subsubsub">
            <?php foreach ($sections as $key => $value) : ?>
            <li>
                <a href="<?php echo add_query_arg('section', $key); ?>"
                    <?php echo ($current_section === $key) ? 'class="current"' : ''; ?>><?php echo $value; ?></a> |
            </li>
            <?php endforeach; ?>
        </ul>
    </div>

    <hr class="wp-header-end">
    <div class="marketpress-ecommerce-settings" style="text-align: left">
        <form action="" method="post" enctype="multipart/form-data">
            <?php wp_nonce_field('noncenonce', '__marketpressecommercesettingsnonce', false); ?>
            <?php do_action('marketpress_ecommerce_settings_option_page_' . $current_tab . '_' . $current_section); ?>
        </form>
    </div>
</div>
<?php
}

add_filter('marketpress_ecommerce_settings_tab_section', 'marketpress_tab_section', 10, 2);
function marketpress_tab_section($sections, $current_tab)
{
    if ($current_tab == 'shipping') :
        $sections['rajaongkir'] = 'Raja Ongkir API';
        $sections['flatshipping'] = 'Flat Shpping';
        $sections = apply_filters('marketpress_ecommerce_settings_tab_section_shipping', $sections);
    endif;

    if ($current_tab == 'payment') :
        $sections['manual'] = 'Bank Transfer';
        $sections['cod'] = 'COD (Bayar ditempat)';
        $sections = apply_filters('marketpress_ecommerce_settings_tab_section_payment', $sections);
    endif;

    if ($current_tab == 'admin-setting') {
        $sections['flashsale'] = 'Flashsale';
        $sections = apply_filters('marketpress_ecommerce_settings_tab_section_admin_setting', $sections);
    }

    return $sections;
}