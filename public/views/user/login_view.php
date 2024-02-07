<div class='container'>
    <section class='w-50 mx-auto text-center mb-3'>
        <h3>Авторизация</h3>

        <!-- логин-пароль -->
        <form method="POST" class='w-75 mx-auto' action="<?php echo $routes['auth']; ?>">
            <input type='text' class='d-block w-100 mb-2 p-3' name='login' placeholder="Почта" required>
            <input type="password" class='d-block w-100 mb-2 p-3' name='password' placeholder="Пароль" required>
            <input type='submit' value='Войти' class="button-basic theme-border theme-border-radius d-block mx-auto mb-2 w-100">
            <a href="<?php echo $routes['register']; ?>" class="button-basic theme-border theme-border-radius d-block mx-auto mb-2 w-100">Регистрация</a>
            <input type="checkbox" name="save_auth" checked/> Запомнить меня
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


        <a href="<?php echo $routes['home']; ?>" class="d-block button-basic theme-border theme-border-radius mx-auto w-75">Назад</a>
    </section>

    <?php if (isset($data['error'])) { ?>
        <div class='mx-auto text-center text-danger fw-bolder'><?php echo $data['error']; ?></div>
    <?php } ?>
</div>