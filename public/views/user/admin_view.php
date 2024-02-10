<div class='container'>
    <section class='content-section'>
        <h3 class='section-mb'>Панель администратора</h3>

        <div class='section-mb d-inline-block w-100 text-start px-2'>
            <div class='fw-bolder fs-5'>Логин: <?php echo $data['user_login']; ?></div>
            <div class='fs-3'>Имя: <?php echo $data['user_name']; ?></div>
        </div>

        <a href="<?php echo $routes['authors']; ?>" class='button-basic theme-border theme-border-radius d-block mx-auto mb-2 w-100'>Авторы</a>
        <a href="<?php echo $routes['genres']; ?>" class='button-basic theme-border theme-border-radius d-block mx-auto mb-2 w-100'>Жанры</a>
        <a href="<?php echo $routes['home']; ?>" class="button-basic theme-border theme-border-radius d-block mx-auto mb-2 w-100">Назад</a>
    </section>
</div>