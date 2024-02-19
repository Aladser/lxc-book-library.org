<section class='content-section'>
    <h3 class='theme-mb'>Страница администратора</h3>

    <div class='theme-mb d-inline-block w-100 text-start px-2'>
        <div class='fw-bolder fs-5'>Логин: <?php echo $data['user_login']; ?></div>
        <div class='fs-3'>Имя: <?php echo $data['user_name']; ?></div>
    </div>

    <article class='theme-mb'>
        <h5>Пользователи</h5>
        <a href="<?php echo $routes['users']; ?>" class='button-basic theme-border theme-border-radius d-block mx-auto mb-2 w-100'>Пользователи</a>
    </article>

    <article class='theme-mb'>
        <h5>Книги</h5>
        <a href="<?php echo $routes['authors']; ?>" class='button-basic theme-border theme-border-radius d-block mx-auto mb-2 w-100'>Авторы</a>
        <a href="<?php echo $routes['genres']; ?>" class='button-basic theme-border theme-border-radius d-block mx-auto mb-2 w-100'>Жанры</a>
    </article>

    <a href="<?php echo $routes['home']; ?>" class="button-basic theme-border theme-border-radius d-block mx-auto mb-2 w-100">На главную</a>
</section>