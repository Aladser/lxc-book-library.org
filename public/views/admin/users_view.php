<div class='container'>
    <p id='prg-error' class='prg-error'></p>
    <section class='content-section section-mb'>
        <h3 class='section-mb'>Пользователи с авторизацией на сайте</h3>

        <!-- форма добавления пользователя -->
        <article class='section-mb'>
            <h5>Новый пользователь:</h5>
            <form id='form-add-user' class='form-add-user mb-1' method='post' action='<?php echo $routes['store']; ?>'>
                <div class='form-add-user__row'>
                    <label for="email">Почта</label>
                    <input type="email" name="email" placeholder='почта' class='form-add__input theme-border' required>
                </div>
                <div class='form-add-user__row'>
                    <label for="password">Пароль</label>
                    <input type="password" name="password" placeholder='пароль' class='form-add__input theme-border' value='111' required>
                    <p class='ps-3'><i>Пароль по умолчанию: 111</i></p>
                </div>
                <div class='form-add-user__row'>
                    <label for="is_admin">Админ.права</label>
                    <select name="is_admin" class='form-add__input theme-border pe-4'>
                        <option value=0>Нет</option>
                        <option value=1>Да</option>
                    </select>
                </div>

                <input type="submit" value="Добавить" class='form-add__input theme-bg-сolor-white theme-bg-сolor-with-hover theme-border px-4 mt-1'>
                <input type="hidden" name="CSRF_JS" value="<?php echo $data['csrf']; ?>">
            </form>
        </article>

        <!-- пользователи -->
        <div class='section-mb'>
            <h5>Пользователи:</h5>
            <table id='user-table' class='w-100'>
                <tr>
                    <th class='<?php echo $css_tr_style; ?>'>Логин</th>
                    <th class='<?php echo $css_tr_style; ?>'>Имя</th>
                    <th class='<?php echo $css_tr_style; ?>'>Админ.права</th>
                </tr>

                <?php foreach ($data['users'] as $user) { ?>
                <tr class='theme-bg-сolor-with-hover'>
                    <td class='table-row p-3 theme-border-bottom'>
                        <span class='user-table__content'><?php echo $user['login']; ?></span>
                        <button class='user-table__btn user-table__btn-remove' title='удалить пользователя'>✘</button>
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