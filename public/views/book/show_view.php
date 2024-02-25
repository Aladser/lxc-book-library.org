<div class='content-container theme-border theme-shadow theme-mb'>
    <!-- контейнер книги -->
    <div class='book__container'>
        <article class='book__image'>
            <img class='d-block mx-auto h-100 object-fit-cover' 
            src="<?php echo 'http://'.$site_address.'/'.$data['book']['picture']; ?>" 
            alt="<?php echo $data['book']['name']; ?>"
            >
        </article>
        <article class='book__description'>
            <p class='h3 theme-darker-color'><?php echo $data['book']['name']; ?></p>
            <p class='h6 theme-darker-color'><?php echo $data['book']['author_name']; ?></p>
            <p><?php echo $data['book']['genre']; ?></p>
            <p class="fst-italic"><?php echo $data['book']['year']; ?> год</p>
        </article>
        <div class='book__content'>
        НА ЧТО ТЫ ГОТОВ РАДИ ВЕЧНОЙ ЖИЗНИ? Уже при нашей жизни будут сделаны открытия, которые позволят людям оставаться вечно молодыми. 
        Смерти больше нет. Наши дети не умрут никогда. Добро пожаловать в будущее. В мир, населенный вечно юными, совершенно здоровыми, счастливыми людьми. 
        Но будут ли они такими же, как мы? Нужны ли дети, если за них придется пожертвовать бессмертием? Нужна ли семья тем, кто не может завести детей? 
        Нужна ли душа людям, тело которых не стареет?.
        </div>

        <!-- кнопка редактирования и удаления -->
        <?php if ($data['is_admin']) {?>
        <div class='book__btn-block'>
            <a href="<?php echo $routes['book_edit'].$data['book']['id']; ?>" class="button-basic d-inline-block">Редактировать</a>
            <a href="<?php echo $routes['book_delete'].$data['book']['id']; ?>" class="button-basic d-inline-block">Удалить</a>
        </div>
        <?php } ?>
    </div>
</div>

<a href="<?php echo $routes['home']; ?>" class="button-basic button-wide d-block mx-auto mb-2">На главную</a>
