<?php

add_action('widgets_init', 'marketpress_widget_init');
function marketpress_widget_init()
{

    $lic = new marketpress_License();
    $domain = preg_replace("(^https?://)", "", site_url());
    if ($lic->license == 'valid') {

        // Define Sidebar Widget Area 1
        register_sidebar(array(
            'name'          => __('Post Sidebar', 'marketpress'),
            'description'   => __('Blogpost sidebar', 'marketpress'),
            'id'            => 'widget-post-1',
            'before_widget' => '<div id="%1$s" class="widget %2$s">',
            'after_widget'  => '</div>',
            'before_title'  => '<h3>',
            'after_title'   => '</h3>'
        ));

        register_sidebar(array(
            'name'          => __('Footer Widget 1', 'marketpress'),
            'description'   => __('Footer widget on lef position', 'marketpress'),
            'id'            => 'footer-widget-1',
            'before_widget' => '<div id="%1$s" class="%2$s">',
            'after_widget'  => '</div>',
            'before_title'  => '<h3>',
            'after_title'   => '</h3>'
        ));

        register_sidebar(array(
            'name'          => __('Footer Widget 2', 'marketpress'),
            'description'   => __('Footer widget on center position', 'marketpress'),
            'id'            => 'footer-widget-2',
            'before_widget' => '<div id="%1$s" class="%2$s">',
            'after_widget'  => '</div>',
            'before_title'  => '<h3>',
            'after_title'   => '</h3>'
        ));

        register_sidebar(array(
            'name'          => __('Footer Widget 3', 'marketpress'),
            'description'   => __('Footer widget on right position', 'marketpress'),
            'id'            => 'footer-widget-3',
            'before_widget' => '<div id="%1$s" class="%2$s">',
            'after_widget'  => '</div>',
            'before_title'  => '<h3>',
            'after_title'   => '</h3>'
        ));

        register_widget('Marketpress_About_Widget');
        register_widget('Marketpress_Social_Widget');
        register_widget('Marketpress_Latest_Post_Widget');
    }
}

class Marketpress_Latest_Post_Widget extends WP_Widget
{
    /**
     * construction
     */
    public function __construct()
    {
        parent::__construct(
            'marketpress_latest_post',
            __('Marketpress Latest Post', 'marketpress'),
            array(
                'description' => __('Latest Posts', 'marketpress')
            )
        );
    }

    /**
     * createing widget front end
     */
    public function widget($args, $instance)
    {
        extract($args);

        $title = apply_filters('widget_title', empty($instance['title']) ? __('Latest Posts') : $instance['title'], $instance, $this->id_base);

        if (empty($instance['number']) || !$number = absint($instance['number']))
            $number = 5;

        $args = array(
            'posts_per_page' => $number,
            'no_found_rows' => true,
            'post_status' => 'publish',
            'ignore_sticky_posts' => true
        );
        $args = apply_filters('widget_posts_args', $args);

        $r = new WP_Query($args);
        if ($r->have_posts()) :

            echo $before_widget;
            if ($title) echo $before_title . $title . $after_title;
            echo '<ul class="latest-posts">';
            while ($r->have_posts()) : $r->the_post();
                $category = get_the_category();
                $firstCategory = $category[0]->cat_name;

                $thumbnail_url = get_the_post_thumbnail_url();

                echo '<li><a href="' . get_the_permalink(get_the_ID()) . '" title="' . get_the_title() . '">';
                echo '<div class="latest-post-box">';
                echo '<div class="latest-post-thumbnail image-thumbnail">';
                echo '<img data-src="' . $thumbnail_url . '" class="lazy">';
                echo '</div>';
                echo '<div class="latest-post-content">';
                echo '<div class="cat">' . $firstCategory . '</div>';
                echo '<h4>' . get_the_title() . '</h4>';
                echo '<div class="date">' . get_the_date() . '</div>';
                echo '</div>';
                echo '</a></li>';
            endwhile;
            echo '</ul>';

            echo $after_widget;

            wp_reset_postdata();

        endif;
    }

    /**
     * backend form
     */
    public function form($instance)
    {
        $title = isset($instance['title']) ? $instance['title'] : 'Latest Posts';
        $number = isset($instance['number']) ? $instance['number'] : 5;

        echo '<p>';
        echo '<label for="' . $this->get_field_id('title') . '">' . _e('Title:') . '</label>';
        echo '<input class="widefat" id="' . $this->get_field_id('title') . '" name="' . $this->get_field_name('title') . '" type="text" value="' . esc_attr($title) . '" />';
        echo '<label for="' . $this->get_field_id('content') . '">' . _e('Number  post to show:') . '</label>';
        echo '<input class="widefat" id="' . $this->get_field_id('number') . '" name="' . $this->get_field_name('number') . '" type="number" value="' . esc_attr($number) . '" />';
        echo '</p>';
    }

    // Updating widget replacing old instances with new
    public function update($new_instance, $old_instance)
    {
        $instance = $old_instance;
        $instance['title'] = strip_tags($new_instance['title']);
        $instance['number'] = intval($new_instance['number']);
        return $instance;
    }
}

class Marketpress_About_Widget extends WP_Widget
{

    /**
     * construction
     */
    public function __construct()
    {

        parent::__construct(
            'marketpress_about',
            __('Marketpress About', 'marketpress'),
            array(
                'description' => __('About your website', 'marketpress')
            )
        );
    }

    // Creating widget front-end
    public function widget($args, $instance)
    {
        $title = apply_filters('widget_title', $instance['title']);
        // before and after widget arguments are defined by themes
        echo $args['before_widget'];
        if (!empty($title))
            echo $args['before_title'] . $title . $args['after_title'];

        echo '<div class="widget-content">';
        echo $instance['content'];
        echo '</div>';
        echo $args['after_widget'];
    }

