<div class='container'>
    <section class='text-center mx-auto w-50'>
        <h3 class='mb-4 theme-grey-color'>Регистрация нового пользователя</h3>

        <form class='w-75 mx-auto mb-3' method="POST" action=<?php echo $routes['store']; ?>>
            <input type="login" name='login' value="<?php echo $data['user']; ?>" 
            class="d-block w-100 mb-2 p-3" placeholder="Логин" required>

            <input type="password" name='password'
            class="d-block w-100 mb-2 p-3" placeholder="Пароль (минимум 3 символа)" required>

            <input type="password" name='password_confirm'
            class="d-block w-100 mb-2 p-3" placeholder="Подтвердите пароль" required>

            <input type="submit" value="Регистрация" class='d-block mx-auto button-basic theme-border w-100 mb-2'>
            <a href=<?php echo $routes['home']; ?> class='d-block mx-auto button-basic theme-border w-100'>Назад</a>
            <input type="hidden" name="CSRF" value=<?php echo $data['csrf']; ?>>  
        </form>

        <?php if (isset($data['error'])) {?>
            <p class='mx-auto fw-bolder theme-red-color'><?php echo $data['error']; ?></p> 
        <?php }?>
    </section>  
</div>
