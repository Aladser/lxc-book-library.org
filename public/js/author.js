// URL author->update()
let authorUpdateURL = '/author/update';
// URL author->store()
let authorStoreURL = '/author/store';
// URL author->destroy()
let authorDestroyURL = '/author/destroy';

/** CSRF */
const csrf = document.querySelector("meta[name='csrf']");
/** контекстное меню строки*/
const authorContextMenu = document.querySelector('.author-context-menu');
/** таблица авторов */
const authorTable = document.querySelector('#author-table').childNodes[1]; 
/** блок ошибок */
const prgError = document.querySelector('#prg-error');
/** выбранный автор */
let selectedAuthorElem = false;
let selectedAuthorName = false;
/** форма добавления автора*/
const addAuthorForm = document.querySelector('#form-add-author');


window.addEventListener('DOMContentLoaded', function(e) {
    document.oncontextmenu = () => false;

    window.addEventListener('click', function(e) {
        authorContextMenu.classList.remove('author-context-menu--active');
    });

    /** добавить нового автора */
    addAuthorForm.addEventListener('submit', function(e){
        e.preventDefault();

        ServerRequest.execute(
            authorStoreURL,
            data => processStoreResponse(data, e.target),
            "post",
            prgError,
            new FormData(e.target)
        );
    });

    appendButtonListeners();

    // изменить автора
    document.querySelectorAll('.author-context-menu__btn-edit').forEach(btn => {
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
    document.querySelectorAll('.author-context-menu__btn-remove').forEach(btn => {
        btn.addEventListener('click', function(){
            let author_name = new URLSearchParams();
            author_name.set('author_name', selectedAuthorName);
            author_name.set('CSRF', csrf.content);

            ServerRequest.execute(
                authorDestroyURL,
                data => processRemoveResponse(data),
                "post",
                prgError,
                author_name
            );
        });
    });
});


// слушатели кнопок строк
function appendButtonListeners() {
    // ПКМ по автору
    document.querySelectorAll('.table-row').forEach(row => {
        row.addEventListener('contextmenu', function(e) {
            authorContextMenu.style.left = (e.pageX - 10)+'px';
            authorContextMenu.style.top = (e.pageY - 10)+'px';
            authorContextMenu.classList.add('author-context-menu--active');
            selectedAuthorElem = this;
            selectedAuthorName = selectedAuthorElem.textContent.trim();
        });
    });
}

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
        data => processUpdateResponse(data, newAuthorName),
        "post",
        prgError,
        formData
    );
}

/** обработать ответ сервера на изменение автора
 * @param {*} responseData ответ сервера
 * @param {*} newAuthorName новое имя
 */
function processUpdateResponse(responseData, newAuthorName) {
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

/** ответ сервера о добавлении нового автора
 * @param {*} responseData ответ сервера
 * @param {*} form форма добавления
 */
function processStoreResponse(responseData, form) {
    try {
        let response = JSON.parse(responseData);
        if (response.is_added > 0) {
            let cssClass = 'table-row p-3 cursor-pointer theme-bg-сolor-white theme-border-top theme-border-bottom';
            let row = `<tr><td class='${cssClass}'>${form.name.value} ${form.surname.value}</td></tr>`;
            authorTable.innerHTML = row + authorTable.innerHTML;
            prgError.textContent = '';
            appendButtonListeners();
            form.reset();
        } else {
            prgError.textContent = response.description;
        }
    } catch(exception) {
        prgError.textContent = exception;
        console.log(responseData);
    }
}

/** обработать ответ сервера об удалении автора
 * @param {*} responseData 
 */
function processRemoveResponse(responseData) {
    try {
        let response = JSON.parse(responseData);
        if (response.is_removed == 1) {
            selectedAuthorElem.remove();
            selectedAuthorElem = false;
        } else {
            prgError.textContent = response.description;
        }
    } catch(exception) {
        prgError.textContent = exception;
        console.log(responseData);
    }
}