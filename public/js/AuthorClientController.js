class AuthorClientController {
        /** DOM выбранного автора */
        #selectedAuthorElem = false;
        /** имя выбранного автора */
        #selectedAuthorName = false;

    constructor() {
        /** таблица авторов */
        this.authorTable = document.querySelector('#author-table').childNodes[1]; 

        this.baseClassURL = '/author';
        this.url = {
            'update': '/author/update',
            'store': '/author/store',
            'destroy': '/author/destroy'
        };
        /** CSRF */
        this.csrf = document.querySelector("meta[name='csrf']");
        /** блок ошибок */
        this.prgError = document.querySelector('#prg-error');
        /** форма добавления автора*/
        this.addAuthorForm = document.querySelector('#form-add-author');
        this.addAuthorForm.onsubmit = e => this.store(e);
        /** контекстное меню строки*/
        this.authorContextMenu = document.querySelector('.author-context-menu');
    }

    /** установить выбранного автора */
    setSelectedAuthor(selectedAuthorElem) {
        this.#selectedAuthorElem = selectedAuthorElem;
        this.#selectedAuthorName = selectedAuthorElem.textContent.trim();
    }

    hideContextMenu() {
        this.authorContextMenu.classList.remove('author-context-menu--active');
    }

    /** добавить нового автора */
    store(e) {
        e.preventDefault();
        ServerRequest.execute(
            this.url.store,
            data => this.#processStoreAuthorResponse(data, e.target),
            "post",
            this.prgError,
            new FormData(e.target)
        );
    }

    /** обработать ответ сервера о добавлении нового автора
     * @param {*} responseData ответ сервера
     * @param {*} form форма добавления
     */
    #processStoreAuthorResponse(responseData, form) {
        try {
            let response = JSON.parse(responseData);
            if (response.is_added > 0) {
                let trElem = document.createElement('tr');
                let tdElem = document.createElement('td');
                trElem.append(tdElem);
                tdElem.className = 'table-row p-3 cursor-pointer theme-bg-сolor-white theme-border-top theme-border-bottom';
                tdElem.textContent = `${form.name.value} ${form.surname.value}`;
                this.authorTable.prepend(trElem);

                this.prgError.textContent = '';
                form.reset();
            } else {
                this.prgError.textContent = response.description;
            }
        } catch(exception) {
            this.prgError.textContent = exception;
            console.log("#processStoreAuthorResponse: " + responseData);
        }
    }

    // показать форму обновления автора
    edit() {
        let [name, surname] = this.#selectedAuthorName.split(' ');
        this.#selectedAuthorElem.innerHTML = `
            <form id='table-row__form-edit'>
                <input type='text' name='name' class='table-row__input-author theme-border' value='${name}'>
                <input type='text' name='surname' class='table-row__input-author theme-border' value='${surname}'>
                <input type='button' id='table-row__btn-cancel' class='table-row__btn theme-border' value='Отмена'>
                <input type='submit' class='table-row__btn theme-border' value='OK'>
                <input type="hidden" name="CSRF" value="${this.csrf.content}">
            </form>
        `;
        document.querySelector('#table-row__form-edit').onsubmit = e => this.update(e);
        document.querySelector('#table-row__btn-cancel').onclick = e => this.restore(e);
    }

    /** обновить автора */
    update(e) {
        e.preventDefault();

        let newAuthorName = `${e.target.name.value} ${e.target.surname.value}`;
        // если не изменилось имя
        if (this.#selectedAuthorName === newAuthorName) {
            this.cancelUpdate();
            return;
        }

        let formData = new FormData(e.target);
        formData.set('current_author_name', this.#selectedAuthorName);
        ServerRequest.execute(
            this.url.update,
            data => this.#processUpdateAuthorResponse(data, newAuthorName),
            "post",
            this.prgError,
            formData
        );
    }

    /** отменить обновление автора */
    restore() {
        this.#selectedAuthorElem.innerHTML = this.#selectedAuthorName;
        this.prgError.textContent = '';
    }

    /** обработать ответ сервера на изменение автора
     * @param {*} responseData ответ сервера
     * @param {*} newAuthorName новое имя
     */
    #processUpdateAuthorResponse(responseData, newAuthorName) {
        try {
            let response = JSON.parse(responseData);
            if (response.is_updated == 1) {
                this.#selectedAuthorElem.innerHTML = newAuthorName;
                this.prgError.textContent = '';
            } else {
                this.prgError.textContent = response.description;
            }
        } catch(exception) {
            this.prgError.textContent = exception;
            console.log(responseData);
        }
    }
    
    /** удалить автора */
    destroy() {
        let author_name = new URLSearchParams();
        author_name.set('author_name', this.#selectedAuthorName);
        author_name.set('CSRF', this.csrf.content);

        ServerRequest.execute(
            this.url.destroy,
            data => this.#processRemoveResponse(data),
            "post",
            this.prgError,
            author_name
        );
    }

    /** обработать ответ сервера об удалении автора
     * @param {*} responseData 
     */
    #processRemoveResponse(responseData) {
        try {
            let response = JSON.parse(responseData);
            if (response.is_removed == 1) {
                this.#selectedAuthorElem.remove();
                this.#selectedAuthorElem = false;
            } else {
                this.prgError.textContent = response.description;
            }
        } catch(exception) {
            this.prgError.textContent = exception;
            console.log(responseData);
        }
    }
}