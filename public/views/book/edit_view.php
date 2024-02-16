<div class='container'>
        <!-- форма редактирования пользователя -->
        <article class='w-50 mx-auto mb-3'>
            <h5 class='text-center mb-3'>Редактирование пользователя</h5>
            <form id='form-add-user' class='form-add-users' method='post' action='<?php echo $routes['book_update']; ?>'>
                <!-- Название -->
                <div class='form-add-user__row'>
                    <label for="name">Название</label>
                    <input type="text" name="name" class='form-add__input theme-border' required value="<?php echo $data['book']['name']; ?>">
                </div>
                <!-- Автор -->
                <div class='form-add-user__row'>
                    <label for="author">Автор</label>
                    <select name="author" class='form-add__input theme-border' required>
                        <?php foreach ($data['authors'] as $author) { ?>
                            <?php if ($author['name'].' '.$author['surname'] === $data['book']['author_name']) {?>
                                <option selected><?php echo $author['name'].' '.$author['surname']; ?></option>
                            <?php } else {?>
                                <option><?php echo $author['name'].' '.$author['surname']; ?></option>
                            <?php }?>
                        <?php } ?>
                    </select>
                </div>
                <!-- Жанр -->
                <div class='form-add-user__row'>
                <label for="genre">Жанр</label>
                    <select name="genre" class='form-add__input theme-border' required>
                        <?php foreach ($data['genres'] as $genre) { ?>
                            <?php if ($genre == $data['book']['genre']) {?>
                                <option selected><?php echo $genre; ?></option>
                            <?php } else {?>
                                <option><?php echo $genre; ?></option>
                            <?php } ?>
                        <?php } ?>
                    </select>
                </div>
                <!-- Год -->
                <div class='form-add-user__row'>
                    <label for="year">Год</label>
                    <input type="number" name="year" class='form-add__input theme-border' min=1000 max=2100 required value="<?php echo $data['book']['year']; ?>">
                </div>
                <!-- Изображение -->
                <div class='form-add-user__row'>
                    <label for="picture">Изображение</label>
                    <input type="text" name="picture" class='form-add__input theme-border' value="<?php echo $data['book']['picture']; ?>">
                </div>
                <!-- Описание -->
                <div class='form-add-user__row'>
                    <label for="description" class='text-center w-100 mb-1'>Описание</label>
                    <textarea name="description" class='form-add__input theme-border w-100' rows="10"><?php echo $data['book']['description']; ?></textarea>
                </div>

                <input type="submit" value="Сохранить" class='button-basic button-wide d-block mx-auto'>
                <input type="hidden" name="CSRF" value="<?php echo $data['csrf']; ?>">
            </form>
        </article>

        <a href="<?php echo $routes['home']; ?>" class="button-basic button-wide d-block mx-auto">На главную</a>
</div>