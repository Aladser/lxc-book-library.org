<div class='container'>
    <section class='admin-section w-50 mx-auto text-center mb-3'>
        <h3 class='admin-section__mb'>Панель администратора</h3>

        <div class='admin-section__mb d-inline-block w-100 text-start px-2'>
            <div class='fw-bolder fs-5'>Логин: <?php echo $data['user_login']; ?></div>
            <div class='fs-2'>Имя: <?php echo $data['user_name']; ?></div>
        </div>

        <a href="" class='button-basic theme-border d-block mx-auto mb-2 w-100'>Авторы</a>
        <a href="" class='button-basic theme-border d-block mx-auto mb-2 w-100'>Жанры</a>
        <a href="<?php echo $routes['home']; ?>" class="button-basic theme-border d-block mx-auto mb-2 w-100">Назад</a>
    </section>
</div>