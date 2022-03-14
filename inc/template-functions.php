<?php

/**
 * Functions which enhance the theme by hooking into WordPress
 *
 * @package MarketPress
 */

/**
 * Add a pingback url auto-discovery header for single posts, pages, or attachments.
 */
function marketpress_pingback_header()
{
	if (is_singular() && pings_open()) {
		printf('<link rel="pingback" href="%s">', esc_url(get_bloginfo('pingback_url')));
	}
}
add_action('wp_head', 'marketpress_pingback_header');

/**
 * insert custom style
 * @return [type] [description]
 */
function marketpress_css_script()
{
	$css = '';
	$layout_width = get_theme_mod('layout_width');
	if ($layout_width) {
		$css .= '.wrapper{max-width:' . $layout_width . 'px}';
	}
	$link_color = get_theme_mod('link_color');
	$link_hover_color = get_theme_mod('link_hover_color');
	$link_visited_color = get_theme_mod('link_visited_color');

	if ($link_color) {
		$css .= 'a{color:' . $link_color . '}';
	}
	if ($link_hover_color) {
		$css .= 'a:hover, a:focus, a:active{color:' . $link_hover_color . '}';
	}
	if ($link_visited_color) {
		$css .= 'a:visited{color:' . $link_visited_color . '}';
	}
	$body = array();
	$body_background = get_theme_mod('body_background', '#f4f4f4');
	if ($body_background) {
		$body[] = 'background:' . $body_background . ';';
	}
	$font_family = get_theme_mod('font_family');
	if ($font_family) {
		$body[] = 'font-family:' . $font_family . ';';
	}
	if ($body) {
		$css .= 'body{' . implode(' ', $body) . '}';
	}
	$header_background = get_theme_mod('header_background', '#ffffff');
	if ($header_background) {
		$css .= '.header{background:' . $header_background . '}';
		$css .= '.primary-menu ul li.menu-item-has-children ul.sub-menu{background:' . $header_background . '}';
		$css .= '.secondary-menu{background:' . $header_background . '}';
		$css .= '.secondary-menu ul:before{border-bottom:10px solid ' . $header_background . '}';
		$css .= '.primary-menu{background:' . $header_background . '}';
	}
	$header_color = get_theme_mod('header_color', '#1F3346');
	if ($header_color) {
		$css .= '.primary-menu-toggle span{background:' . $header_color . '}';
		$css .= '.logo-4section h1, .logo-3section h1, .logo-store h1{color:' . $header_color . '}';
		$css .= '.primary-menu ul li a{color:' . $header_color . '}';
		$css .= '.primary-menu ul li.menu-item-has-children:after{border-color: ' . $header_color . '}';
		$css .= '.search-toggle i{color:' . $header_color . '}';
		$css .= '.secondary-menu-toggle .fill{fill:' . $header_color . '}';
		$css .= '.primary-menu ul li a:hover{border-color:' . $header_color . '}';
		$css .= '.secondary-menu ul li a{color:' . $header_color . '}';
		$css .= '.secondary-menu ul li a:hover{border-color:' . $header_color . '}';
		$css .= '.basket-menu-toggle .fill {color: ' . $header_color . ';}';
	}

	$button_color = get_theme_mod('button_color', '#ffffff');
	$css .= '.button-color, .button-color:visited{color:' . $button_color . ' !important}';
	$css .= '.slide-category ul li a h4{color:' . $button_color . ' !important}';

	$button_bg = get_theme_mod('button_bg', '#39499c');
	$css .= '.button-bg{background:' . $button_bg . ' !important}';
	$css .= '.slide-category ul li a h4{color:' . $button_bg . ' !important}';
	$css .= '.button-second{border: 1px solid ' . $button_bg . ' !important;color:' . $button_bg . ' !important}';
	$css .= '.splides ul.splide__pagination li button.is-active{background: ' . $button_bg . ' !important}';
	$css .= '.galeries .galerysmall::-webkit-scrollbar-thumb {background: ' . $button_bg . '}.galeries .galerysmall::-webkit-scrollbar-thumb:hover {background: ' . $button_bg . '}';
	$css .= '.variation label.variation-radio input:checked ~ .marked {border: 1px solid ' . $button_bg . ';color: ' . $button_bg . ';}';


	echo '<style type="text/css">' . $css . '</style>';
}
add_action('wp_head', 'marketpress_css_script', 11);

/**
 * get script and styles version
 */
function marketpress_get_script_version()
{
	$version = MARKETPRESS_VERSION;
	$current_domain = home_url();
	$dev_domain = array(
		'http://marketpress.loc'
	);

	if (in_array($current_domain, $dev_domain)) {
		$version = strtotime('now');
	}

	return $version;
}

