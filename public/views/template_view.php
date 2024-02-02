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
    <link rel="icon" href="http://<?php echo $site_address; ?>/public/images/favicon.ico">
    <link rel="stylesheet" href="http://<?php echo $site_address; ?>/public/css/template.css">
    <link rel="stylesheet" href="http://<?php echo $site_address; ?>/public/css/theme.css">
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
    <div class='d-flex justify-content-between theme-border theme-color'>
        <div class='d-inline-block p-3 fw-bold'><?php echo $page_name; ?></div>
        <?php if (!empty($data['header_button_url'])) { ?>
            <div class='d-flex'>
                <?php if (!empty($data['auth_user_name'])) { ?>
                <a href=<?php echo $data['auth_user_page']; ?> class='button-basic button-wide theme-border-start d-flex justify-content-center align-items-center' title='Профиль'>
                    <?php echo $data['auth_user_name']; ?>
                </a>
                <?php } ?> 

                <a href="<?php echo $data['header_button_url']; ?>" class='button-basic theme-border-start d-flex justify-content-center align-items-center'>
                    <?php echo $data['header_button_name']; ?>
                </a>
            </div>
        <?php } ?>
    </div>
</header>

<?php include $content_view; ?>
</body>
</html>
