<section class='content-section theme-mb'>
    <h3 class='mb-4 theme-grey-color'>Регистрация нового пользователя</h3>

    <form class='mx-auto mb-3' method="POST" action=<?php echo $routes['store']; ?>>
        <input type="email" name='email' value="<?php echo $data['user']; ?>" 
        class="d-block w-100 mb-2 p-3" placeholder="Почта" title="Почта" required>

        <input type="password" name='password'
        class="d-block w-100 mb-2 p-3" placeholder="Пароль (минимум 3 символа)" title="Пароль" required>

        <input type="password" name='password_confirm'
        class="d-block w-100 mb-2 p-3" placeholder="Подтвердите пароль" title="Подтвердите пароль" required>

        <input type="submit" value="Регистрация" class='button-basic d-block w-100 mb-2'>
        <a href=<?php echo $routes['login']; ?> class='button-basic d-block w-100 mb-2'>Есть учетная запись?</a>
        <a href="<?php echo $routes['home']; ?>" class="button-basic d-block w-100">На главную</a>
        <input type="hidden" name="CSRF" value=<?php echo $data['csrf']; ?>>  
    </form>

    <?php if (isset($data['error'])) {?>
        <p class='mx-auto fw-bolder theme-red-color'><?php echo $data['error']; ?></p> 
    <?php }?>
</section>  
