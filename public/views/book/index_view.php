<div class='container'>
    <?php if (!empty($data['books'])) {?>
        <article class='theme-border theme-shadow theme-mb p-4'>
            <h4 class='text-center'>Библиография</h4>
            <?php if ($data['is_admin']) {?>
                <a href="#" class="button-basic d-block float-end mb-2">Добавить книгу</a>
            <?php } ?>
            <table class='book-table theme-mb w-100'>
                <?php foreach ($data['books'] as $book) { ?>
                    <tr class='book-table__row theme-border-bottom'>
                        <td class='book-table__author'>
                            <a href="<?php echo $routes['book_show'].$book['id']; ?>"><?php echo $book['author_name'].' - '.$book['name']; ?></a>
                        </td>
                        <td><?php echo $book['genre']; ?></td>
                    </tr>
                <?php }?>
            </table>
        </article>
    <?php } ?>
</div>
