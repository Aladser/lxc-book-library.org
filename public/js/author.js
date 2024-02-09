const authorClientController = new AuthorClientController();

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