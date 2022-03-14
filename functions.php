<?php

/**
 * MarketPress functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package MarketPress
 */

if (!function_exists('__debug')) :
	function __debug()
	{
		$bt     = debug_backtrace();
		$caller = array_shift($bt);
		$print = array(
			"file"  => $caller["file"],
			"line"  => $caller["line"],
			"args"  => func_get_args()
		);

		echo '<pre>';
		print_r($print);
		echo '</pre>';
	}
endif;

define('MARKETPRESS_VERSION', '2.2.1');
define('MARKETPRESS_PATH', get_template_directory());
define('MARKETPRESS_URL', get_template_directory_uri());

require 'update-checker/plugin-update-checker.php';
$UpdateChecker = Puc_v4_Factory::buildUpdateChecker(
	'https://bitbucket.org/brandmarketers/marketpress',
	__FILE__,
	'marketpress'
);

$UpdateChecker->setAuthentication(array(
	'consumer_key' => 'aXtnJ5GWCKudzYvrSg',
	'consumer_secret' => 'C9KNxJSBEqKCYBQBPDG37Y8AgnAv2VvL',
));


if (!function_exists('marketpress_setup')) :
	/**
	 * Sets up theme defaults and registers support for various WordPress features.
	 *
	 * Note that this function is hooked into the after_setup_theme hook, which
	 * runs before the init hook. The init hook is too late for some features, such
	 * as indicating support for post thumbnails.
	 */
	function marketpress_setup()
	{
		/*
		 * Make theme available for translation.
		 * Translations can be filed in the /languages/ directory.
		 * If you're building a theme based on MarketPress, use a find and replace
		 * to change 'marketpress' to the name of your theme in all the template files.
		 */
		load_theme_textdomain('marketpress', get_template_directory() . '/languages');

		// Add default posts and comments RSS feed links to head.
		add_theme_support('automatic-feed-links');

		/*
		 * Let WordPress manage the document title.
		 * By adding theme support, we declare that this theme does not use a
		 * hard-coded <title> tag in the document head, and expect WordPress to
		 * provide it for us.
		 */
		add_theme_support('title-tag');

		/*
		 * Enable support for Post Thumbnails on posts and pages.
		 *
		 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
		 */
		add_theme_support('post-thumbnails');

		// This theme uses wp_nav_menu() in one location.
		register_nav_menus(
			array(
				'primary' => esc_html__('Primary', 'marketpress'),
				'secondary' => esc_html__('Secondary', 'marketpress'),
				'footer' => esc_html__('Footer Menu', 'marketpress')
			)
		);
	}
endif;

add_action('after_setup_theme', 'marketpress_setup');

/**
 * Enqueue scripts and styles.
 */
function marketpress_scripts()
{
	if (get_post_meta(get_the_ID(), '_elementor_edit_mode', true)) :
		wp_enqueue_script('jquery');
		wp_enqueue_script('wp-embed');
	else :
		wp_dequeue_script('jquery');
		wp_dequeue_script('wp-embed');
	endif;
}
add_action('wp_enqueue_scripts', 'marketpress_scripts');

/**
 * Enqueue admin scriptd
 */
function marketpress_admin_scripts()
{

	wp_enqueue_style('jquery-ui-css', 'https://cdnjs.cloudflare.com/ajax/libs/jquery-datetimepicker/2.5.20/jquery.datetimepicker.css');

	wp_enqueue_media();


	//wp_enqueue_style('marketpress-admin', get_template_directory_uri() . '/css/admin.css', array(), strtotime('now'), 'all');

	wp_enqueue_script('datetimepicker', 'https://cdnjs.cloudflare.com/ajax/libs/jquery-datetimepicker/2.5.20/jquery.datetimepicker.full.min.js', array('jquery'), '4.0.13', true);
	wp_enqueue_script('autocomplete', 'https://cdn.jsdelivr.net/npm/@tarekraafat/autocomplete.js@7.2.0/dist/js/autoComplete.min.js', array(), '7.2.0', false);
	wp_enqueue_script('marketpress-admin', get_template_directory_uri() . '/js/admin.js', array('jquery', 'jquery-ui-datepicker'), strtotime('now'), true);
}
add_action('admin_enqueue_scripts', 'marketpress_admin_scripts');

/**
 * Load styles
 */
function marketpress_css()
{
	echo '<style type="text/css">';
	include(get_template_directory() . '/css/style.min.css');
	echo '</style>';
}
add_action('wp_head', 'marketpress_css', 10);


/**
 * insert head js
 * @return [type] [description]
 */
