<div class='container w-50'>
    <section class='text-center mx-auto mb-3'>
        <h3 class='admin-section__mb'>Авторы</h3>
        <table class='w-75 mx-auto'>
            <?php foreach ($data['authors'] as $author) { ?>
            <tr><td class='p-3 cursor-pointer theme-border-bottom'><?php echo $author['name'].' '.$author['surname']; ?></td></tr>
            <?php }?>
        </table>
    </section>
    <a href="<?php echo $routes['show']; ?>" class="d-block button-basic theme-border theme-border-radius mx-auto mb-2">Назад</a>
</div>