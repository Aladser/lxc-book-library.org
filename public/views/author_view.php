<div class='container w-50'>
    <section class='text-center mx-auto mb-3'>
        <h3 class='section-mb'>Авторы</h3>
        <table class='w-75 mx-auto'>
            <?php foreach ($data['authors'] as $author) { ?>
            <tr>
                <td class='table-row p-3 cursor-pointer theme-bg-сolor-white theme-border-bottom'>
                    <?php echo $author['name'].' '.$author['surname']; ?>
                </td>
            </tr>
            <?php }?>
        </table>
    </section>
    <a href="<?php echo $routes['show']; ?>" class="d-block button-basic theme-border theme-border-radius mx-auto mb-2">Назад</a>

    <div class='author-context-menu position-absolute'>
        <button class='author-context-menu__btn author-context-menu__btn-edit theme-border-top theme-border-start theme-border-end theme-bg-сolor-white'>Изменить</button>
        <button class='author-context-menu__btn author-context-menu__btn-remove theme-border theme-bg-сolor-white'>Удалить</button>
    </div>
</div>