function marketpress_head_js_script()
{
	$fonts = array(
		get_theme_mod('font_family')
	);
	$font_uri = customizer_library_get_google_font_uri($fonts);
	$font = $font_uri ? '"' . $font_uri . '"' : 'false';



	$phpjs = array(
		'site_url' => site_url(),
		'ajax_url' => admin_url('admin-ajax.php'),
		'nonce' => wp_create_nonce('marketpress'),
		'main_script' => MARKETPRESS_URL . '/js/marketpress.min.js?v=' . marketpress_get_script_version(),
		'regions_source' => MARKETPRESS_URL . '/data/regions/',
		'font_uri' => $font,
		'currency' => 'new Intl.NumberFormat("id-ID", {style: "currency", currency: "IDR",minimumFractionDigits: 0})',
		'shipping_enable' => get_option('mes_shipping_enable'),
		'payment_enable' => get_option('mes_payment_enable'),
		'payment_cod_cost' => get_option('mes_payment_cod_cost', '10'),
		'shipping_zone_province_id' => get_option('mes_shipping_zone_province_id', 99999),
		'shipping_zone_province_name' => get_option('mes_shipping_zone_province_name'),
		'shipping_zone_district_id' => get_option('mes_shipping_zone_district_id', 99999),
		'shipping_zone_district_name' => get_option('mes_shipping_zone_district_name'),
		'shipping_zone_district_type' => get_option('mes_shipping_zone_district_type'),
		'shipping_zone_subdistrict_id' => get_option('mes_shipping_zone_subdistrict_id', 99999),
		'shipping_zone_subdistrict_name' => get_option('mes_shipping_zone_subdistrict_name'),
		'thanks_page' => get_option('mes_thanks_enable', 'yes'),
		'link_wa' => 'https://web.whatsapp.com/send?phone=' . marketpress_get_admin_phone() . '&',
		'shop_now_page' => get_option('mes_shop_now_link', '')
	);

	echo '<script type="text/javascript">';
	echo 'const marketpress = {';
	$items = array();
	foreach ($phpjs as $key => $value) {
		$val = '"' . $value . '"';

		$bools = array(
			'currency',
			'font_uri',
			'payment'
		);

		if (in_array($key, $bools)) {
			$val = $value;
		}
		$items[] = '"' . $key . '" : ' . $val;
	}

	echo implode(',', $items);
	echo '}';
	echo '</script>';
}
add_action('wp_head', 'marketpress_head_js_script', 12);

function marketpress_admin_head()
{
	$phpjs = array(
		'site_url' => site_url(),
		'regions_source' => MARKETPRESS_URL . '/data/regions/',
	);

	echo '<script type="text/javascript">';
	echo 'const marketpress_admin = {';
	$items = array();
	foreach ($phpjs as $key => $value) {
		$val = '"' . $value . '"';

		$bools = array();

		if (in_array($key, $bools)) {
			$val = $value;
		}
		$items[] = '"' . $key . '" : ' . $val;
	}

	echo implode(',', $items);
	echo '}';
	echo '</script>';
}
add_action('admin_head', 'marketpress_admin_head');

/**
 * footer inline js
 * @return [type] [description]
 */
function marketpress_js_script()
{
	$lic = new marketpress_License();
	if ($lic->license == 'valid') {

		ob_start();
		include(MARKETPRESS_PATH . '/js/script.min.js');
		$script = ob_get_contents();
		ob_end_clean();

		echo '<script type="text/javascript">';
		echo $script;
		echo '</script>';
	}
}
add_action('wp_footer', 'marketpress_js_script', 11);


function marketpress_head_js_product()
{
	if (is_singular('marketpress-store')) {

		$variation1 = get_post_meta(get_the_ID(), 'variation1_option', true);
		$variation2 = get_post_meta(get_the_ID(), 'variation2_option', true);
		$weight = get_post_meta(get_the_ID(), 'store_weight', true);
		$regular_price = get_post_meta(get_the_ID(), 'price', true);
		$promo_price = intval(get_post_meta(get_the_ID(), 'promo_price', true));
		$price = ($promo_price) ? $promo_price : $regular_price;
		$price_stik = $regular_price;

		if (empty($weight)) {
			$weight = 1000;
		}
		$cart_key = get_the_ID();
		$product_variation1_value = '';
		$product_variation2_value = '';

		if ($variation1 && isset($variation1[0])) {
			$cart_key .= sanitize_title($variation1[0]);
			$product_variation1_value = sanitize_text_field($variation1[0]);
		}
		if ($variation2 && isset($variation2[0]['text'])) {
			$cart_key .= sanitize_title($variation2[0]['text']);
			$product_variation2_value = sanitize_text_field($variation2[0]['text']);
		}

		$product_images = array();

		if (get_post_thumbnail_id(get_the_ID())) {
			$product_images[] = get_post_thumbnail_id(get_the_ID());
		}

		if (get_post_meta(get_the_ID(), 'product_images', true)) {
			$galeries = get_post_meta(get_the_ID(), 'product_images', true);
			$galery_ids = array_keys($galeries);
			$product_images = array_merge($product_images, $galery_ids);
		}

		$thumb_url = '';

		if ($product_images) {
			$thumb_url = wp_get_attachment_url($product_images[0], 'full');
		}

		$phpjs = array(
			'key' => $cart_key,
			'id' => get_the_ID(),
			'permalink' => get_the_permalink(get_the_ID()),
			'name' => get_the_title(),
			'image' => $thumb_url,
			'price' => $price,
			'price_stik' => $price_stik,
			'quantity' => 1,
			'total' => $price,
			'weight' => $weight,
			'variation_1_label' => get_post_meta(get_the_ID(), 'variation1_label', true),
			'variation_1_value' => $product_variation1_value,
			'variation_2_label' => get_post_meta(get_the_ID(), 'variation2_label', true),
			'variation_2_value' => $product_variation2_value,
			'note' => '',
		);

		echo '<script type="text/javascript">';
		echo 'const marketpress_product = {';
		foreach ($phpjs as $key => $val) {
			$v = '"' . $val . '"';
			$ints = array(
				'id',
				'price',
				'price_stik',
				'quantity',
				'total',
				'weight',
			);
			if (in_array($key, $ints)) {
				$v = $val;
			}
			echo '"' . $key . '":' . $v . ',';
		}
		echo '};';
		echo 'localStorage.setItem("marketpress_product", JSON.stringify(marketpress_product));';
		echo '</script>';
	} else {
		echo '<script type="text/javascript">';
		echo 'localStorage.removeItem("marketpress_product");';
		echo '</script>';
	}
}
add_action('wp_head', 'marketpress_head_js_product', 99);

