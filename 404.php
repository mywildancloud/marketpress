<?php

/**
 * The template for displaying 404 pages (not found)
 *
 * @link https://codex.wordpress.org/Creating_an_Error_404_Page
 *
 * @package MarketPress
 */

get_header();
?>

<main id="primary" class="site-main">
    <div class="wrapper">
        <div class="homepage">
            <div class="homeinfo">
                <p><?php esc_html_e('Ups, Nothing found', 'marketpress'); ?>
                </p>
            </div>
        </div>
    </div>

</main><!-- #main -->

<?php
get_footer();