<div class='container'>
    <section class='content-section section-mb'>
        <p id='prg-error' class='prg-error'></p>
        <h3 class='section-mb'>Жанры</h3>

        <article>
            <h5>Новый жанр:</h5>
            <form id='form-add-genre' class='form-add section-mb'>
                <input type="text" name="name" placeholder='название' class='form-add__input theme-border' required>
                <input type="submit" value="Добавить" class='form-add__btn'>
                <input type="hidden" name="CSRF" value="<?php echo $data['csrf']; ?>">
            </form>
        </article>

        <article>
            <h5>Жанры:</h5>
            <table id='genre-table' class='w-100'>
                <?php for ($i = 0; $i < count($data['genres']); ++$i) {
                    // первой строке добавляется верхняя граница
                    $css_tr_style = 'table-row p-3 theme-bg-сolor-with-hover theme-border-bottom';
                    if ($i === 0) {
                        $css_tr_style .= ' theme-border-top';
                    }
                    ?>
                <tr>
                    <td class='<?php echo $css_tr_style; ?>'>
                        <span class='genre-table__content'><?php echo $data['genres'][$i]; ?></span>
                        <button class='genre-table__btn-remove' title='удалить жанр'>✘</button>
                    </td>
                </tr>
                <?php }?>
            </table>
        </article>
    </section>

    <a href="<?php echo $routes['show']; ?>" class="d-block button-basic theme-border theme-border-radius mx-auto mb-2">Назад</a>
</div>