/**
 * includes script
 */

require MARKETPRESS_PATH . '/inc/license.php';

require MARKETPRESS_PATH . '/inc/customizer-library/customizer-library.php';
require MARKETPRESS_PATH . '/inc/customizer.php';

require MARKETPRESS_PATH . '/inc/cmb2/init.php';
require MARKETPRESS_PATH . '/inc/cmb2-conditionals/cmb2-conditionals.php';

require MARKETPRESS_PATH . '/inc/widget.php';

require MARKETPRESS_PATH . '/inc/class-order.php';

require MARKETPRESS_PATH . '/inc/product.php';
require MARKETPRESS_PATH . '/inc/product-metabox.php';
require MARKETPRESS_PATH . '/inc/product-import.php';

require MARKETPRESS_PATH . '/inc/store.php';
require MARKETPRESS_PATH . '/inc/store-metabox.php';
require MARKETPRESS_PATH . '/inc/store-import.php';

require MARKETPRESS_PATH . '/inc/coupon.php';
require MARKETPRESS_PATH . '/inc/coupon-metabox.php';
require MARKETPRESS_PATH . '/inc/order.php';
require MARKETPRESS_PATH . '/inc/order-metabox.php';
require MARKETPRESS_PATH . '/inc/template-functions.php';
require MARKETPRESS_PATH . '/inc/ecommerce-settings.php';
require MARKETPRESS_PATH . '/inc/ecommerce-settings-shipping.php';
require MARKETPRESS_PATH . '/inc/ecommerce-settings-payment.php';
require MARKETPRESS_PATH . '/inc/ecommerce-settings-order.php';
require MARKETPRESS_PATH . '/inc/shipping.php';
require MARKETPRESS_PATH . '/inc/slider.php';
require MARKETPRESS_PATH . '/inc/slider-metabox.php';


require MARKETPRESS_PATH . '/inc/metabox.php';


/**
 * Optimizie default unused source
 */
remove_action('wp_head', 'feed_links_extra', 3); // Display the links to the extra feeds such as category feeds
remove_action('wp_head', 'feed_links', 2); // Display the links to the general feeds: Post and Comment Feed
remove_action('wp_head', 'rsd_link'); // Display the link to the Really Simple Discovery service endpoint, EditURI link
remove_action('wp_head', 'wlwmanifest_link'); // Display the link to the Windows Live Writer manifest file.
remove_action('wp_head', 'index_rel_link'); // Index link
remove_action('wp_head', 'parent_post_rel_link', 10, 0); // Prev link
remove_action('wp_head', 'start_post_rel_link', 10, 0); // Start link
remove_action('wp_head', 'adjacent_posts_rel_link', 10, 0); // Display relational links for the posts adjacent to the current post.
remove_action('wp_head', 'wp_generator'); // Display the XHTML generator that is generated on the wp_head hook, WP version
remove_action('wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0);
remove_action('wp_head', 'rel_canonical');
remove_action('wp_head', 'wp_shortlink_wp_head', 10, 0);

remove_action('wp_head', 'print_emoji_detection_script', 7);
remove_action('admin_print_scripts', 'print_emoji_detection_script');
remove_action('wp_print_styles', 'print_emoji_styles');
remove_action('admin_print_styles', 'print_emoji_styles');

remove_action('wp_head', 'rest_output_link_wp_head', 10);

// Disable oEmbed Discovery Links
remove_action('wp_head', 'wp_oembed_add_discovery_links', 10);
remove_action('wp_head', 'wp_oembed_add_host_js');

// Disable REST API link in HTTP headers
remove_action('template_redirect', 'rest_output_link_header', 11, 0);

add_filter('show_admin_bar', '__return_false');
