<div class='container'>
    <p id='prg-error' class='prg-error'></p>
    <section class='content-section section-mb'>
        <h3 class='section-mb'>Пользователи с авторизацией на сайте</h3>

        <!-- форма добавления пользователя -->
        <article class='section-mb'>
            <h5>Новый пользователь:</h5>
            <form id='form-add-user' class='mb-1' method='post' action='<?php echo $routes['store']; ?>'>
                <input type="email" name="email" placeholder='почта' class='form-add__input theme-border' required>
                <input type="password" name="password" placeholder='пароль' class='form-add__input theme-border' value='111' required>
                <select name="is_admin" class='form-add__input theme-bg-сolor-white theme-border pe-4'>
                    <option value=0>Нет</option>
                    <option value=1>Да</option>
                </select>
                <input type="submit" value="Добавить" class='form-add__input theme-bg-сolor-white theme-border px-4'>
                <input type="hidden" name="CSRF_JS" value="<?php echo $data['csrf']; ?>">
            </form>
            <p><i>Пароль по умолчанию: 111</i></p>
        </article>

        <!-- пользователи -->
        <div class='section-mb'>
            <table id='user-table' class='w-100'>
                <tr>
                    <th class='<?php echo $css_tr_style; ?>'>Логин</th>
                    <th class='<?php echo $css_tr_style; ?>'>Имя</th>
                    <th class='<?php echo $css_tr_style; ?>'>Админ.права</th>
                </tr>

                <?php foreach ($data['users'] as $user) { ?>
                <tr>
                    <td class='table-row p-3 theme-border-bottom'>
                        <button class='user-table__btn user-table__btn-remove' title='удалить автора'>✘</button>
                        <span class='user-table__content'><?php echo $user['login']; ?></span>
                    </td>
                    <td class='table-row p-3 theme-border-bottom'><?php echo $user['nickname']; ?></td>
                    <td class='table-row p-3 theme-border-bottom'><?php echo $user['is_admin'] ? 'да' : 'нет'; ?></td>
                </tr>
                <?php }?>
            </table>
        </div>
    </section>

    <a href="<?php echo $routes['show']; ?>" class="d-block button-basic theme-border theme-border-radius mx-auto mb-2">Назад</a>
</div>