    // Widget Backend
    public function form($instance)
    {
        $title = isset($instance['title']) ? $instance['title'] : 'Market Press';
        $content = isset($instance['content']) ? $instance['content'] : 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industrys standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.';

        echo '<p>';
        echo '<label for="' . $this->get_field_id('title') . '">' . _e('Title:') . '</label>';
        echo '<input class="widefat" id="' . $this->get_field_id('title') . '" name="' . $this->get_field_name('title') . '" type="text" value="' . esc_attr($title) . '" />';
        echo '<label for="' . $this->get_field_id('content') . '">' . _e('Content:') . '</label>';
        echo '<textarea id="' . $this->get_field_id('content') . '" name="' . $this->get_field_name('content') . '" class="widefat">' . esc_textarea($content) . '</textarea>';
        echo '</p>';
    }

    // Updating widget replacing old instances with new
    public function update($new_instance, $old_instance)
    {
        $instance = $old_instance;
        $instance['title'] = strip_tags($new_instance['title']);
        $instance['content'] = wp_kses_post($new_instance['content']);
        return $instance;
    }
}

class Marketpress_Social_Widget extends WP_Widget
{

    /**
     * construction
     */
    public function __construct()
    {

        parent::__construct(
            'marketpress_social',
            __('Marketpress Social', 'marketpress'),
            array(
                'description' => __('Social Media', 'marketpress')
            )
        );
    }

    // Creating widget front-end
    public function widget($args, $instance)
    {
        $title = apply_filters('widget_title', $instance['title']);
        // before and after widget arguments are defined by themes
        echo $args['before_widget'];
        if (!empty($title))
            echo $args['before_title'] . $title . $args['after_title'];

        echo '<div class="social">';
        echo '<a target="__blank" href="' . $instance['facebook'] . '"><i class="lni lni-facebook"></i></a>';
        echo '<a target="__blank" href="' . $instance['twitter'] . '"><i class="lni lni-twitter"></i></a>';
        echo '<a target="__blank" href="' . $instance['instagram'] . '"><i class="lni lni-instagram"></i></a>';
        echo '<a target="__blank" href="' . $instance['linkedin'] . '"><i class="lni lni-linkedin"></i></a>';
        echo '<a target="__blank" href="' . $instance['youtube'] . '"><i class="lni lni-youtube"></i></a>';
        echo '</div>';
        echo $args['after_widget'];
    }

    // Widget Backend
    public function form($instance)
    {
        $title = isset($instance['title']) ? $instance['title'] : 'Social Media';
        $facebook = isset($instance['facebook']) ? $instance['facebook'] : '#';
        $twitter = isset($instance['twitter']) ? $instance['twitter'] : '#';
        $instagram = isset($instance['instagram']) ? $instance['instagram'] : '#';
        $linkedin = isset($instance['linkedin']) ? $instance['linkedin'] : '#';
        $youtube = isset($instance['youtube']) ? $instance['youtube'] : '#';

        echo '<p>';
        echo '<label for="' . $this->get_field_id('title') . '">' . _e('Title:') . '</label>';
        echo '<input class="widefat" id="' . $this->get_field_id('title') . '" name="' . $this->get_field_name('title') . '" type="text" value="' . esc_attr($title) . '" />';
        echo '<hr/>';
        echo '<label for="' . $this->get_field_id('facebook') . '">' . _e('Facebook Link:') . '</label>';
        echo '<input class="widefat" id="' . $this->get_field_id('facebook') . '" name="' . $this->get_field_name('facebook') . '" type="text" value="' . esc_attr($facebook) . '" />';
        echo '<hr/>';
        echo '<label for="' . $this->get_field_id('twitter') . '">' . _e('Twitter Link:') . '</label>';
        echo '<input class="widefat" id="' . $this->get_field_id('twitter') . '" name="' . $this->get_field_name('twitter') . '" type="text" value="' . esc_attr($twitter) . '" />';
        echo '<hr/>';
        echo '<label for="' . $this->get_field_id('instagram') . '">' . _e('Instagram Link:') . '</label>';
        echo '<input class="widefat" id="' . $this->get_field_id('instagram') . '" name="' . $this->get_field_name('instagram') . '" type="text" value="' . esc_attr($instagram) . '" />';
        echo '</p>';
        echo '<hr/>';
        echo '<label for="' . $this->get_field_id('linkedin') . '">' . _e('Linkedin Link:') . '</label>';
        echo '<input class="widefat" id="' . $this->get_field_id('linkedin') . '" name="' . $this->get_field_name('linkedin') . '" type="text" value="' . esc_attr($linkedin) . '" />';
        echo '</p>';
        echo '<hr/>';
        echo '<label for="' . $this->get_field_id('youtube') . '">' . _e('Youtube Link:') . '</label>';
        echo '<input class="widefat" id="' . $this->get_field_id('youtube') . '" name="' . $this->get_field_name('youtube') . '" type="text" value="' . esc_attr($youtube) . '" />';
        echo '</p>';
    }

    // Updating widget replacing old instances with new
    public function update($new_instance, $old_instance)
    {
        $instance = $old_instance;
        $instance['title'] = strip_tags($new_instance['title']);
        $instance['facebook'] = wp_kses_post($new_instance['facebook']);
        $instance['twitter'] = wp_kses_post($new_instance['twitter']);
        $instance['linkedin'] = wp_kses_post($new_instance['linkedin']);
        $instance['instagram'] = wp_kses_post($new_instance['instagram']);
        $instance['youtube'] = wp_kses_post($new_instance['youtube']);
        return $instance;
    }
}
