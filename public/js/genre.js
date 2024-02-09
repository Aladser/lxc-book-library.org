/** URL */
const url = {
    'update': '/genre/update',
    'store': '/genre/store',
    'destroy': '/genre/destroy'
};
/** блок ошибок */
const errorPrg = document.querySelector('#prg-error');
/** клиентский контроллер жанров */
const genreClientController = new GenreClientController(url, errorPrg);

/** CSRF */
const csrf = document.querySelector("meta[name='csrf']");
/** таблица жанров */
const genreTable = document.querySelector('#genre-table').childNodes[1];
/** контекстное меню строки*/
const genreContextMenu = document.querySelector('.genre-context-menu');

window.addEventListener('DOMContentLoaded', function(e) {
    document.oncontextmenu = () => false;
    window.addEventListener('click', () => genreContextMenu.classList.remove('genre-context-menu--active'));

    // контекстное меню жанра
    document.querySelectorAll('.table-row').forEach(row => {
        row.addEventListener('contextmenu', e => openTableRowContextMenu(e));
    });
    // изменить жанра
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
    tr.oncontextmenu = e => openTableRowContextMenu(e);
};

/** ПКМ по жанру */
function openTableRowContextMenu(e) {
    genreContextMenu.style.left = (e.pageX - 10)+'px';
    genreContextMenu.style.top = (e.pageY - 10)+'px';
    genreContextMenu.classList.add('genre-context-menu--active');
    genreClientController.setSelectedGenre(e.target);
}