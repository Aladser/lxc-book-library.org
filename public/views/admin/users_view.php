<div class='container'>
    <p id='prg-error' class='prg-error'></p>
    <section class='content-section section-mb'>
        <h3 class='section-mb'>Пользователи с авторизацией на сайте</h3>

        <!-- пользователи -->
        <div class='section-mb'>
            <table id='author-table' class='w-100'>
                <tr>
                    <th class='<?php echo $css_tr_style; ?>'>Логин</th>
                    <th class='<?php echo $css_tr_style; ?>'>Имя</th>
                    <th class='<?php echo $css_tr_style; ?>'>Админ.права</th>
                </tr>

                <?php foreach ($data['users'] as $user) { ?>
                <tr>
                    <td class='table-row p-3 theme-border-bottom'><?php echo $user['login']; ?></td>
                    <td class='table-row p-3 theme-border-bottom'><?php echo $user['nickname']; ?></td>
                    <td class='table-row p-3 theme-border-bottom'><?php echo $user['is_admin'] ? 'есть' : 'нет'; ?></td>
                </tr>
                <?php }?>
            </table>
        </div>
    </section>

    <a href="<?php echo $routes['show']; ?>" class="d-block button-basic theme-border theme-border-radius mx-auto mb-2">Назад</a>
</div>