function marketpress_facebook_pixel_tracking()
{
	$pixels = array();

	$pixel_id_1 = get_theme_mod('fb_pixel_id_1');
	if ($pixel_id_1) {
		$pixels[] = $pixel_id_1;
	}
	$pixel_id_2 = get_theme_mod('fb_pixel_id_2');
	if ($pixel_id_2) {
		$pixels[] = $pixel_id_2;
	}
	$pixel_id_3 = get_theme_mod('fb_pixel_id_3');
	if ($pixel_id_3) {
		$pixels[] = $pixel_id_3;
	}
	$pixel_id_4 = get_theme_mod('fb_pixel_id_4');
	if ($pixel_id_4) {
		$pixels[] = $pixel_id_4;
	}
	$pixel_id_5 = get_theme_mod('fb_pixel_id_5');
	if ($pixel_id_5) {
		$pixels[] = $pixel_id_5;
	}

	$pixel_ids = get_post_meta(get_the_ID(), 'facebook_pixel_ids', true);
	$pixel_ids = (array) $pixel_ids;

	$pixels = array_merge($pixels, $pixel_ids);

	if (empty($pixels)) return;

	$pixel_script = array();
	$pixel_noscript = array();

	foreach ((array) $pixels as $pixel) {

		if (empty($pixel)) continue;

		$pixel_script[] = 'fbq("init", "' . $pixel . '");';
		$pixel_noscript[] = '<img height="1" width="1" style="display:none" src="https://www.facebook.com/tr?id=' . $pixel . '&ev=PageView&noscript=1" />';
	}
	$code = '';
	if (empty($pixel_script)) return;
	ob_start();
?>
	<!-- Facebook Pixel Code -->
	<script>
		! function(f, b, e, v, n, t, s) {
			if (f.fbq) return;
			n = f.fbq = function() {
				n.callMethod ?
					n.callMethod.apply(n, arguments) : n.queue.push(arguments)
			};
			if (!f._fbq) f._fbq = n;
			n.push = n;
			n.loaded = !0;
			n.version = '2.0';
			n.queue = [];
			t = b.createElement(e);
			t.async = !0;
			t.src = v;
			s = b.getElementsByTagName(e)[0];
			s.parentNode.insertBefore(t, s)
		}(window, document, 'script',
			'https://connect.facebook.net/en_US/fbevents.js');
	</script>
	<?php echo '<script>' . implode(' ', $pixel_script) . 'fbq("track", "PageView");</script>'; ?>
	<noscript>
		<?php echo implode(' ', $pixel_noscript); ?>
	</noscript>
	<!-- End Facebook Pixel Code -->
<?php
	$code = ob_get_contents();
	ob_end_clean();

	echo $code;
}
add_action('wp_footer', 'marketpress_facebook_pixel_tracking', 98);

/**
 * handle custom script on head
 * @return [type] [description]
 */
function marketpress_head_custom_script()
{
	$code = get_theme_mod('custom_script_head');
	$singular_code = get_post_meta(get_the_ID(), 'head_script_code', true);

	if (!$code && !$singular_code) return;

	echo $code . $singular_code;
}
add_action('wp_head', 'marketpress_head_custom_script', 99);

/**
 * handle custom script on footer
 * @return [type] [description]
 */
function marketpress_footer_custom_script()
{
	$code = get_theme_mod('custom_script_footer');
	$singular_code = get_post_meta(get_the_ID(), 'footer_script_code', true);

	if (!$code && !$singular_code) return;

	echo $code . $singular_code;
}
add_action('wp_footer', 'marketpress_footer_custom_script', 99);

/**
 * handle hidden affiliate link
 */
function marketpress_footer_hidden_affiliate_link()
{
	$link = get_post_meta(get_the_ID(), 'hidden_affiliate_link', true);
	$tag = get_post_meta(get_the_ID(), 'hidden_affiliate_link_tag', true);


	if (!$link) return;

	if ($tag == 'iframe') {
		echo  '<iframe src="' . $link . '" style="width: 1px;height: 1px;opacity:0;display:none"></iframne>';
	} else {
		echo  '<img src="' . $link . '" style="width: 1px;height: 1px;opacity:0;display:none">';
	}
}
add_action('wp_footer', 'marketpress_footer_hidden_affiliate_link', 99);

/**
 * Displays an optional post thumbnail.
 *
 * Wraps the post thumbnail in an anchor element on index views, or a div
 * element when on single views.
 */
function marketpress_post_thumbnail()
{
	if (post_password_required() || is_attachment() || !has_post_thumbnail()) {
		return;
	}

	$attachment_id = get_post_thumbnail_id();

	$image = wp_get_attachment_image_src($attachment_id, 'full', false);
	if (!$image) return;

	$attr = array();

	list($src, $width, $height) = $image;

	$default_attr = array(
		'src'   => '',
		'data-src' => $src,
		'class' => 'lazy',
		'alt'   => trim(strip_tags(get_post_meta($attachment_id, '_wp_attachment_image_alt', true))),
		'width' => '100%',
		'height' => 'auto',
	);

	$attr = wp_parse_args($attr, $default_attr);

	$image_meta = wp_get_attachment_metadata($attachment_id);
	if (is_array($image_meta)) {
		$size_array = array(absint($width), absint($height));
		$srcset     = wp_calculate_image_srcset($size_array, $src, $image_meta, $attachment_id);
		$sizes      = wp_calculate_image_sizes($size_array, $src, $image_meta, $attachment_id);

		if ($srcset && ($sizes || !empty($attr['sizes']))) {
			$attr['srcset'] = $srcset;

			if (empty($attr['sizes'])) {
				$attr['sizes'] = $sizes;
			}
		}
	}

	$attr = array_map('esc_attr', $attr);
	$html = rtrim('<img');
	foreach ($attr as $name => $value) {
		$html .= " $name=" . '"' . $value . '"';
	}
	$html .= ' />';

	echo $html;
}

/**
 * modify content output
 * @param  string $content [description]
 * @return mixed          [description]
 */
function marketpress_the_content($content)
{

	if (is_feed() || is_search() || is_archive()) {
		return $content;
	}


	$img_pattern = '/<img\s+[^>]*>/si';

	$iframe_pattern = '/<iframe.*?s*src="(.*?)".*?<\/iframe>/si';

	$blockquote_pattern = '/(<blockquote)(.*)(<\/blockquote>)/m';

	$content = preg_replace_callback($img_pattern, 'marketpress_content_img', $content);

	$content = preg_replace_callback($iframe_pattern, 'marketpress_content_iframe', $content);

	if (is_admin()) {
		return $content;
	}

	if (is_singular('marketpress-product')) {
		return $content;
	}

	if (is_single()) {

		$content = preg_replace_callback($blockquote_pattern, 'marketpress_content_saved_blockquote', $content);

		$content = marketpress_content_related($content);
		$content = marketpress_content_ads($content);
		$content = marketpress_content_toc($content);

		$content = preg_replace_callback($blockquote_pattern, 'marketpress_content_return_blockquote', $content);
	}

	return $content;
}
add_filter('the_content', 'marketpress_the_content', 9999);

