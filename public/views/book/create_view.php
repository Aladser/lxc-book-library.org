<div class='container'>
        <!-- форма добавления пользователя -->
        <article class='w-50 mx-auto theme-mb'>
            <h5>Новый пользователь:</h5>
            <form id='form-add-user' class='form-add-users' method='post' action='<?php echo $routes['book_store']; ?>'>
                <div class='form-add-user__row'>
                    <label for="name">Название</label>
                    <input type="text" name="name" class='form-add__input theme-border' required>
                </div>
                <div class='form-add-user__row'>
                    <label for="author">Автор</label>
                    <input type="text" name="author" class='form-add__input theme-border' required>
                </div>
                <div class='form-add-user__row'>
                    <label for="genre">Жанр</label>
                    <input type="text" name="genre" class='form-add__input theme-border' required>
                </div>
                <div class='form-add-user__row'>
                    <label for="year">Год</label>
                    <input type="text" name="year" class='form-add__input theme-border' required>
                </div>
                <div class='form-add-user__row'>
                    <label for="picture">Изображение</label>
                    <input type="text" name="picture" class='form-add__input theme-border' required>
                </div>
                <div class='form-add-user__row'>
                    <label for="description" class='text-center w-100 mb-1'>Описание</label>
                    <textarea name="description" class='w-100 theme-border' rows="10"></textarea>
                </div>

                <input type="submit" value="Добавить" class='button-basic button-wide d-block mx-auto'>
                <input type="hidden" name="CSRF" value="<?php echo $data['csrf']; ?>">
            </form>
        </article>

        <a href="<?php echo $routes['home']; ?>" class="button-basic button-wide d-block mx-auto">На главную</a>
</div>