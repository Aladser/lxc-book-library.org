<div class='container'>
    <?php if (!empty($data['books'])) {?>
        <article class='theme-border theme-shadow section-mb p-4'>
            <h4 class='text-center'>Библиография</h4>
            <table class='book-table section-mb w-100'>
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


    <div class='book theme-border theme-shadow'>
        <div class='book__content'>
            <article class='image-block mx-auto'>
                <img class='d-block mx-auto h-100 object-fit-cover' src="storage/data/images/buduschee.jpeg" alt="Будущее">
            </article>
            <div class='w-50'>
                <p class='h3 theme-darker-color'>Будущее</p>
                <p class='h6 theme-darker-color'>Дмитрий Глуховский</p>
                <p>Научно-фантастический роман</p>
                <p class="fst-italic">2013 год</p>
            </div>
        </div>
        <div class='p-2'>
            НА ЧТО ТЫ ГОТОВ РАДИ ВЕЧНОЙ ЖИЗНИ? Уже при нашей жизни будут сделаны открытия, которые позволят людям оставаться вечно молодыми. 
            Смерти больше нет. Наши дети не умрут никогда. Добро пожаловать в будущее. В мир, населенный вечно юными, совершенно здоровыми, счастливыми людьми. 
            Но будут ли они такими же, как мы? Нужны ли дети, если за них придется пожертвовать бессмертием? Нужна ли семья тем, кто не может завести детей? 
            Нужна ли душа людям, тело которых не стареет?.
        </div>
    </div>
</div>
