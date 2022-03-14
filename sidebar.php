<?php

/**
 * The sidebar containing the main widget area
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package MarketPress
 */

if (!is_active_sidebar('widget-post-1')) {
	return;
}
?>

<aside id="secondary">
    <div class="sidebar-content">
        <?php dynamic_sidebar('widget-post-1'); ?>
    </div>
</aside><!-- #secondary -->