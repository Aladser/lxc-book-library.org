/** клиентский контроллер авторов */
const authorClientController = new AuthorClientController();

window.addEventListener('DOMContentLoaded', function(e) {
    document.oncontextmenu = () => false;
    window.addEventListener('click', () => authorClientController.hideContextMenu());

    // ПКМ по автору
    document.querySelectorAll('.table-row').forEach(row => {
        row.addEventListener('contextmenu', function(e) {
            authorClientController.authorContextMenu.style.left = (e.pageX - 10)+'px';
            authorClientController.authorContextMenu.style.top = (e.pageY - 10)+'px';
            authorClientController.authorContextMenu.classList.add('author-context-menu--active');
            authorClientController.setSelectedAuthor(this);
        });
    });

    // изменить автора
    document.querySelectorAll('.author-context-menu__btn-edit').forEach(btn => {
        btn.onclick = e => authorClientController.edit(e);
    });
    // удалить автора
    document.querySelectorAll('.author-context-menu__btn-remove').forEach(btn => {
        btn.addEventListener('click', e => authorClientController.destroy(e));
    });
});