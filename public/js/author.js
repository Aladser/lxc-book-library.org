/** URL */
const url = {
    'update': '/author/update',
    'store': '/author/store',
    'destroy': '/author/destroy'
};
/** блок ошибок */
const errorPrg = document.querySelector('#prg-error');
/** клиентский контроллер авторов */
const authorClientController = new AuthorClientController(url, errorPrg);

/** CSRF */
const csrf = document.querySelector("meta[name='csrf']");
/** таблица авторов */
const authorTable = document.querySelector('#author-table').childNodes[1];
/** контекстное меню строки*/
const authorContextMenu = document.querySelector('.author-context-menu');

window.addEventListener('DOMContentLoaded', function(e) {
    document.oncontextmenu = () => false;
    window.addEventListener('click', () => authorContextMenu.classList.remove('author-context-menu--active'));

    // контекстное меню автора
    document.querySelectorAll('.table-row').forEach(row => {
        row.addEventListener('contextmenu', e => openTableRowContextMenu(e));
    });
    // изменить автора
    document.querySelectorAll('.author-context-menu__btn-edit').forEach(btn => {
        btn.onclick = () => authorClientController.edit(csrf);
    });
    // удалить автора
    document.querySelectorAll('.author-context-menu__btn-remove').forEach(btn => {
        btn.onclick = () => authorClientController.destroy(csrf);
    });
});

/** форма добавления автора*/
const addAuthorForm = document.querySelector('#form-add-author');
addAuthorForm.onsubmit = async(e) => {
    let tr = await authorClientController.store(e, authorTable);
    tr.oncontextmenu = e => openTableRowContextMenu(e);
};

/** ПКМ по автору */
function openTableRowContextMenu(e) {
    authorContextMenu.style.left = (e.pageX - 10)+'px';
    authorContextMenu.style.top = (e.pageY - 10)+'px';
    authorContextMenu.classList.add('author-context-menu--active');
    authorClientController.setSelectedAuthor(e.target);
}