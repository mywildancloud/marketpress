<?php
$link  = urlencode(get_the_permalink());
$title = str_replace(' ', '%20', get_the_title());
$tw    = 'https://twitter.com/intent/tweet?text=' . $title . '&amp;url=' . $link;
$fb    = 'https://www.facebook.com/sharer.php?u=' . $link;
$line   = 'line://msg/text/' . $link;
$wa    = 'https://api.whatsapp.com/send?text=' . $link;
?>
<div class="social-share2 clear">
    <div class="label">
        <?php _e('Share link on:', 'marketpress'); ?>
    </div>
    <a href="<?php echo $wa; ?>" target="_blank" rel="nofollow" class="sharebox social whatsapp">
        <i class="lni lni-whatsapp"></i>
    </a>
    <a href="<?php echo $line; ?>" target="_blank" rel="nofollow" class="sharebox social line">
        <i class="lni lni-line"></i>
    </a>
    <a href="<?php echo $fb; ?>" target="_blank" rel="nofollow" class="sharebox social facebook">
        <i class="lni lni-facebook"></i>
    </a>
    <a href="<?php echo $tw; ?>" target="_blank" rel="nofollow" class="sharebox social twitter">
        <i class="lni lni-twitter"></i>
    </a>
</div>