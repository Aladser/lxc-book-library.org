<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php
        if (!empty($add_head)) {
            echo $add_head;
        }
    ?>
    <title><?php echo $page_name; ?></title>
    <link href="<?php echo $boostrap_url; ?>" rel="stylesheet" integrity="<?php echo $boostrap_integrity; ?>" crossorigin="anonymous">
    <link rel="icon" href="http://<?php echo $site_address; ?>/public/images/icon.png">
    <link rel="stylesheet" href="http://<?php echo $site_address; ?>/public/css/reset_styles.css">
    <link rel="stylesheet" href="http://<?php echo $site_address; ?>/public/css/template.css">
    <!-- css -->
    <?php if (!empty($content_css)) { ?>
        <link rel="stylesheet" href="http://<?php echo $site_address; ?>/public/css/<?php echo $content_css; ?>">
    <?php } ?>
    <!-- js -->
    <?php if (!empty($content_js)) { ?>
    <?php foreach ($content_js as $script) {?>
        <script type='text/javascript' src="http://<?php echo $site_address; ?>/public/js/<?php echo $script; ?>" defer></script>
    <?php }?>
<?php } ?>
</head>
<body>

<header class='mb-4'>
    <div class='theme-bg-сolor text-center text-white d-flex justify-content-between'>
        <h3 class='p-3 w-100'><?php echo $page_name; ?></h3>
    </div>
</header>

<?php include $content_view; ?>
</body>
</html>
