<container>
    <div class='content-width'>
        <section class='ps-2'>
            <a href=<?php echo $routes['article_create']; ?> class='theme-bg-сolor-btn ref mb-2'>Новая тема</a>
        </section>

        <section class='mb-1'>
            <article class='d-flex p-1 theme-border-start theme-border-end'>
                <p class='w-25 m-0 fw-bolder p-1 text-secondary'>Название</p>
                <p class='w-50 text-center m-0 fw-bolder p-1 text-secondary'>Описание</p>
                <p class='w-25 text-center m-0 fw-bolder p-1 text-secondary'>Автор</p>
            </article>
            <?php foreach ($data['articles'] as $article) { ?>
                <a href=<?php echo $article['url']; ?> class='text-dark'>
                    <article class='table-article-row'>
                        <p class='w-25 m-0 p-2'><?php echo $article['title']; ?></p>
                        <p class='w-50 m-0 text-center p-2'><?php echo $article['summary']; ?></p>
                        <p class='w-25 m-0 text-center p-2'><?php echo $article['author']; ?></p>
                    </article>
                </a>
            <?php }?>
        </section>
        
        <!-- страницы показа статей (по 10) -->
        <section class='p-2 fs-5'>
        <?php foreach ($data['page-list'] as $page) {?>
            <a class="<?php echo $page['css']; ?>" href=<?php echo $page['url']; ?>> <?php echo $page['number']; ?> </a>  
        <?php } ?>
        </section>
    </div>
</container>