<div class='container w-50'>
    <p id='prg-error' class='prg-error'></p>
    <section class='text-center mx-auto mb-3'>
        <h3 class='section-mb'>Авторы книг</h3>

        <article class='w-75 mx-auto'>
            <h5>Новый пользователь:</h5>
            <form id='form-add-author' class='section-mb'>
                <input type="text" name="name" placeholder='имя' class='theme-border p-1' required>
                <input type="text" name="surname" placeholder='фамилия' class='theme-border p-1' required>
                <input type="submit" value="Добавить" class='theme-bg-сolor-white theme-border py-1 px-4'>
                <input type="hidden" name="CSRF" value="<?php echo $data['csrf']; ?>">
            </form>
        </article>

        <article class='w-75 mx-auto'>
            <h5>Авторы:</h5>
            <table id='author-table' class='w-100'>
                <?php for ($i = 0; $i < count($data['authors']); ++$i) {
                    // первой строке добавляется верхняя граница
                    $css_tr_style = 'table-row p-3 cursor-pointer theme-bg-сolor-white theme-border-bottom';
                    if ($i === 0) {
                        $css_tr_style .= ' theme-border-top';
                    }
                    ?>
                <tr>
                    <td class='<?php echo $css_tr_style; ?>'>
                        <?php echo $data['authors'][$i]['name'].' '.$data['authors'][$i]['surname']; ?>
                    </td>
                </tr>
                <?php }?>
            </table>
        </article>
    </section>
    <a href="<?php echo $routes['show']; ?>" class="d-block button-basic theme-border theme-border-radius mx-auto mb-2">Назад</a>

    <!-- контекстное меню -->
    <div class='author-context-menu position-absolute'>
        <button class='author-context-menu__btn author-context-menu__btn-edit theme-border-top theme-border-start theme-border-end theme-bg-сolor-white'>Изменить</button>
        <button class='author-context-menu__btn author-context-menu__btn-remove theme-border theme-bg-сolor-white'>Удалить</button>
    </div>
</div>