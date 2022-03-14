<?php
$logo = get_theme_mod('header_logo');
$site_logo = '<a href="' . esc_url(home_url()) . '"><h1>' . get_bloginfo('name') . '</h1></a>';
if ($logo) {
    $site_logo = '<a href="' . esc_url(home_url()) . '"><img class="lazy" data-src="' . $logo . '" alt="' . get_bloginfo('name') . '" width="auto" height="auto"></a><h1 style="display:none">' . get_bloginfo('name') . '</h1>';
}
$favicon = get_site_icon_url();
if (empty($favicon)) {
    $favicon = MARKETPRESS_URL . '/images/favicon.png';
}
$sticky = '';
if (get_theme_mod('header_sticky') == 1 || get_theme_mod('header_sticky') == '1') {
    $sticky = ' sticky';
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

    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
    <header>
        <div class="header<?php echo $sticky; ?>">
            <div class="wrapper">
                <div class="headerbox clear">
                    <div id="primary-menu-toggle" class="primary-menu-toggle">
                        <span></span>
                        <span></span>
                        <span></span>
                    </div>
                    <div class="logo-3section">
                        <?php echo $site_logo; ?>
                    </div>
                    <?php
                    wp_nav_menu(array(
                        'theme_location' => 'primary',
                        'menu_id'        => false,
                        'container_class' => 'primary-menu',
                        'container_id' => false,
                        'menu_class' => false
                    ));
                    ?>
                    <div id="search-toggle" class="search-toggle">
                        <i class="lni lni-search-alt"></i>
                    </div>
                    <div class="search-form">
                        <div class="wrapper">
                            <form method="get" action="<?php echo home_url(); ?>/" role="search">
                                <input type="search" name="s"
                                    placeholder="<?php _e('Search and Enter', 'marketpress'); ?>" required>
                                <button type="submit">
                                    <i class="lni lni-search-alt"></i>
                                </button>
                                <div class="close">
                                    <i class="lni lni-close"></i>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>