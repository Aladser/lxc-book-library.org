/** блок ошибок */
const errorPrg = document.querySelector('#prg-error');
/** клиентский контроллер жанров */
const genreClientController = new GenreClientController(errorPrg);


/** базовое CSS имя контекстного меню автора*/
const genreContextMenuClassName = 'genre-context-menu';
/** контекстное меню автора */
const genreContextMenu = new ContextMenu(genreContextMenuClassName);


/** CSRF */
const csrf = document.querySelector("meta[name='csrf']");
/** таблица жанров */
const genreTable = document.querySelector('#genre-table').childNodes[1];


window.addEventListener('DOMContentLoaded', function() {
    document.oncontextmenu = () => false;
    window.addEventListener('click', () => genreContextMenu.hide());

    // контекстное меню жанра
    document.querySelectorAll('.table-row').forEach(row => {
        row.oncontextmenu = e => openContextMenu(e);
    });
    // изменить жанр
    document.querySelectorAll('.genre-context-menu__btn-edit').forEach(btn => {
        btn.onclick = () => genreClientController.edit(csrf);
    });
    // удалить жанр
    document.querySelectorAll('.genre-context-menu__btn-remove').forEach(btn => {
        btn.onclick = () => genreClientController.destroy(csrf);
    });
});

/** форма добавления жанра*/
const addGenreForm = document.querySelector('#form-add-genre');
addGenreForm.onsubmit = async(e) => {
    let tr = await genreClientController.store(e, genreTable);
    tr.oncontextmenu = e => openContextMenu(e);
};

/** ПКМ по жанру */
function openContextMenu(e) {
    addGenreForm.reset();
    errorPrg.textContent = '';
    genreContextMenu.show(e);
    genreClientController.setSelectedGenre(e.target);
}