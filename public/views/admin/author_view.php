<div class='container'>
    <p id='prg-error' class='prg-error'></p>
    <section class='content-section section-mb'>
        <h3 class='section-mb'>Авторы книг</h3>

        <!-- форма добавления автора -->
        <article>
            <h5>Новый автор:</h5>
            <form id='form-add-author' class='section-mb'>
                <input type="text" name="name" placeholder='имя' class='form-add__input theme-border' required>
                <input type="text" name="surname" placeholder='фамилия' class='form-add__input theme-border' required>
                <input type="submit" value="Добавить" class=' form-add__input theme-bg-сolor-with-hover theme-border px-4'>
                <input type="hidden" name="CSRF" value="<?php echo $data['csrf']; ?>">
            </form>
        </article>

        <!-- авторы -->
        <article class='section-mb'>
            <h5>Авторы:</h5>
            <table id='author-table' class='w-100'>
                <?php for ($i = 0; $i < count($data['authors']); ++$i) {
                    // первой строке добавляется верхняя граница
                    $css_tr_style = 'table-row p-3 cursor-pointer theme-bg-сolor-with-hover theme-border-bottom';
                    if ($i === 0) {
                        $css_tr_style .= ' theme-border-top';
                    }
                    ?>
                <tr>
                    <td class='<?php echo $css_tr_style; ?>'>
                        <span class='author-table__content'><?php echo $data['authors'][$i]['name'].' '.$data['authors'][$i]['surname']; ?></span>
                        <button class='author-table__btn author-table__btn-edit' title='изменить автора'>✏</button>
                        <button class='author-table__btn author-table__btn-remove' title='удалить автора'>✘</button>
                    </td>
                </tr>
                <?php }?>
            </table>
        </article>
    </section>

    <a href="<?php echo $routes['show']; ?>" class="d-block button-basic theme-border theme-border-radius mx-auto mb-2">Назад</a>
</div>