/**
 * skip blockquote from count paragrapf
 */
function marketpress_content_saved_blockquote($blockquote)
{
	$blockquote = $blockquote[0];
	$blockquote = str_replace('</p>', '</pi>', $blockquote);
	return $blockquote;
}

/**
 * return blocquote to original blockquote
 */
function marketpress_content_return_blockquote($blockquote)
{
	$blockquote = $blockquote[0];
	$blockquote = str_replace('</pi>', '</p>', $blockquote);
	return $blockquote;
}

/**
 * show ads on content
 * @param string $content [description]
 * @return [type] [description]
 */
function marketpress_content_ads($content)
{

	$ad1 = get_theme_mod('ad_content_on_content1_code');
	$ad1_location = get_theme_mod('ad_content_on_content1_location', 2);

	$ad2 = get_theme_mod('ad_content_on_content2_code');
	$ad2_location = get_theme_mod('ad_content_on_content2_location', 4);

	$ad3 = get_theme_mod('ad_content_on_content3_code');
	$ad3_location = get_theme_mod('ad_content_on_content3_location', 6);

	$ad4 = get_theme_mod('ad_content_on_content4_code');
	$ad4_location = get_theme_mod('ad_content_on_content4_location', 8);

	if (empty($ad1) && empty($ad2) && empty($ad3) && empty($ad4)) return $content;


	$closing_p = '</p>';
	$paragraphs = explode($closing_p, $content);
	foreach ($paragraphs as $index => $paragraph) {

		if (trim($paragraph)) {
			$paragraphs[$index] .= $closing_p;
		}

		if ($ad1 && $ad1_location) {
			if (intval($ad1_location) == $index + 1) {
				$paragraphs[$index] .= '<div class="ads">' . $ad1 . '</div>';
			}
		}

		if ($ad2 && $ad2_location) {
			if (intval($ad2_location) == $index + 1) {
				$paragraphs[$index] .= '<div class="ads">' . $ad2 . '</div>';
			}
		}

		if ($ad3 && $ad3_location) {
			if (intval($ad3_location) == $index + 1) {
				$paragraphs[$index] .= '<div class="ads">' . $ad3 . '</div>';
			}
		}

		if ($ad4 && $ad4_location) {
			if (intval($ad4_location) == $index + 1) {
				$paragraphs[$index] .= '<div class="ads">' . $ad4 . '</div>';
			}
		}
	}

	return implode('', $paragraphs);
}

/**
 * caching related post expired every 6 hours
 */
function marketpress_set_related_cache()
{

	global $related_post;

	if ('post' != get_post_type()) return;

	if (get_post_meta(get_the_ID(), '_related_cache_expired', true) < strtotime('now')) :
		$args = array(
			'post__not_in' =>
			array(get_the_ID()),
			'posts_per_page' => 30,
			'ignore_sticky_posts' => 1,
			'post_type' => 'post',
		);

		$categories = get_the_category(get_the_ID());

		if ($categories) :
			$category_ids = array();
			foreach ($categories as $category) :
				$category_ids[] = $category->term_id;
			endforeach;

			if ($category_ids) :
				$args['category__in'] = $category_ids;
			endif;
		endif;

		$query_related = get_posts($args);
		$relateds = array();

		foreach ($query_related as $related) :
			$relateds[] = (object) array(
				'ID' => $related->ID,
				'title' => $related->post_title,
				'permalink' => get_the_permalink($related->ID),
				'thumbnail_url' => get_the_post_thumbnail_url($related->ID)
			);
		endforeach;

		update_post_meta(get_the_ID(), '_related_cache', $relateds);
		update_post_meta(get_the_ID(), '_related_cache_expired', strtotime('+6 hours'));

	endif;

	$related_post = get_post_meta(get_the_ID(), '_related_cache', true);
}
add_action('wp', 'marketpress_set_related_cache');

/**
 * inline post related
 * @param string $content [description]
 * @return [type] [description]
 */
function marketpress_content_related($content)
{

	global $related_post;

	if (!get_theme_mod('enable_post_inline_related', 1)) return $content;

	if (!$related_post || !isset($related_post[0])) return $content;

	ob_start();
?>
	<div class="related">
		<div class="label"><?php _e('Related Post', 'marketpress'); ?> : </div>
		<ul>
			<li>>> <a href="<?php echo $related_post[0]->permalink; ?>" target="_blank"><?php echo $related_post[0]->title; ?></a></li>
			<?php if (isset($related_post[1])) : ?>
				<li>>> <a href="<?php echo $related_post[1]->permalink; ?>" target="_blank"><?php echo $related_post[1]->title; ?></a></li>
			<?php endif; ?>
		</ul>
	</div>
	<?php
	$related = ob_get_contents();
	ob_end_clean();

	ob_start();

	if (isset($related_post[2])) :
	?>
		<div class="related clear">
			<div class="label"><?php _e('Related Post', 'marketpress'); ?> : </div>
			<ul>
				<?php $i = 0; ?>
				<?php foreach ((array)$related_post as $key => $rel) : ?>
					<?php if ($key == 0 || $key == 1) continue; ?>
					<?php if ($i == 2) break; ?>
					<li>>> <a href="<?php echo $rel->permalink; ?>" target="_blank"><?php echo $rel->title; ?></a></li>
					<?php $i++; ?>
				<?php endforeach; ?>
			</ul>
		</div>
	<?php
	endif;
	$related_2 = ob_get_contents();
	ob_end_clean();

	$alphabet_count = strlen(wp_strip_all_tags(strip_shortcodes($content)));

	$related_place = $alphabet_count / 3;

	$related_place_2 = $related_place * 2;

	$closing_p = '</p>';
	$paragraphs = explode($closing_p, $content);

	$count = 0;
	$insert = false;
	$insert_2 = false;

	foreach ($paragraphs as $index => $paragraph) {

		$count += intval(strlen(wp_strip_all_tags(strip_shortcodes($paragraph))));

		if (trim($paragraph)) {
			$paragraphs[$index] .= $closing_p;
		}

		if (!$insert && $count > $related_place) {
			$paragraphs[$index] .= $related;
			$insert = true;
		}

		if (!$insert_2 && $count > $related_place_2) {
			$paragraphs[$index] .= $related_2;
			$insert_2 = true;
		}
	}

	return implode('', $paragraphs);
}

