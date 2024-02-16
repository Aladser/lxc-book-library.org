<div class='container'>
    <section class='content-section section-mb'>
        <h3>Авторизация</h3>

        <!-- логин-пароль -->
        <form method="POST" class='mx-auto' action="<?php echo $routes['auth']; ?>">
            <input type='text' class='d-block w-100 mb-2 p-3' name='login' placeholder="Почта" title="Почта" required>
            <input type="password" class='d-block w-100 mb-2 p-3' name='password' placeholder="Пароль" title="Пароль" required>
            <!-- кнопки формы -->
            <input type='submit' value='Войти' class="button-basic d-block w-100 mb-2">
            <a href="<?php echo $routes['register']; ?>" class="button-basic d-block w-100 mb-2">Регистрация</a>
            <input type="hidden" name="CSRF" value="<?php echo $data['csrf']; ?>">
        </form>

        <div class='my-3'>
            <!-- авторизация ВК -->
            <form method="POST" class='d-inline' action="<?php echo $routes['login_vk']; ?>">
                <input type="image" src='/public/images/vk_logo.ico' alt='ВК'>
                <input type="hidden" name="CSRF" value="<?php echo $data['csrf']; ?>">
            </form>
            <!-- авторизация google -->
            <form method="POST" class='d-inline' action="<?php echo $routes['login_google']; ?>">
                <input type="image" src='/public/images/google_logo.ico' alt='google'>
                <input type="hidden" name="CSRF" value="<?php echo $data['csrf']; ?>">
            </form>
        </div>

        <a href="<?php echo $routes['home']; ?>" class="button-basic d-block w-100">Назад</a>
    </section>


    <?php if (isset($data['error'])) { ?>
        <div class='mx-auto text-center text-danger fw-bolder'><?php echo $data['error']; ?></div>
    <?php } ?>
</div>