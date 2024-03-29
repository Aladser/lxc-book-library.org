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
    <link rel="icon" href="/public/images/favicon.ico">
    <!-- css -->
    <link rel="stylesheet" href="/public/css/theme.css">
    <link rel="stylesheet" href="/public/css/template.css">
    <?php if (!empty($content_css)) { ?>
        <?php foreach ($content_css as $css) { ?>
            <link rel="stylesheet" href='<?php echo "/public/css/$css"; ?>'>
        <?php }?>
    <?php } ?>
    <!-- js -->
    <?php if (!empty($content_js)) { ?>
        <?php foreach ($content_js as $script) {?>
            <script type='text/javascript' src='<?php echo "/public/js/$script"; ?>' defer></script>
        <?php }?>
    <?php } ?>
</head>
<body>

<header class='theme-border-bottom theme-shadow theme-mb'>
    <div class='container'>
        <div class='d-flex justify-content-between theme-color'>
            <div class='d-inline-block p-3 fw-bold w-100'><?php echo $page_name; ?></div>
            <?php if (!empty($data['header_button_url'])) { ?>
                <div class='d-flex align-items-center justify-content-end w-100'>
                    <!-- имя пользователя -->
                    <?php if (!empty($data['auth_user_name'])) { ?>
                    <a href=<?php echo $data['auth_user_page']; ?> class='header-nav-btn theme-color' title='Профиль'>
                        <?php echo $data['auth_user_name']; ?>
                    </a>
                    <?php } ?> 
                    <!-- кнопка входа-выхода -->
                    <a href="<?php echo $data['header_button_url']; ?>" class='header-nav-btn theme-color me-1'>
                        <?php echo $data['header_button_name']; ?>
                    </a>
                </div>
            <?php } ?>
        </div>
    </div>
</header>

<div class='container'>
    <?php include $content_view; ?>
</div>

</body>
</html>