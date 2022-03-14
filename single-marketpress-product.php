<?php
$var = isset($_GET['iframe']) ? '-iframe' : '';

if (get_post_meta(get_the_ID(), 'call_to_action', true) == 'atc') {
    $var = '-cart';
}

get_template_part('template-parts/content', 'product' . $var);