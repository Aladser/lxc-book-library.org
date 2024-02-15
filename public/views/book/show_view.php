<div class='container'>

    <div class='book theme-border theme-shadow'>
        <div class='book__content'>
            <article class='image-block mx-auto'>
                <img class='d-block mx-auto h-100 object-fit-cover' src="<?php echo 'http://'.$site_address.'/'.$data['book']['picture']; ?>" alt="<?php echo $data['book']['name']; ?>">
            </article>
            <div class='w-50'>
                <p class='h3 theme-darker-color'><?php echo $data['book']['name']; ?></p>
                <p class='h6 theme-darker-color'><?php echo $data['book']['author_name']; ?></p>
                <p><?php echo $data['book']['genre']; ?></p>
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

    <a href="<?php echo $routes['home']; ?>" class="button-basic button-wide d-block mx-auto">Назад</a>
</div>