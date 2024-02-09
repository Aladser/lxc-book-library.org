/** CSRF */
const csrf = document.querySelector("meta[name='csrf']");
/** контекстное меню строки*/
const authorContextMenu = document.querySelector('.author-context-menu');
/** строки таблицы авторов */
const authorRows = document.querySelectorAll('.table-row');
/** кнопки изменения автора */
const btnEditList = document.querySelectorAll('.author-context-menu__btn-edit');
/** кнопки удаления автора */
const btnRemoveList = document.querySelectorAll('.author-context-menu__btn-remove');
/** блок ошибок */
const prgError = document.querySelector('#prg-error');
/** выбранный автор */
let selectedAuthorElem = false;
let selectedAuthorName = false;
// URL author->update()
let authorUpdateURL = '/author/update';

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
        selectedAuthorName = selectedAuthorElem.textContent.trim();
    });
});

// изменить автора
btnEditList.forEach(btn => {
    btn.addEventListener('click', function(){
        let [name, surname] = selectedAuthorName.split(' ');
        selectedAuthorElem.innerHTML = `
            <form id='table-row__form-edit'>
                <input type='text' name='name' class='table-row__input-author theme-border' value='${name}'>
                <input type='text' name='surname' class='table-row__input-author theme-border' value='${surname}'>
                <input type='button' id='table-row__btn-cancel' class='table-row__btn theme-border' value='Отмена'>
                <input type='submit' class='table-row__btn theme-border' value='OK'>
                <input type="hidden" name="CSRF" value="${csrf.content}">
            </form>
        `;
        document.querySelector('#table-row__form-edit').onsubmit = (e) => saveAuthorEditing(e);
        document.querySelector('#table-row__btn-cancel').onclick = cancelAuthorEditing;
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

    let newAuthorName = `${e.target.name.value} ${e.target.surname.value}`;
    // если не изменилось имя
    if (selectedAuthorName === newAuthorName) {
        cancelAuthorEditing();
        return;
    }

    let formData = new FormData(e.target);
    formData.set('current_author_name', selectedAuthorName);
    ServerRequest.execute(
        authorUpdateURL,
        (data) => processSaveResponse(data, newAuthorName),
        "post",
        prgError,
        formData
    );
}

/** обработать ответ сервера на изменение автора
 * @param {*} responseData ответ сервера
 * @param {*} newAuthorName новое имя
 */
function processSaveResponse(responseData, newAuthorName) {
    try {
        let response = JSON.parse(responseData);
        if (response.is_updated == 1) {
            selectedAuthorElem.innerHTML = newAuthorName;
            prgError.textContent = '';
        } else {
            prgError.textContent = response.description;
        }
    } catch(exception) {
        prgError.textContent = exception;
        console.log(responseData);
    }
}

/** отменить изменение автора */
function cancelAuthorEditing() {
    selectedAuthorElem.innerHTML = selectedAuthorName;
    prgError.textContent = '';
}