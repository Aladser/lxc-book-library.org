/** контекстное меню строки*/
const authorContextMenu = document.querySelector('.author-context-menu');
/** строки таблицы авторов */
const authorRows = document.querySelectorAll('.table-row');

window.addEventListener('DOMContentLoaded', function(e) {
    document.oncontextmenu = () => false;
});

// нажатия правой кнопкой мыши на строке автора
authorRows.forEach(row => {
    row.addEventListener('contextmenu', function(e) {
        authorContextMenu.style.left = (e.pageX-10)+'px';
        authorContextMenu.style.top = (e.pageY-5)+'px';
    });
}); 