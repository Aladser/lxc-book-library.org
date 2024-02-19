<?php if (!empty($data['books'])) {?>
    <section class='theme-border theme-shadow theme-mb p-4'>
        <h4 class='text-center mb-3'>Библиография</h4>
        <!-- кнопка добавления книги -->
        <?php if ($data['is_admin']) {?>
            <a href=<?php echo $routes['book_create']; ?> class="button-basic btn-add-book">Добавить книгу</a>
        <?php } ?>
        <!-- список книг -->
        <table class='book-table theme-mb w-100'>
            <?php foreach ($data['books'] as $book) { ?>
                <tr class='book-table__row theme-border-bottom'>
                    <td class='book-table__author'>
                        <a href="<?php echo $routes['book_show'].$book['id']; ?>" class='d-inline-block p-2'><?php echo $book['author_name'].' - '.$book['name']; ?></a>
                    </td>
                    <td><?php echo $book['genre']; ?></td>
                </tr>
            <?php }?>
        </table>
    </section>
<?php } ?>
