<?php

/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package MarketPress
 */

?>

<footer>
    <div class="footer">
        <div class="wrapper">
            <div class="widgetbox clear">
                <div class="widget widgetleft">
                    <?php if (!function_exists('dynamic_sidebar') || !dynamic_sidebar('footer-widget-1')) ?>
                </div>
                <div class="widget widgetcenter">
                    <?php if (!function_exists('dynamic_sidebar') || !dynamic_sidebar('footer-widget-2')) ?>
                </div>
                <div class="widget widgetright">
                    <?php if (!function_exists('dynamic_sidebar') || !dynamic_sidebar('footer-widget-3')) ?>
                </div>
            </div>
            <div class="site-info">
                <?php echo get_theme_mod('copyright_text', '© 2019 Copyright Market Press'); ?>
            </div>
        </div>
    </div>
</footer><!-- #colophon -->

<?php wp_footer(); ?>

</body>

</html>