/**
 * make image on content ready for lazyload
 * @param  [type] $img [description]
 * @return [type]      [description]
 */
function marketpress_content_img($img)
{

	//preg_match_all('/(\w+)=["\']([a-zA-Z0-9-\/_.:\'"]+)["\']/', $img_tag, $matches, PREG_SET_ORDER, 0);

	$img_tag = isset($img[0]) ? $img[0] : '';

	if (!$img_tag) return $img_tag;

	if (get_post_meta(get_the_ID(), '_elementor_edit_mode', true)) {
		return $img_tag;
	}

	$noscript = '<noscript>' . $img_tag . '</noscript>';

	preg_match('/class/', $img_tag, $match);
	if (empty($match)) {
		$img_tag = str_replace('<img', '<img class="lazy"', $img_tag);
	}

	preg_match('/lazy/', $img_tag, $match);
	if (empty($match)) {
		$img_tag = str_replace('class="', 'class="lazy ', $img_tag);
	}

	preg_match('/data-src="/', $img_tag, $match);
	if (empty($match)) {
		$img_tag = str_replace(' src="', ' src="' . MARKETPRESS_URL . '/images/loadere.gif" data-src="', $img_tag);
	}

	return $img_tag . $noscript;
}

/**
 * make iframe on content ready for lazyload
 * @param  [type] $iframe [description]
 * @return [type]         [description]
 */
function marketpress_content_iframe($iframe)
{

	$iframe_tag = isset($iframe[0]) ? $iframe[0] : '';

	if (empty($iframe_tag)) return $iframe_tag;

	$src   = isset($iframe[1]) ? $iframe[1] : '';

	preg_match('/youtu/', $src, $match);
	if ($match) {
		$parts = parse_url($src);
		if (isset($parts['path'])) {
			$path = explode('/', trim($parts['path'], '/'));
			$youtube_id = $path[count($path) - 1];

			$iframe_tag = str_replace('></iframe>', ' poster="https://i3.ytimg.com/vi/' . $youtube_id . '/hqdefault.jpg"></iframe>', $iframe_tag);
		}
	}

	preg_match('/class/', $iframe_tag, $match);
	if (empty($match)) {
		$iframe_tag = str_replace('<iframe', '<iframe class="lazy"', $iframe_tag);
	}

	preg_match('/lazy/', $iframe_tag, $match);
	if (empty($match)) {
		$iframe_tag = str_replace('class="', 'class="lazy ', $iframe_tag);
	}

	preg_match('/data-src="/', $iframe_tag, $match);
	if (empty($match)) {
		$iframe_tag = str_replace(' src="', ' data-src="', $iframe_tag);
	}
	return $iframe_tag;
}

/**
 * auto table of content
 * @param  [type] $content [description]
 * @return [string]          [description]
 */
function marketpress_content_toc($content)
{

	if (!get_theme_mod('enable_post_toc', 1)) return $content;

	$display = get_theme_mod('post_toc_display', 'opened');
	$show = '';
	$button_text = __('Close', 'marketpress');
	$button_class = '';

	if ($display == 'closed') {
		$show = 'hide';
		$button_text = __('Open', 'marketpress');
		$button_class = 'close';
	}

	if (empty($content)) {
		$content = get_the_content();
	}
	//preg_match_all( '|<h[^>]+>(.*)</h[^>]+>|iU', $content, $matches, PREG_SET_ORDER );
	preg_match_all('/\<(h[2-3].*?)>(.*)<\/h[2-3]>/i', $content, $match_headings, PREG_SET_ORDER);

	if (!$match_headings) return $content;

	$h = array();
	$key = 'null';

	$matches = array();
	$replacements = array();

	foreach ((array)$match_headings as $heading) {

		if (preg_match('/h2/', $heading[1], $ketemu)) {
			$th = array();
			$th = $heading;
			$key = sanitize_title_with_dashes($heading[2]);
			$h[$key] = $heading;
			$h[$key]['child'] = array();
		} else {
			$h[$key]['child'][] = $heading;
		}

		$matches[]   = $heading[0];
		$id          = sanitize_title_with_dashes($heading[2]);
		$replacements[] = sprintf('<%1$s id="%2$s">%3$s</%1$s>', $heading[1], $id, $heading[2]);
	}

	$content = str_replace($matches, $replacements, $content);

	$tocc = '<div class="tocbox"><div class="toc">';
	$tocc .= '<div class="toc-label">' . __('Table Of Content', 'marketpress') . ' [ <a id="toc-toggle" class="' . $button_class . '">' . $button_text . '</a> ]</div>';
	$tocc .= '<ul id="toc-list" class="toc-list ' . $show . '">';
	$i = 1;
	foreach ((array) $h as $key => $val) {
		if ($key == 'null') {
			if (!isset($val['child'])) continue;

			$tocc .= '<li><ul>';
			$ii = 1;
			foreach ((array) $val['child'] as $item) {
				$tocc .= '<li><a href="#' . sanitize_title_with_dashes($item[2]) . '">' . '0.' . $ii . ' ' . $item[2] . '</a></li>';
				$ii++;
			}
			$tocc .= '</ul></li>';
		} else {
			$tocc .= '<li><a href="#' . sanitize_title_with_dashes($val[2]) . '">' . $i . '. ' . $val[2] . '</a>';
			$tocc .= $val['child'] ? '<ul>' : '';
			$ii = 1;
			foreach ($val['child'] as $item) {
				$tocc .= '<li><a href="#' . sanitize_title_with_dashes($item[2]) . '">' . $i . '.' . $ii . ' ' . $item[2] . '</a></li>';
				$ii++;
			}
			$tocc .= $val['child'] ? '</ul>' : '';

			$i++;
		}
	}
	$tocc .= '</ul></div></div>';

	$position = get_theme_mod('post_toc_position');

	if (!$position) {
		return $tocc . $content;
	}

	$closing_p = '</p>';
	$paragraphs = explode($closing_p, $content);
	foreach ($paragraphs as $index => $paragraph) {

		if (trim($paragraph)) {
			$paragraphs[$index] .= $closing_p;
		}

		if (intval($position) == $index + 1) {
			$paragraphs[$index] .= $tocc;
		}
	}

	return implode('', $paragraphs);
}

