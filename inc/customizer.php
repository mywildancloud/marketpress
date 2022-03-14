<?php

/**
 * Zorelix Theme Customizer
 *
 * @package Zorelix
 */

function marketpress_customizer_options()
{
	$lic = new marketpress_License();
	if ($lic->license == 'valid') {

		// Stores all the controls that will be added
		$options = array();
		// Stores all the sections to be added
		$sections = array();
		// Stores all the panels to be added
		$panels = array();
		// Adds the sections to the $options array
		$options['sections'] = $sections;

		// Header
		$panels[] = array(
			'id' => 'general',
			'title' => __('General', 'marketpress'),
			'priority' => '19'
		);

		$sections[] = array(
			'id' => 'layout',
			'title' => __('Layout', 'marketpress'),
			'priority' => '10',
			'panel' => 'general'
		);
		$options['layout_width'] = array(
			'id' => 'layout_width',
			'label'   => __('Page Width (px)', 'marketpress'),
			'section' => 'layout',
			'type'    => 'range-value',
			'input_attrs' => array(
				'min'   => 768,
				'max'   => 1280,
				'step'  => 1,
			),
			'default' => 1280,
		);
		$options['layout_line2'] = array(
			'id' => 'layout_line2',
			'section' => 'layout',
			'type'    => 'content',
			'content' => '<p>' . __('<hr/>', 'marketpress') . '</p>',
		);
		$options['body_background'] = array(
			'id' => 'body_background',
			'label'   => __('Site Background', 'marketpress'),
			'section' => 'layout',
			'type'    => 'color',
			'default' => '#f4f4f4',
		);

		$sections[] = array(
			'id' => 'font',
			'title' => __('Font', 'marketpress'),
			'priority' => '10',
			'panel' => 'general'
		);
		$options['font_family'] = array(
			'id' => 'font_family',
			'label'   => __('Font Family', 'marketpress'),
			'section' => 'font',
			'type'    => 'select',
			'choices' => customizer_library_get_font_choices(),
			'default' => 0
		);
		$options['default_line1'] = array(
			'id' => 'default_line1',
			'section' => 'default',
			'type'    => 'content',
			'content' => '<p>' . __('<hr/>', 'marketpress') . '</p>',
		);
		$sections[] = array(
			'id' => 'links',
			'title' => __('Link', 'marketpress'),
			'priority' => '10',
			'panel' => 'general'
		);
		$options['link_color'] = array(
			'id' => 'link_color',
			'label'   => __('Color', 'marketpress'),
			'section' => 'links',
			'type'    => 'color',
			'default' => '#333F50',
		);
		$options['link_line1'] = array(
			'id' => 'link_line1',
			'section' => 'links',
			'type'    => 'content',
			'content' => '<p>' . __('<hr/>', 'marketpress') . '</p>',
		);
		$options['link_hover_color'] = array(
			'id' => 'link_hover_color',
			'label'   => __('Hover Color', 'marketpress'),
			'section' => 'links',
			'type'    => 'color',
			'default' => '#191970',
		);
		$options['link_line2'] = array(
			'id' => 'link_line2',
			'section' => 'links',
			'type'    => 'content',
			'content' => '<p>' . __('<hr/>', 'marketpress') . '</p>',
		);
		$options['link_visited_color'] = array(
			'id' => 'link_visited_color',
			'label'   => __('Visited Color', 'marketpress'),
			'section' => 'links',
			'type'    => 'color',
			'default' => '#521989',
		);

		$sections[] = array(
			'id' => 'button',
			'title' => __('Button', 'marketpress'),
			'priority' => '10',
			'panel' => 'general'
		);
		$options['button_color'] = array(
			'id' => 'button_color',
			'label'   => __('Color', 'marketpress'),
			'section' => 'button',
			'type'    => 'color',
			'default' => '#ffffff',
		);
		$options['buttonline1'] = array(
			'id' => 'button_line1',
			'section' => 'button',
			'type'    => 'content',
			'content' => '<p>' . __('<hr/>', 'marketpress') . '</p>',
		);
		$options['button_bg'] = array(
			'id' => 'button_bg',
			'label'   => __('Background Color', 'marketpress'),
			'section' => 'button',
			'type'    => 'color',
			'default' => '#39499c',
		);


		$sections[] = array(
			'id' => 'fb_pixel',
			'title' => __('Facebook Pixel', 'marketpress'),
			'priority' => '10',
			'panel' => 'general'
		);
		$options['fb_pixel_id_1'] = array(
			'id' => 'fb_pixel_id_1',
			'label'   => __('Facebook Pixel ID #1', 'marketpress'),
			'section' => 'fb_pixel',
			'type'    => 'text',
		);
		$options['fb_pixel_line1'] = array(
			'id' => 'fb_pixel_line1',
			'section' => 'fb_pixel',
			'type'    => 'content',
			'content' => '<p>' . __('<hr/>', 'marketpress') . '</p>',
		);
		$options['fb_pixel_id_2'] = array(
			'id' => 'fb_pixel_id_2',
			'label'   => __('Facebook Pixel ID #2', 'marketpress'),
			'section' => 'fb_pixel',
			'type'    => 'text',
		);
		$options['fb_pixel_line2'] = array(
			'id' => 'fb_pixel_line2',
			'section' => 'fb_pixel',
			'type'    => 'content',
			'content' => '<p>' . __('<hr/>', 'marketpress') . '</p>',
		);
		$options['fb_pixel_id_3'] = array(
			'id' => 'fb_pixel_id_3',
			'label'   => __('Facebook Pixel ID #3', 'marketpress'),
			'section' => 'fb_pixel',
			'type'    => 'text',
		);
		$options['fb_pixel_line3'] = array(
			'id' => 'fb_pixel_line3',
			'section' => 'fb_pixel',
			'type'    => 'content',
			'content' => '<p>' . __('<hr/>', 'marketpress') . '</p>',
		);
		$options['fb_pixel_id_4'] = array(
			'id' => 'fb_pixel_id_4',
			'label'   => __('Facebook Pixel ID #4', 'marketpress'),
			'section' => 'fb_pixel',
			'type'    => 'text',
		);
		$options['fb_pixel_line4'] = array(
			'id' => 'fb_pixel_line4',
			'section' => 'fb_pixel',
			'type'    => 'content',
			'content' => '<p>' . __('<hr/>', 'marketpress') . '</p>',
		);
		$options['fb_pixel_id_5'] = array(
			'id' => 'fb_pixel_id_5',
			'label'   => __('Facebook Pixel ID #5', 'marketpress'),
			'section' => 'fb_pixel',
			'type'    => 'text',
		);

		$sections[] = array(
			'id' => 'custom_script',
			'title' => __('Custom Script', 'marketpress'),
			'priority' => '50',
			'panel' => 'general'
		);
		$options['custom_script_head'] = array(
			'id' => 'custom_script_head',
			'label'   => __('Head Custom Script', 'marketpress'),
			'section' => 'custom_script',
			'type'    => 'textarea',
			'default' => '',
			'description' => 'Insert script to Head',
			'sanitize_callback' => false,
		);
		$options['custom_script_line4'] = array(
			'id' => 'custom_script_line4',
			'section' => 'custom_script',
			'type'    => 'content',
			'content' => '<p>' . __('<hr/>', 'marketpress') . '</p>',
		);
		$options['custom_script_footer'] = array(
			'id' => 'custom_script_footer',
			'label'   => __('Footer Custom Script', 'marketpress'),
			'section' => 'custom_script',
			'type'    => 'textarea',
			'default' => '',
			'description' => 'Insert script to Footer',
			'sanitize_callback' => false,
		);

		$sections[] = array(
			'id' => 'header',
			'title' => __('Header', 'marketpress'),
			'priority' => '20'
		);

		$options['header_style'] = array(
			'id' => 'header_style',
			'label'   => __('Style', 'marketpress'),
			'section' => 'header',
			'type'    => 'radio',
			'choices' => array(
				'3section' => '3 Section',
				'4section' => '4 Section',
				'store' => 'E-commerce'
			),
			'default'  => '3section'
		);

		$options['header_line10'] = array(
			'id' => 'header_line10',
			'section' => 'header',
			'type'    => 'content',
			'content' => '<p>' . __('<hr/>', 'marketpress') . '</p>',
		);

		$options['header_background'] = array(
			'id' => 'header_background',
			'label'   => __('Background', 'marketpress'),
			'section' => 'header',
			'type'    => 'color',
			'default' => '#ffffff'
		);
		$options['header_line11'] = array(
			'id' => 'header_line11',
			'section' => 'header',
			'type'    => 'content',
			'content' => '<p>' . __('<hr/>', 'marketpress') . '</p>',
		);
		$options['header_color'] = array(
			'id' => 'header_color',
			'label'   => __('Color', 'marketpress'),
			'section' => 'header',
			'type'    => 'color',
			'default' => '#000000',
			'description' => 'Text',
		);

		$options['header_line1'] = array(
			'id' => 'header_line1',
			'label'   => __('<hr/>Sticky On Scroll?', 'marketpress'),
			'section' => 'header',
			'type'    => 'content',
		);
		$options['header_sticky'] = array(
			'id' => 'header_sticky',
			'label'   => __('Yes', 'marketpress'),
			'section' => 'header',
			'type'    => 'checkbox',
			'default' => 0,
		);
		$options['header_line2'] = array(
			'id' => 'header_line2',
			'section' => 'header',
			'type'    => 'content',
			'content' => '<p>' . __('<hr/>', 'marketpress') . '</p>',
		);
		$options['header_logo'] = array(
			'id' => 'header_logo',
			'label'   => __('Logo', 'marketpress'),
			'section' => 'header',
			'type'    => 'image',
			'default' => '',
			'description' => 'Recomended logo image size with max height 40px'
		);
		//ads
		$panels[] = array(
			'id' => 'ads',
			'title' => __('Ads', 'marketpress'),
			'priority' => '30'
		);

		$sections[] = array(
			'id' => 'ad_content_after_title',
			'title' => __('After Post Title Ads', 'marketpress'),
			'priority' => '20',
			'panel' => 'ads'
		);
		$options['ad_content_after_title_code'] = array(
			'id' => 'ad_content_after_title_code',
			'label'   => __('After Post Title Ads Code', 'marketpress'),
			'section' => 'ad_content_after_title',
			'type'    => 'textarea',
			'default' => '',
			'description' => 'Put your ads code here',
			'sanitize_callback' => false,
		);
		$sections[] = array(
			'id' => 'ad_content_after_content',
			'title' => __('After Post Content Ads', 'marketpress'),
			'priority' => '20',
			'panel' => 'ads'
		);
		$options['ad_content_after_content_code'] = array(
			'id' => 'ad_content_after_content_code',
			'label'   => __('After Post Content Ads Code', 'marketpress'),
			'section' => 'ad_content_after_content',
			'type'    => 'textarea',
			'default' => '',
			'description' => 'Put your ads code here',
			'sanitize_callback' => false,
		);

		$sections[] = array(
			'id' => 'ad_content_on_content1',
			'title' => __('On Content Ads 1', 'marketpress'),
			'priority' => '20',
			'panel' => 'ads'
		);
		$options['ad_content_on_content1_code'] = array(
			'id' => 'ad_content_on_content1_code',
			'label'   => __('On Content Ads Code', 'marketpress'),
			'section' => 'ad_content_on_content1',
			'type'    => 'textarea',
			'default' => '',
			'description' => 'Put your ads code here',
			'sanitize_callback' => false,
		);
		$options['ad_content_on_content1_line1'] = array(
			'id' => 'ad_content_on_content1_line1',
			'section' => 'ad_content_on_content1',
			'type'    => 'content',
			'content' => '<p>' . __('<hr/>', 'marketpress') . '</p>',
		);
		$options['ad_content_on_content1_location'] = array(
			'id' => 'ad_content_on_content1_location',
			'label'   => __('Show ads after x paragraph', 'marketpress'),
			'section' => 'ad_content_on_content1',
			'type'    => 'select',
			'choices' => array(
				1 => 1,
				2 => 2,
				3 => 3,
				4 => 4,
				5 => 5,
				6 => 6,
				7 => 7,
				8 => 8,
				9 => 9,
				10 => 10,
				11 => 11,
				12 => 12,
				13 => 13,
				14 => 14,
				15 => 15,
				16 => 16,
				17 => 17,
				18 => 18,
				19 => 19,
				20 => 20
			),
			'default' => 2
		);

		$sections[] = array(
			'id' => 'ad_content_on_content2',
			'title' => __('On Content Ads 2', 'marketpress'),
			'priority' => '20',
			'panel' => 'ads'
		);
		$options['ad_content_on_content2_code'] = array(
			'id' => 'ad_content_on_content2_code',
			'label'   => __('On Content Ads Code', 'marketpress'),
			'section' => 'ad_content_on_content2',
			'type'    => 'textarea',
			'default' => '',
			'description' => 'Put your ads code here',
			'sanitize_callback' => false,
		);
		$options['ad_content_on_content2_line2'] = array(
			'id' => 'ad_content_on_content2_line2',
			'section' => 'ad_content_on_content2',
			'type'    => 'content',
			'content' => '<p>' . __('<hr/>', 'marketpress') . '</p>',
		);
		$options['ad_content_on_content2_location'] = array(
			'id' => 'ad_content_on_content2_location',
			'label'   => __('Show ads after x paragraph', 'marketpress'),
			'section' => 'ad_content_on_content2',
			'type'    => 'select',
			'choices' => array(
				1 => 1,
				2 => 2,
				3 => 3,
				4 => 4,
				5 => 5,
				6 => 6,
				7 => 7,
				8 => 8,
				9 => 9,
				10 => 10,
				11 => 11,
				12 => 12,
				13 => 13,
				14 => 14,
				15 => 15,
				16 => 16,
				17 => 17,
				18 => 18,
				19 => 19,
				20 => 20
			),
			'default' => 4
		);

		$sections[] = array(
			'id' => 'ad_content_on_content3',
			'title' => __('On Content Ads 3', 'marketpress'),
			'priority' => '20',
			'panel' => 'ads'
		);
		$options['ad_content_on_content3_code'] = array(
			'id' => 'ad_content_on_content3_code',
			'label'   => __('On Content Ads Code', 'marketpress'),
			'section' => 'ad_content_on_content3',
			'type'    => 'textarea',
			'default' => '',
			'description' => 'Put your ads code here',
			'sanitize_callback' => false,
		);
		$options['ad_content_on_content3_line3'] = array(
			'id' => 'ad_content_on_content3_line3',
			'section' => 'ad_content_on_content3',
			'type'    => 'content',
			'content' => '<p>' . __('<hr/>', 'marketpress') . '</p>',
		);
		$options['ad_content_on_content3_location'] = array(
			'id' => 'ad_content_on_content3_location',
			'label'   => __('Show ads after x paragraph', 'marketpress'),
			'section' => 'ad_content_on_content3',
			'type'    => 'select',
			'choices' => array(
				1 => 1,
				2 => 2,
				3 => 3,
				4 => 4,
				5 => 5,
				6 => 6,
				7 => 7,
				8 => 8,
				9 => 9,
				10 => 10,
				11 => 11,
				12 => 12,
				13 => 13,
				14 => 14,
				15 => 15,
				16 => 16,
				17 => 17,
				18 => 18,
				19 => 19,
				20 => 20
			),
			'default' => 6
		);

		$sections[] = array(
			'id' => 'ad_content_on_content4',
			'title' => __('On Content Ads 4', 'marketpress'),
			'priority' => '20',
			'panel' => 'ads'
		);
		$options['ad_content_on_content4_code'] = array(
			'id' => 'ad_content_on_content4_code',
			'label'   => __('On Content Ads Code', 'marketpress'),
			'section' => 'ad_content_on_content4',
			'type'    => 'textarea',
			'default' => '',
			'description' => 'Put your ads code here',
			'sanitize_callback' => false,
		);
		$options['ad_content_on_content4_line4'] = array(
			'id' => 'ad_content_on_content4_line4',
			'section' => 'ad_content_on_content4',
			'type'    => 'content',
			'content' => '<p>' . __('<hr/>', 'marketpress') . '</p>',
		);
		$options['ad_content_on_content4_location'] = array(
			'id' => 'ad_content_on_content4_location',
			'label'   => __('Show ads after x paragraph', 'marketpress'),
			'section' => 'ad_content_on_content4',
			'type'    => 'select',
			'choices' => array(
				1 => 1,
				2 => 2,
				3 => 3,
				4 => 4,
				5 => 5,
				6 => 6,
				7 => 7,
				8 => 8,
				9 => 9,
				10 => 10,
				11 => 11,
				12 => 12,
				13 => 13,
				14 => 14,
				15 => 15,
				16 => 16,
				17 => 17,
				18 => 18,
				19 => 19,
				20 => 20
			),
			'default' => 8
		);

		/**
		 * single post panel
		 */

		$panels[] = array(
			'id' => 'page',
			'title' => __('Post Type Page', 'marketpress'),
			'priority' => '30'
		);

		$sections[] = array(
			'id' => 'page_title',
			'title' => __('Title', 'marketpress'),
			'priority' => '10',
			'panel' => 'page'
		);
		$options['enable_page_title'] = array(
			'id' => 'enable_page_title',
			'label'   => __('Enable Page Title', 'marketpress'),
			'section' => 'page_title',
			'type'    => 'checkbox',
			'default' => 1,
		);


		$panels[] = array(
			'id' => 'post',
			'title' => __('Post Type Post', 'marketpress'),
			'priority' => '30'
		);

		$sections[] = array(
			'id' => 'post_breadcrumb',
			'title' => __('Breadcrumb', 'marketpress'),
			'priority' => '10',
			'panel' => 'post'
		);
		$options['enable_post_breadcrumb'] = array(
			'id' => 'enable_post_breadcrumb',
			'label'   => __('Enable Post Breadcrumb', 'marketpress'),
			'section' => 'post_breadcrumb',
			'type'    => 'checkbox',
			'default' => 1,
		);

		$sections[] = array(
			'id' => 'post_thumbnail',
			'title' => __('Thumbnail', 'marketpress'),
			'priority' => '10',
			'panel' => 'post'
		);
		$options['enable_post_thumbnail'] = array(
			'id' => 'enable_post_thumbnail',
			'label'   => __('Enable Post Thumbnail', 'marketpress'),
			'section' => 'post_thumbnail',
			'type'    => 'checkbox',
			'default' => 1,
		);

		$sections[] = array(
			'id' => 'post_title',
			'title' => __('Title', 'marketpress'),
			'priority' => '10',
			'panel' => 'post'
		);
		$options['enable_post_title'] = array(
			'id' => 'enable_post_title',
			'label'   => __('Enable Post Title', 'marketpress'),
			'section' => 'post_title',
			'type'    => 'checkbox',
			'default' => 1,
		);

		$sections[] = array(
			'id' => 'post_toc',
			'title' => __('Table of Content (TOC)', 'marketpress'),
			'priority' => '25',
			'panel' => 'post'
		);
		$options['enable_post_toc'] = array(
			'id' => 'enable_post_toc',
			'label'   => __('Enable Table of Content', 'marketpress'),
			'section' => 'post_toc',
			'type'    => 'checkbox',
			'default' => 1,
		);
		$options['post_toc_line1'] = array(
			'id' => 'post_toc_line1',
			'section' => 'post_toc',
			'type'    => 'content',
			'content' => '<p>' . __('<hr/>', 'marketpress') . '</p>',
		);
		$options['post_toc_display'] = array(
			'id' => 'post_toc_display',
			'label'   => __('Default Display', 'marketpress'),
			'section' => 'post_toc',
			'type'    => 'select',
			'choices' => array(
				'opened' => __('Opened', 'marketpress'),
				'closed' => __('Closed', 'marketpress')
			),
			'default' => 'opened',
		);

		$options['post_toc_line2'] = array(
			'id' => 'post_toc_line2',
			'section' => 'post_toc',
			'type'    => 'content',
			'content' => '<p>' . __('<hr/>', 'marketpress') . '</p>',
		);
		$options['post_toc_position'] = array(
			'id' => 'post_toc_position',
			'label'   => __('Position', 'marketpress'),
			'section' => 'post_toc',
			'type'    => 'select',
			'choices' => array(
				0 => __('Before Content', 'marketpress'),
				1 => __('After first paragraph', 'marketpress'),
				2 => __('After second parapgraph', 'marketpress'),
				3 => __('After the third paragraph', 'marketpress'),
				4 => __('After the fourth parapgraph', 'marketpress'),
			),
			'default' => 0,
		);

		$sections[] = array(
			'id' => 'post_inline_related',
			'title' => __('Inline related', 'marketpress'),
			'priority' => '25',
			'panel' => 'post'
		);
		$options['enable_post_inline_related'] = array(
			'id' => 'enable_post_inline_related',
			'label'   => __('Enable Post Inline Related', 'marketpress'),
			'section' => 'post_inline_related',
			'type'    => 'checkbox',
			'default' => 1,
		);

		$sections[] = array(
			'id' => 'post_share',
			'title' => __('Social Share', 'marketpress'),
			'priority' => '30',
			'panel' => 'post'
		);
		$options['enable_post_share'] = array(
			'id' => 'enable_post_share',
			'label'   => __('Enable Social Share', 'marketpress'),
			'section' => 'post_share',
			'type'    => 'checkbox',
			'default' => 1,
		);

		$sections[] = array(
			'id' => 'post_related',
			'title' => __('Related Post', 'marketpress'),
			'priority' => '35',
			'panel' => 'post'
		);
		$options['enable_post_related'] = array(
			'id' => 'enable_post_related',
			'label'   => __('Enable Related Post', 'marketpress'),
			'section' => 'post_related',
			'type'    => 'checkbox',
			'default' => 1,
		);

		/**
		 * footer panel
		 * @var [type]
		 */
		$panels[] = array(
			'id' => 'footer',
			'title' => __('Footer', 'marketpress'),
			'priority' => '30'
		);
		$sections[] = array(
			'id' => 'copyright',
			'title' => __('Footer Copyright', 'marketpress'),
			'priority' => '20',
			'panel' => 'footer'
		);
		$options['copyright_text'] = array(
			'id' => 'copyright_text',
			'label'   => __('Copyright Text', 'marketpress'),
			'section' => 'copyright',
			'type'    => 'textarea',
			'default' => 'Copyright @ 2019 Market Press',
			'sanitize_callback' => false,
		);

		// Adds the sections to the $options array
		$options['sections'] = $sections;
		// Adds the panels to the $options array
		$options['panels'] = $panels;
		$customizer_library = Customizer_Library::Instance();
		$customizer_library->add_options($options);
		// To delete custom mods use: customizer_library_remove_theme_mods();
	}
}
add_action('init', 'marketpress_customizer_options');
