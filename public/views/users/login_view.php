<container>
    <section class='loginWindow'>
        <h3 class='loginWindow__header'> Авторизация</h3>

        <form method="POST" class='loginWindow__form' id='loginWindow__form'>
            <input type='text' class='loginWindow__input loginWindow__loginInput' name='db_login' id='loginInput' value ='user' placeholder="Логин">
            <br>
            <input type="password" class='loginWindow__input loginWindow__passwordInput' name='password' id='passwordInput' value= 'user@@user' placeholder="Пароль">
            <br>
            <input type='submit' class='loginWindow__btn' id='loginWindow__sendBtn' value='Войти'>
            <br>
            <input type='button' class='loginWindow__btn' id='loginWindow__regBtn' value='Регистрация'>
            <br>
            <input type="checkbox" class='loginWindow__saveAuth' id="loginWindow__saveAuth" name="saveAuth" checked/>
            <label for="loginWindow__saveAuth">Запомнить меня</label>
            <?php // auth и токен?>
            <input type="hidden" name="auth" value=1>
            <input type="hidden" name="CSRF" value="<?php echo $token; ?>">
        </form>

        <div class='text-center theme-color p-2'>или войти с помощью</div>

        <form method="POST" class='mx-auto' action="/engine/auth/auth_vk_check_csrf.php">
            <input type="submit" value='VK'>
            <input type="hidden" name="CSRF" value="<?php echo $data['csrf']; ?>">
        </form>
    </section>

    <a href="<?php echo $routes['home']; ?>" class="text-decoration"><p class="loginInputSection__btn-back">Назад</p></a>
    <?php if (isset($args['error'])) { ?>
        <article class='mx-auto text-center text-danger fw-bolder'><?php echo $args['error']; ?></article>
    <?php } ?>
</container>