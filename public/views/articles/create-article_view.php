<container>
    <div class='content-width'>
        <form method='POST' id='form-create-article' action=<?php echo $routes['article_store']; ?>>
            <input type="hidden" name='CSRF' value=<?php echo $data['csrf']; ?>>

            <input type="text" class='w-100 d-block ps-3 p-2 mb-2 text-secondary theme-border' 
            name="title" placeholder='Заголовок' value='<?php echo $data['title']; ?>' required>

            <textarea class="w-100 input-group-prepend form-control mb-2 text-secondary theme-border" 
            name='summary' rows='2'placeholder='Краткое содержание'></textarea>

            <textarea class="w-100 input-group-prepend form-control mb-2 text-secondary theme-border" 
            name='content' rows='10' placeholder='Содержание' required></textarea>

            <input type="submit" value='Добавить' class='theme-bg-сolor-btn button-basic mb-1'>
            <a href=<?php echo $routes['home']; ?> class='theme-bg-сolor-btn button-basic'>Назад</a>
        </form>
        <p id='prg-error' class='pb-4 text-center text-danger fw-bolder'><?php echo $data['error']; ?> </p>
    </div>
</container>