/**
 * post related
 * @return [type] [description]
 */
function marketpress_post_related()
{
	global $related_post;

	if ('post' != get_post_type()) return;

	if (!$related_post) return;

	$count = count($related_post);

	$layout_width = intval(get_theme_mod('layout_width'));

	$default = $layout_width < 900 &&  $layout_width > 600 ? 2 : 3;

	$limit = $count > $default ? $default : $count;

	$rands = array_rand($related_post, $limit);

	if (!$rands) return;

	?>
	<div class="related-posts">
		<div class="labele"><?php _e('Related Post', 'marketpress'); ?> : </div>
		<ul class="horizontal-query-posts">
			<?php
			foreach ((array) $rands as $key => $post_id) :

				$thumbnail_url = $related_post[$post_id]->thumbnail_url;

				echo '<li><a href="' . $related_post[$post_id]->permalink . '" title="' . $related_post[$post_id]->title . '">';
				echo '<div class="latest-post-box">';
				echo '<div class="latest-post-thumbnail image-thumbnail">';
				echo '<img data-src="' . $thumbnail_url . '" class="lazy">';
				echo '</div>';
				echo '<div class="latest-post-content">';
				echo '<h4>' . $related_post[$post_id]->title . '</h4>';
				echo '<div class="date">' . get_the_date() . '</div>';
				echo '</div>';
				echo '</a></li>';

			endforeach; ?>
		</ul>
	</div>
<?php
}

/**
 * add next classes for next post navigation
 */
add_filter('next_posts_link_attributes', 'marketpress_next_posts_link');
function marketpress_next_posts_link()
{
	return 'class="next"';
}

/**
 * add prev classes forprevieous post navigation
 */
add_filter('previous_posts_link_attributes', 'marketpress_prev_posts_link');
function marketpress_prev_posts_link()
{
	return 'class="prev"';
}

/**
 * product navigation
 */
function marketpress_products_pagination()
{

	ob_start();
	posts_nav_link(' ', __('Previous Page', 'marketpress'), __('Next Page', 'marketpress'));
	$nav_link = ob_get_contents();
	ob_end_clean();

	if (strlen($nav_link) > 0) :
		echo '<div class="loop-navigation hide clear">';
		echo $nav_link;
		echo '</div>';
	endif;
}

/**
 * post pagination
 */
function marketpress_posts_pagination($pages = '', $range = 1, $query = false)
{
	global $wp_query, $paged;

	if ($query) {
		$wp_query = $query;
	}

	$showitems = ($range * 2) + 1;     // This is the items range, that we can pass it as parameter depending on your necessary.

	if (empty($paged)) $paged = 1;

	if ($pages == '') {   // paged is not defined than its first page. just assign it first page.
		$pages = $wp_query->max_num_pages;
		if (!$pages)
			$pages = 1;
	}

	if (1 != $pages) { //For other pages, make the pagination work on other page queries
		echo '<div class="pagination">';
		//posts_nav_link(' ', __('Previous Page', 'pngtree'), __('Next Page', 'pngtree'));
		//if($paged > 2 && $paged > $range+1 && $showitems < $pages) echo "<a href='".get_pagenum_link(1)."'>&laquo;</a>";
		if ($paged > 1 && $showitems < $pages) echo '<a href="' . get_pagenum_link($paged - 1) . '" class="prevnextlink"><<</a>';

		for ($i = 1; $i <= $pages; $i++) {
			if (1 != $pages && (!($i >= $paged + $range + 1 || $i <= $paged - $range - 1) || $pages <= $showitems))
				echo ($paged == $i) ? "<span class='current'>" . $i . "</span>" : "<a href='" . get_pagenum_link($i) . "' class='inactive' >" . $i . "</a>";
		}

		if ($paged < $pages && $showitems < $pages) echo '<a href="' . get_pagenum_link($paged + 1) . '" class="prevnextlink">>></a>';
		//if ($paged < $pages-1 &&  $paged+$range-1 < $pages && $showitems < $pages) echo "<a href='".get_pagenum_link($pages)."'>&raquo;</a>";
		echo '</div>';
	}
}

/**
 * loader animation
 */
function marketpress_animation_loader()
{
	ob_start();
?>
	<div class="loader">
		<svg xmlns:svg="http://www.w3.org/2000/svg" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.0" width="53px" height="53px" viewBox="0 0 128 128" xml:space="preserve">
			<g>
				<circle cx="16" cy="64" r="16" fill="#8c888c" fill-opacity="1" />
				<circle cx="16" cy="64" r="16" fill="#b2b0b2" fill-opacity="0.67" transform="rotate(45,64,64)" />
				<circle cx="16" cy="64" r="16" fill="#cfcdcf" fill-opacity="0.42" transform="rotate(90,64,64)" />
				<circle cx="16" cy="64" r="16" fill="#e8e7e8" fill-opacity="0.2" transform="rotate(135,64,64)" />
				<circle cx="16" cy="64" r="16" fill="#f1f1f1" fill-opacity="0.12" transform="rotate(180,64,64)" />
				<circle cx="16" cy="64" r="16" fill="#f1f1f1" fill-opacity="0.12" transform="rotate(225,64,64)" />
				<circle cx="16" cy="64" r="16" fill="#f1f1f1" fill-opacity="0.12" transform="rotate(270,64,64)" />
				<circle cx="16" cy="64" r="16" fill="#f1f1f1" fill-opacity="0.12" transform="rotate(315,64,64)" />
				<animateTransform attributeName="transform" type="rotate" values="0 64 64;315 64 64;270 64 64;225 64 64;180 64 64;135 64 64;90 64 64;45 64 64" calcMode="discrete" dur="720ms" repeatCount="indefinite"></animateTransform>
			</g>
		</svg>
	</div>
	<div class="loadmore">
		<button id="loadmore">Muat lebih banyak</button>
	</div>
<?php
	$content = ob_get_contents();
	ob_end_clean();

	echo $content;
}

