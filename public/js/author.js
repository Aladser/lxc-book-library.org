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
});