<container>
    <div class='content-width'>
        <form method='POST' action=<?php echo $routes['article_update']; ?>>
            <input type="hidden" name='CSRF' value=<?php echo $data['csrf']; ?>>
            <input type="hidden" name="id" value="<?php echo $data['id']; ?>">
            <input type="hidden" name="author" value="<?php echo $data['login']; ?>" >

            <input type="text" name="title" value=<?php echo $data['title']; ?> 
            class='d-block ps-3 p-2 mb-2 w-100 theme-border' placeholder='Заголовок' required>

            <textarea name='summary' class="input-group-prepend form-control mb-2 w-100 theme-border" rows='2' 
            placeholder='Краткое содержание' ><?php echo $data['summary']; ?></textarea>

            <textarea name='content' class="input-group-prepend form-control mb-2 w-100 theme-border" rows='10' 
            placeholder='Содержание' required><?php echo $data['content']; ?></textarea>

            <input type="submit" value='Сохранить' class='theme-bg-сolor-btn button-basic mb-1'>
            <a href="<?php echo $data['show_url']; ?>" class='theme-bg-сolor-btn button-basic'>Назад</a>
        </form>
        <p id='prg-error' class='pb-4 text-center text-danger fw-bolder'><?php echo $data['error']; ?></p>
    </div>
</container>