/**
 * Product select short
 */
function marketpress_product_category_list()
{
	$terms = get_terms([
		'taxonomy' => 'marketpress-product-category'
	]);

	$page_object = get_queried_object();
	$term_id = 0;
	$selected = 'selected="selected"';

	if (isset($page_object->taxonomy) && $page_object->taxonomy == 'marketpress-product-category') {
		$term_id = $page_object->term_id;
		$selected = '';
	}

	echo '<select id="product-short">';
	echo '<option value="' . site_url() . '/product/" ' . $selected . '>All Category</option>';

	foreach ((array)$terms as $t) {
		if ($term_id == $t->term_id) {
			$selected = 'selected="selected"';
		} else {
			$selected = '';
		}
		echo '<option value="' . get_term_link($t->term_id) . '" ' . $selected . '>' . $t->name . '</option>';
	}

	echo '</select>';
}

/**
 * Product select short
 */
function marketpress_store_category_list()
{
	$terms = get_terms([
		'taxonomy' => 'marketpress-store-category'
	]);

	$page_object = get_queried_object();
	$term_id = 0;
	$selected = 'selected="selected"';

	if (isset($page_object->taxonomy) && $page_object->taxonomy == 'marketpress-store-category') {
		$term_id = $page_object->term_id;
		$selected = '';
	}

	echo '<select id="product-short">';
	echo '<option value="' . site_url() . '/shop/" ' . $selected . '>All Category</option>';

	foreach ((array)$terms as $t) {
		if ($term_id == $t->term_id) {
			$selected = 'selected="selected"';
		} else {
			$selected = '';
		}
		echo '<option value="' . get_term_link($t->term_id) . '" ' . $selected . '>' . $t->name . '</option>';
	}

	echo '</select>';
}


function marketpress_breadcrumb()
{

	$sep = ' / ';

	if (!is_front_page()) {

		// Start the breadcrumb with a link to your homepage
		echo '<div class="breadcrumbs">';
		echo '<a href="';
		echo get_option('home');
		echo '">';
		echo 'Home';
		echo '</a>' . $sep;

		// Check if the current page is a category, an archive or a single page. If so show the category or archive name.
		if (is_category() || is_single()) {
			the_category('/ ');
		} elseif (is_archive() || is_single()) {
			if (is_day()) {
				printf(__('%s', 'text_domain'), get_the_date());
			} elseif (is_month()) {
				printf(__('%s', 'text_domain'), get_the_date(_x('F Y', 'monthly archives date format', 'text_domain')));
			} elseif (is_year()) {
				printf(__('%s', 'text_domain'), get_the_date(_x('Y', 'yearly archives date format', 'text_domain')));
			} else {
				_e('Blog Archives', 'text_domain');
			}
		}

		// If the current page is a single post, show its title with the separator
		if (is_single()) {
			echo $sep;
			the_title();
		}

		// If the current page is a static page, show its title.
		if (is_page()) {
			echo the_title();
		}

		// if you have a static page assigned to be you posts list page. It will find the title of the static page and display it. i.e Home >> Blog
		if (is_home()) {
			global $post;
			$page_for_posts_id = get_option('page_for_posts');
			if ($page_for_posts_id) {
				$post = get_page($page_for_posts_id);
				setup_postdata($post);
				the_title();
				rewind_posts();
			}
		}

		echo '</div>';
	}
}

add_action('admin_menu',   'marketpress_admin_menu');
/**
 * Market Press Menu
 * @return [type] [description]
 */
function marketpress_admin_menu()
{

	add_menu_page(
		__('MarketPress', 'marketpress'),
		__('MarketPress', 'marketpress'),
		'manage_options',
		'marketpress',
		'marketpress_admin_page',
		MARKETPRESS_URL . '/images/favicon.svg',
		1
	);

	add_submenu_page(
		'marketpress',
		__('License', 'marketpress'),
		__('License', 'marketpress'),
		'manage_options',
		'marketpress',
		'marketpress_admin_page',
		1
	);

	add_submenu_page(
		'marketpress',
		__('Documentations', 'marketpress'),
		__('Documentations', 'marketpress'),
		'manage_options',
		'admin.php?marketpress-redirect=docs.brandmarketers.id/docs-category/marketpress',
		false
	);

	add_submenu_page(
		'marketpress',
		__('Order', 'marketpress'),
		__('Order', 'marketpress'),
		'manage_options',
		'edit.php?post_type=marketpress-order',
		false
	);


	add_submenu_page(
		'marketpress',
		__('Coupon', 'marketpress'),
		__('Coupon', 'marketpress'),
		'manage_options',
		'edit.php?post_type=marketpress-coupon',
		false
	);

	add_submenu_page(
		'marketpress',
		__('Slider', 'marketpress'),
		__('Slider', 'marketpress'),
		'manage_options',
		'edit.php?post_type=slider',
		false
	);
}

add_action('admin_init', 'marketpress_handle_submenu_extrenal_link');

/**
 * marketpress handle submenu extrenal link
 */
