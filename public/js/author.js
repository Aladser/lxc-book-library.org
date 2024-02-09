const authorClientController = new AuthorClientController();

/** контекстное меню строки*/
const authorContextMenu = document.querySelector('.author-context-menu');

window.addEventListener('DOMContentLoaded', function(e) {
    document.oncontextmenu = () => false;

    window.addEventListener('click', function(e) {
        authorContextMenu.classList.remove('author-context-menu--active');
    });

    // ПКМ по автору
    document.querySelectorAll('.table-row').forEach(row => {
        row.addEventListener('contextmenu', function(e) {
            authorContextMenu.style.left = (e.pageX - 10)+'px';
            authorContextMenu.style.top = (e.pageY - 10)+'px';
            authorContextMenu.classList.add('author-context-menu--active');
            authorClientController.setSelectedAuthor(this);
        });
    });
});