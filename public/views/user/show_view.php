<div class='container'>
    <section class='theme-border p-4'>
        <?php if (isset($data['user_photo'])) {?>
            <img src=<?php echo $data['user_photo']; ?>>
        <?php } ?>
        <div class='d-inline-block ms-4'>
            <div class='fw-bolder fs-5'>Логин: <?php echo $data['user_login']; ?></div>
            <div class='fs-3'>Имя: <?php echo $data['user_name']; ?></div>
        </div>
        <a href="<?php echo $routes['home']; ?>" class="d-block mx-auto button-basic theme-border w-50 mt-4">Назад</a>
    </section>
</div>