function marketpress_handle_submenu_extrenal_link()
{
	if (isset($_GET['marketpress-redirect'])) {
		$link = esc_url($_GET['marketpress-redirect']);
		wp_redirect($link);
	}
}

/**
 * marketpress page
 * @return [type] [description]
 */
function marketpress_admin_page()
{
	$readonly = 'required';
	$value = '';
	$disable = '';
	$status = 'INACTIVE';
	$message = '';
	$button_text = 'Activate License';

	$notice = '';

	if (isset($_GET['msg'])) {
		$msg = sanitize_text_field($_GET['msg']);
		$notice = '<div class="notice notice-error"><p><strong>' . $msg . '</strong></p></div>';
	}

	$lic = new marketpress_License();

	if ($lic->license) {
		if ($lic->license == 'valid') {
			$status = 'ACTIVE';
			$readonly = 'readonly';
			$value = '***************************';
			$disable = 'disabled="disabled"';
			$message = sprintf('Your license expires on "%s"<br/>', $lic->expires);
			$message .= sprintf('Your have activation left "%s"<br/>', $lic->activations_left);
		} else {
			$status = strtoupper($lic->license);
			$readonly = '';
			$value = '';
			$disable = '"';
			$message = sprintf('Your license has expires on "%s"<br/>', $lic->expires);
		}
	}

?>
	<div class="wrap">
		<h2>MarketPress License</h2>
		<?php echo $notice; ?>
		<form method="post" action="">
			<?php wp_nonce_field('marketpress', '_wpnonce'); ?>
			<h3>
				<? echo __('Your marketpress license code', 'marketpress'); ?>
			</h3>
			<p>
				License Status : <?php echo $status; ?>
			</p>
			<p>
				<?php echo $message; ?>
			</p>
			<ol>
				<li><?php printf(__('Masuk <a href="%s" target="_blank">Member Area</a> untuk mendapatkan kode lisensi.'), 'https://user.brandmarketers.id'); ?></li>
				<li><?php _e(__(' Masukan lisensi anda pada kolom license keys di bawah ini.')); ?></li>
				<li><?php _e(__('Klik tombol <strong>"Activate License"</strong>.')); ?></li>
			</ol>
			<p>
				<label>License Code</label><br />
				<input name="code" type="text" class="regular-text" value="<?php echo $value; ?>" placeholder="Insert your license key here" <?php echo $readonly; ?>>
			</p>
			<p>
				<input type="submit" class="button button-primary" name="submit" value="<?php echo $button_text; ?>" <?php echo $disable; ?>>
				<?php if ($lic->license == 'valid') : ?>
					<input type="submit" class="button button-primary" name="deactivate" value="Deactivate License">
				<?php endif; ?>
			</p>
			<p></p>
		</form>
		<div class="clear"></div>
	</div>
<?php

}

function marketpress_push_license()
{
	$lic = new marketpress_License();

	if (isset($_POST['submit']) && isset($_POST['_wpnonce'])) {
		if (wp_verify_nonce($_POST['_wpnonce'], 'marketpress')) {

			$c = sanitize_text_field($_POST['code']);
			$lic->activate($c);
		}
	}

	if (isset($_POST['deactivate']) && isset($_POST['_wpnonce'])) {
		if (wp_verify_nonce($_POST['_wpnonce'], 'marketpress')) {
			$lic->deactivate();
		}
	}
}

add_action('admin_init', 'marketpress_push_license');

/**
 * back to top icon
 */
function marketpress_back_to_top()
{
	global $wp;

	if (get_post_type() == 'marketpress-product') return;
	if (get_post_type() == 'marketpress-store') return;

	if (is_page_template('template-product-atc.php')) return;
	if (is_page_template('template-product.php')) return;

	if (isset($wp->query_vars['checkout']) && $wp->query_vars['checkout'] == 1) return;
	if (isset($wp->query_vars['thanks']) && $wp->query_vars['thanks'] == 1) return;

	echo '<div id="back-to-top" class="button-bg button-color"><i class="lni lni-chevron-up"></i></div>';
}
add_action('wp_footer', 'marketpress_back_to_top', 10);

function marketpress_checking()
{
	$lic = new marketpress_License();

	$lic->check();
}
add_action('init', 'marketpress_checking');

/**
 * add custom route
 * source : https://codex.wordpress.org/Rewrite_API/add_rewrite_rule
 */
function marketpress_custom_route()
{
	add_rewrite_rule(
		'^checkout/?',
		'index.php?checkout=1',
		'top'
	);

	add_rewrite_rule(
		'^thanks/([^/]*)/?',
		'index.php?thanks=1&order_id=$matches[1]',
		'top'
	);

	add_rewrite_rule(
		'^flashsale/?',
		'index.php?flashsale=1',
		'top'
	);

	add_rewrite_rule(
		'^bestseller/?',
		'index.php?bestseller=1',
		'top'
	);
}
add_action('init', 'marketpress_custom_route', 10, 0);

/**
 * custom query vars
 */
function marketpress_query_vars($query_vars)
{
	$query_vars[] = 'checkout';
	$query_vars[] = 'thanks';
	$query_vars[] = 'order_id';
	$query_vars[] = 'flashsale';
	$query_vars[] = 'bestseller';
	return $query_vars;
}
add_filter('query_vars', 'marketpress_query_vars');

/**
 * set template
 */
function marketpress_template($template)
{
	if (get_query_var('checkout') == 1) {
		$template = MARKETPRESS_PATH . '/template-parts/checkout.php';
	}

	if (get_query_var('thanks') == 1 && get_query_var('order_id')) {
		$template = MARKETPRESS_PATH . '/template-parts/thanks.php';
	}

	if (get_query_var('flashsale') == 1) {
		$template = MARKETPRESS_PATH . '/template-parts/flashsale-page.php';
	}

	if (get_query_var('bestseller') == 1) {
		$template = MARKETPRESS_PATH . '/template-parts/bestseller-page.php';
	}

	return $template;
}
add_filter('template_include', 'marketpress_template');

