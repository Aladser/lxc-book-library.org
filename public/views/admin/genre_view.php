<div class='container'>
    <section class='content-section section-mb'>
        <p id='prg-error' class='prg-error'></p>
        <h3 class='section-mb'>Жанры</h3>

        <article>
            <h5>Новый жанр:</h5>
            <form id='form-add-genre' class='form-add section-mb'>
                <input type="text" name="name" placeholder='название' class='form-add__input theme-border p-1' required>
                <input type="submit" value="Добавить" class='form-add__input theme-bg-сolor-white theme-border py-1 px-4'>
                <input type="hidden" name="CSRF" value="<?php echo $data['csrf']; ?>">
            </form>
        </article>

        <article>
            <h5>Жанры:</h5>
            <table id='genre-table' class='w-100'>
                <?php for ($i = 0; $i < count($data['genres']); ++$i) {
                    // первой строке добавляется верхняя граница
                    $css_tr_style = 'table-row p-3 cursor-pointer theme-bg-сolor-white theme-border-bottom';
                    if ($i === 0) {
                        $css_tr_style .= ' theme-border-top';
                    }
                    ?>
                <tr>
                    <td class='<?php echo $css_tr_style; ?>'>
                        <?php echo $data['genres'][$i]; ?>
                    </td>
                </tr>
                <?php }?>
            </table>
        </article>
    </section>

    <a href="<?php echo $routes['show']; ?>" class="d-block button-basic theme-border theme-border-radius mx-auto mb-2">Назад</a>

    <!-- контекстное меню -->
    <div class='context-menu position-absolute'>
        <button class='context-menu__btn context-menu__btn-remove theme-border theme-bg-сolor-white'>Удалить</button>
    </div>
</div>