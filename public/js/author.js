/** клиентский контроллер авторов */
const authorClientController = new AuthorClientController();
/** CSRF */
const csrf = document.querySelector("meta[name='csrf']");

window.addEventListener('DOMContentLoaded', function(e) {
    document.oncontextmenu = () => false;
    window.addEventListener('click', () => authorClientController.hideContextMenu());

    // контекстное меню автора
    document.querySelectorAll('.table-row').forEach(row => {
        row.addEventListener('contextmenu', e => clickTableRowContextMenu(e));
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
    let tr = await authorClientController.store(e);
    tr.oncontextmenu = e => clickTableRowContextMenu(e);
};

/** ПКМ по автору */
function clickTableRowContextMenu(e) {
    authorClientController.authorContextMenu.style.left = (e.pageX - 10)+'px';
    authorClientController.authorContextMenu.style.top = (e.pageY - 10)+'px';
    authorClientController.authorContextMenu.classList.add('author-context-menu--active');
    authorClientController.setSelectedAuthor(e.target);
}