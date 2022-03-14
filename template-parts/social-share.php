<?php
$link  = urlencode(get_the_permalink());
$title = str_replace(' ', '%20', get_the_title());
$tw    = 'https://twitter.com/intent/tweet?text=' . $title . '&amp;url=' . $link;
$fb    = 'https://www.facebook.com/sharer.php?u=' . $link;
$pin   = 'http://pinterest.com/pin/create/button/?url=' . $link . '&description=' . $title;
$wa    = 'https://api.whatsapp.com/send?text=' . $link;
?>
<div class="social-share clear">
    <div class="sharebox label">
        <?php _e('Share', 'zorelix'); ?> :
    </div>
    <a href="<?php echo $fb; ?>" target="_blank" rel="nofollow" class="sharebox social facebook">
        <i class="lni lni-facebook"></i>
    </a>
    <a href="<?php echo $wa; ?>" target="_blank" rel="nofollow" class="sharebox social whatsapp">
        <i class="lni lni-whatsapp"></i>
    </a>
    <a href="<?php echo $pin; ?>" target="_blank" rel="nofollow" class="sharebox social pinterest">
        <i class="lni lni-pinterest"></i>
    </a>
    <a href="<?php echo $tw; ?>" target="_blank" rel="nofollow" class="sharebox social twitter">
        <i class="lni lni-twitter"></i>
    </a>
</div>