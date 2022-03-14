<?php
$link = get_post_meta(get_the_ID(), 'salespage_affiliate_link', true);
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title><?php the_title(); ?></title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=0">
    <style>
    iframe {
        position: fixed;
        z-index: 9;
        width: 100%;
        height: 100%;
        top: 0;
        left: 0;
    }
    </style>
</head>

<body>

    <iframe class="main" src="<?php echo $link; ?>" allowfullscreen="" frameborder="0"></iframe>

</body>

</html>