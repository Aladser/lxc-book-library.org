<div class='container w-50'>
    <section class='text-center mx-auto mb-3'>
        <h3 class='section-mb'>Авторы</h3>
        <table class='w-75 mx-auto'>
            <?php foreach ($data['authors'] as $author) { ?>
            <tr>
                <td class='table-row p-3 cursor-pointer theme-bg-сolor-white theme-border-bottom position-relative'>
                    <span class='table-row__author'><?php echo $author['name'].' '.$author['surname']; ?></span>
                    <div class='position-absolute top-0 start-0 mt-1'>
                        <button class='table-row__btn table-row__btn-edit'>✏</button>
                        <button class='table-row__btn table-row__btn-remove'>🗑</button>
                    </div>
                </td>
            </tr>
            <?php }?>
        </table>
    </section>
    <a href="<?php echo $routes['show']; ?>" class="d-block button-basic theme-border theme-border-radius mx-auto mb-2">Назад</a>
</div>