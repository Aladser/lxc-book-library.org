<div class='container'>
    <section>
        <img src=<?php echo $data['user_photo']; ?>/>
        <div class='d-inline-block ms-4'>
            <div class='fw-bolder fs-5'>ID: <?php echo $data['user_id']; ?></div>
            <div class='fs-2'><?php echo $data['user_name']; ?></div>
        </div>
        <a href="<?php echo $routes['home']; ?>" class="d-block mx-auto button-basic theme-border w-50 mt-4">Назад</a>
    </section>
</div>