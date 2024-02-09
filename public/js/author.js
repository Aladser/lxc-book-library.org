/** блок ошибок */
const errorPrg = document.querySelector('#prg-error');
/** клиентский контроллер авторов */
const authorClientController = new AuthorClientController(errorPrg);

/** CSRF */
const csrf = document.querySelector("meta[name='csrf']");
/** таблица авторов */
const authorTable = document.querySelector('#author-table').childNodes[1];
/** базовое CSS имя контекстного меню автора*/
const authorContextMenuClassName = 'author-context-menu';
/** контекстное меню автора */
const authorContextMenu = new ContextMenu(authorContextMenuClassName);

window.addEventListener('DOMContentLoaded', function(e) {
    document.oncontextmenu = () => false;
    window.addEventListener('click', () => authorContextMenu.hide());

    // контекстное меню автора
    document.querySelectorAll('.table-row').forEach(row => {
        row.addEventListener('contextmenu', e => openContextMenu(e));
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
    tr.oncontextmenu = e => openContextMenu(e);
};

/** ПКМ по автору */
function openContextMenu(e) {
    addAuthorForm.reset();
    errorPrg.textContent = '';
    authorContextMenu.show(e);
    authorClientController.setSelectedAuthor(e.target);
}