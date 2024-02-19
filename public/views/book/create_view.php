<div class='container'>
        <!-- форма добавления книги -->
        <article class='w-50 mx-auto mb-3'>
            <h5 class='text-center mb-3'>Новая книга</h5>
            <form id='form-add-user' class='form-add-users' method='post' action='<?php echo $routes['book_store']; ?>'>
                <!-- Название -->
                <div class='form-add-user__row'>
                    <label for="name">Название</label>
                    <input type="text" name="name" class='form-add__input theme-border' required>
                </div>
                <!-- Автор -->
                <div class='form-add-user__row'>
                    <label for="author">Автор</label>
                    <select name="author" class='form-add__input theme-border' required>
                        <option></option>
                        <?php foreach ($data['authors'] as $author) { ?>
                            <option><?php echo $author['name'].' '.$author['surname']; ?></option>
                        <?php } ?>
                    </select>
                </div>
                <!-- Жанр -->
                <div class='form-add-user__row'>
                <label for="genre">Жанр</label>
                    <select name="genre" class='form-add__input theme-border' required>
                        <option></option>
                        <?php foreach ($data['genres'] as $genre) { ?>
                            <option><?php echo $genre; ?></option>
                        <?php } ?>
                    </select>
                </div>
                <!-- Год -->
                <div class='form-add-user__row'>
                    <label for="year">Год</label>
                    <input type="number" name="year" class='form-add__input theme-border' min=1000 max=2100 value='<?php echo date('Y'); ?>' required>
                </div>
                <!-- Изображение -->
                <div class='form-add-user__row'>
                    <label for="picture">Изображение</label>
                    <input type="text" name="picture" class='form-add__input theme-border' >
                </div>
                <!-- Описание -->
                <div class='form-add-user__row'>
                    <label for="description" class='text-center w-100 mb-1'>Описание</label>
                    <textarea name="description" class='form-add__input theme-border w-100' rows="10"></textarea>
                </div>

                <input type="submit" value="Добавить" class='button-basic button-wide d-block mx-auto'>
                <input type="hidden" name="CSRF" value="<?php echo $data['csrf']; ?>">
            </form>
        </article>

        <a href="<?php echo $routes['home']; ?>" class="button-basic button-wide d-block mx-auto">На главную</a>
</div>