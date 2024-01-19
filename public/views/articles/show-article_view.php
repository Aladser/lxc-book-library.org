<container>
    <div class='content-width'>
        <!-- кнопки -->
        <section>
            <?php if ($data['login'] === $data['article']['author']) { ?>
                <a href=<?php echo $routes['article_edit']; ?> class='theme-bg-сolor-btn ref'>Редактировать</a>
                <a href="<?php echo $routes['article_remove']; ?>" class='theme-bg-сolor-btn ref'>Удалить</a>
            <?php } ?>
            <a href=<?php echo $routes['home']; ?> class='theme-bg-сolor-btn ref rounded'>Назад</a>
        </section>

        <!-- статья --> 
        <section class='p-2 text-center theme-border-start theme-border-end'>
            <h2><?php echo $data['article']['title']; ?></h2>
            <p>Автор: <?php echo $data['article']['author']; ?></p>
            <p><?php echo nl2br($data['article']['content']); ?></p>
        </section>

        <div><hr class='theme-border'></div>

        <!-- комментарии -->
        <section class='p-2 text-center theme-border-start theme-border-end'>
            <h4 class='text-start ps-2'> Комментарии </h4>
            <section id='comment-list'>
                <?php foreach ($data['comments'] as $comment) { ?>
                    <article class='comment-list__item mb-2' id='<?php echo "id-{$comment['id']}"; ?>'>
                        <p class='text-start m-0 ps-2 fw-bolder'><?php echo $comment['login']; ?></p>
                        <p class='text-start m-0 py-2 ps-3 fs-5'><?php echo nl2br($comment['content']); ?></p>
                        <div class='text-end m-0 pe-2'>
                            <?php if ($data['login'] === $comment['login']) { ?>
                                <input type='submit' class='comment-list__btn-remove border-0' title='Удалить' value='🗑'>
                            <?php }?>
                            <span><?php echo $comment['time']; ?></span>
                            </div>
                    </article>
                <?php } ?>
            </section>
            <div class='input-group pb-2'>
                <form method='post' class='d-flex justify-content-between w-100' id='form-send-message'>
                    <input type="hidden" name='CSRF' value=<?php echo $data['csrf']; ?>>
                    <input type="hidden" name="article" value="<?php echo $data['article']['id']; ?>" >
                    <input type="hidden" name="author" value="<?php echo $data['login']; ?>" >
                    <textarea class="input-group-prepend form-control theme-border" 
                    rows='3' placeholder='Сообщение' name='message' id='form-send-message__msg' required></textarea>
                    <button type="submit" class='btn-send-msg theme-bg-сolor-btn ' title='Отправить'>Отправить</button>
                </form>
            </div>
        </section>

        <!-- ошибки -->
        <p id='prg-error' class='pb-4 text-center text-danger fw-bolder'></p>
    </div>
</container>