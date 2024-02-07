/** контекстное меню строки*/
const authorContextMenu = document.querySelector('.author-context-menu');
/** строки таблицы авторов */
const authorRows = document.querySelectorAll('.table-row');
/** кнопки изменения автора */
const btnEditList = document.querySelectorAll('.author-context-menu__btn-edit');
/** кнопки удаления автора */
const btnRemoveList = document.querySelectorAll('.author-context-menu__btn-remove');
/** выбранный автор */
let selectedAuthorElem = false;
let selectedAuthor = false;

window.addEventListener('DOMContentLoaded', function(e) {
    document.oncontextmenu = () => false;
});

window.addEventListener('click', function(e) {
    authorContextMenu.classList.remove('author-context-menu--active');
});

// ПКМ по автору
authorRows.forEach(row => {
    row.addEventListener('contextmenu', function(e) {
        authorContextMenu.style.left = (e.pageX - 10)+'px';
        authorContextMenu.style.top = (e.pageY - 10)+'px';
        authorContextMenu.classList.add('author-context-menu--active');
        selectedAuthorElem = this;
    });
});

// изменить автора
btnEditList.forEach(btn => {
    btn.addEventListener('click', function(){
        selectedAuthor = selectedAuthorElem.textContent.trim();
        let [name, surname] = selectedAuthor.split(' ');
        selectedAuthorElem.innerHTML = `
            <form id='table-row__form-edit'>
                <input type='text' name='name' class='table-row__input-author theme-border' value='${name}'>
                <input type='text' name='surname' class='table-row__input-author theme-border' value='${surname}'>
                <input type='button' id='table-row__btn-cancel' class='table-row__btn theme-border' value='Отмена'>
                <input type='submit' class='table-row__btn theme-border' value='OK'>
            </form>
        `;
        document.querySelector('#table-row__btn-cancel').onclick = cancelAuthorEditing;
        document.querySelector('#table-row__form-edit').onsubmit = (e) => saveAuthorEditing(e);
    });
});

// удалить автора
btnRemoveList.forEach(btn => {
    btn.addEventListener('click', function(){
        console.log('удалить ' + selectedAuthorElem.textContent.trim());
    });
});

/** сохранить изменение автора */
function saveAuthorEditing(e) {
    e.preventDefault();
    console.log(e.target.name.value);
    console.log(e.target.surname.value);
    selectedAuthorElem.innerHTML = selectedAuthor;
}

/** отменить изменение автора */
function cancelAuthorEditing() {
    selectedAuthorElem.innerHTML = selectedAuthor;
}