/**
 * own encryption
 */
function marketpress_crypt($string, $decript = false)
{
	$secret_key = NONCE_KEY;
	$secret_iv = NONCE_SALT;

	$encrypt_method = "AES-256-CBC";
	$key = hash('sha256', $secret_key);
	$iv = substr(hash('sha256', $secret_iv), 0, 16);

	if ($decript)
		return openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);

	return base64_encode(openssl_encrypt($string, $encrypt_method, $key, 0, $iv));
}

add_action('pre_get_posts', 'marketpress_search_filter');
function marketpress_search_filter($query)
{
	if (!is_admin() && $query->is_main_query()) :
		if ($query->is_search) :
			if (isset($_GET['type']) && $_GET['type'] == 'store') {
				$query->set('post_type', ['marketpress-store']);
			} else {
				$query->set('post_type', ['post', 'marketpress-product', 'marketpress-store']);
			}
		endif;
	endif;

	// if (!is_admin() && is_post_type_archive('marketpress-product') && 'marketpress-product' == $query->get('post_type')) {
	// 	$meta_query = array(
	// 		array(
	// 			'key' => 'call_to_action',
	// 			'value' => 'atc',
	// 			'compare' => '!=',
	// 		),
	// 	);
	// 	$query->set('meta_query', $meta_query);
	// }
}

/**
 * get single random admin whatsapp number
 * @return [type] [description]
 */
function marketpress_get_admin_phone()
{

	$lists = get_option('mes_order_admin_phones');
	$phones = array();

	if (is_array($lists)) {
		foreach ((array)$lists as $key => $val) {
			if ($val['number']) {
				$phones[] = $val['number'];
			}
		}
	} else {
		$phones = explode(',', $lists);
	}

	if (empty($phones)) return false;

	$key = array_rand($phones, 1);

	$wa = isset($phones[$key]) ? $phones[$key] : $phones[0];
	$wa = preg_replace('/[^0-9]/', '', $wa);
	$wa = preg_replace('/^620/', '62', $wa);
	$wa = preg_replace('/^0/', '62', $wa);

	return $wa;
}

add_filter('wp_nav_menu_items', 'add_extra_item_to_nav_menu', 10, 2);
function add_extra_item_to_nav_menu($items, $args)
{
	$new_items = '<li class="store-search-form"><form method="get" action="' . home_url() . '/" role="search"><button type="submit"><i class="lni lni-search-alt"></i></button><input type="search" name="s"
                                placeholder="' . __('Mau belanja apa hari ini?', 'marketpress') . '" required><input type="hidden" name="type" value="store" /></form></li>';

	// if ($args->theme_location == 'primary' && get_theme_mod('header_style', '3section') == 'store') {
	// 	$new_items .= $items;

	// 	return $new_items;
	// }

	return $items;
}

function marketpress_flashsale_is_active()
{
	$timezone = 'Asia/Jakarta';

	if (get_option('timezone_string')) {
		$timezone = get_option('timezone_string');
	};

	date_default_timezone_set($timezone);

	$status = false;
	$starttime = get_option('mes_flashsale_date_start', '');
	$endtime = get_option('mes_flashsale_date_end', '');

	if (empty($endtime)) return $status;

	$now = strtotime('now');
	$start = strtotime($starttime);
	$end = strtotime($endtime);

	if ($starttime && $start < $now && $end > $now) {
		$status = true;
	}

	if (empty($starttime) && $end > $now) {
		$status = true;
	}

	return $status;
}

/**
 * show breadcrumb
 * @return [type] [description]
 */
function marketpress_store_breadcrumb()
{
	$args = array(
		'meta_key' => '_wp_page_template',
		'meta_value' => 'template-product-atc.php'
	);
	$pages = get_pages($args);

	$sep = '<svg width="7" height="11" viewBox="0 0 7 11" fill="none" xmlns="http://www.w3.org/2000/svg">
<path d="M1 10L6 5.5L1 1" stroke="black"/>
</svg>';

	if (!is_front_page()) {

		// Start the breadcrumb with a link to your homepage
		echo '<div class="breadcrumbs">';
		echo '<a href="';
		echo get_option('home');
		echo '">';
		echo 'Home';
		echo '</a>' . $sep;

		if (isset($pages[0])) {
			echo '<a href="';
			echo get_the_permalink($pages[0]->ID);
			echo '">';
			echo $pages[0]->post_title;
			echo '</a>' . $sep;
		} else {
			echo '<a href="';
			echo site_url() . '/shop/';
			echo '">';
			echo 'Shop';
			echo '</a>' . $sep;
		}

		// Check if the current page is a category, an archive or a single page. If so show the category or archive name.
		if (is_category() || is_single()) {
			the_terms(get_the_ID(), 'marketpress-store-category', '', '> ');
		} elseif (is_archive() || is_single()) {
			if (is_day()) {
				printf(__('%s', 'text_domain'), get_the_date());
			} elseif (is_month()) {
				printf(__('%s', 'text_domain'), get_the_date(_x('F Y', 'monthly archives date format', 'text_domain')));
			} elseif (is_year()) {
				printf(__('%s', 'text_domain'), get_the_date(_x('Y', 'yearly archives date format', 'text_domain')));
			} else {
				_e('Blog Archives', 'text_domain');
			}
		}

		// If the current page is a single post, show its title with the separator
		if (is_single()) {
			echo $sep;
			the_title();
		}

		// If the current page is a static page, show its title.
		if (is_page()) {
			echo the_title();
		}

		// if you have a static page assigned to be you posts list page. It will find the title of the static page and display it. i.e Home >> Blog
		if (is_home()) {
			global $post;
			$page_for_posts_id = get_option('page_for_posts');
			if ($page_for_posts_id) {
				$post = get_page($page_for_posts_id);
				setup_postdata($post);
				the_title();
				rewind_posts();
			}
		}

		echo '</div>';
	}
}
