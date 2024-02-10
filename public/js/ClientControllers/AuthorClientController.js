class AuthorClientController extends ClientController{
    /** добавить нового автора */
    async store(e, authorTable) {
        e.preventDefault();
        return await ServerRequest.execute(
            this.url.store,
            data => this.#processStoreAuthorResponse(data, e.target, authorTable),
            "post",
            this.errorPrg,
            new FormData(e.target)
        );
    }

    /** обработать ответ сервера о добавлении нового автора
     * @param {*} responseData ответ сервера
     * @param {*} form форма добавления
     */
    #processStoreAuthorResponse(responseData, form, authorTable) {
        try {
            let response = JSON.parse(responseData);
            if (response.is_added > 0) {
                let trElem = document.createElement('tr');
                let tdElem = document.createElement('td');
                trElem.append(tdElem);
                tdElem.className = 'table-row p-3 cursor-pointer theme-bg-сolor-white theme-border-top theme-border-bottom';
                tdElem.textContent = `${form.name.value} ${form.surname.value}`;
                authorTable.prepend(trElem);

                this.errorPrg.textContent = '';
                form.reset();

                return tdElem;
            } else {
                this.errorPrg.textContent = response.description;
                return false;
            }
        } catch(exception) {
            this.errorPrg.textContent = exception;
            console.log("#processStoreAuthorResponse: " + responseData);
            return false;
        }
    }

    /** показать форму обновления автора */
    edit(csrf) {
        let [name, surname] = this.selectedElementContent.split(' ');
        this.selectedElement.innerHTML = `
            <form id='table-row__form-edit'>
                <input type='text' name='name' class='table-row__input-author theme-border' value='${name}'>
                <input type='text' name='surname' class='table-row__input-author theme-border' value='${surname}'>
                <input type='button' id='table-row__btn-cancel' class='table-row__btn theme-border' value='Отмена'>
                <input type='submit' class='table-row__btn theme-border' value='OK'>
                <input type="hidden" name="CSRF" value="${csrf.content}">
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
        if (this.selectedElementContent === newAuthorName) {
            this.restore(e);
            return;
        }

        let formData = new FormData(e.target);
        formData.set('current_author_name', this.selectedElementContent);
        ServerRequest.execute(
            this.url.update,
            data => this.#processUpdateAuthorResponse(data, newAuthorName),
            "post",
            this.errorPrg,
            formData
        );
    }

    /** обработать ответ сервера на изменение автора
     * @param {*} responseData ответ сервера
     * @param {*} newAuthorName новое имя
     */
    #processUpdateAuthorResponse(responseData, newAuthorName) {
        try {
            let response = JSON.parse(responseData);
            if (response.is_updated == 1) {
                this.selectedElement.innerHTML = newAuthorName;
                this.errorPrg.textContent = '';
            } else {
                this.errorPrg.textContent = response.description;
            }
        } catch(exception) {
            this.errorPrg.textContent = exception;
            console.log(responseData);
        }
    }
    
    /** удалить автора */
    destroy(csrf) {
        let author_name = new URLSearchParams();
        author_name.set('author_name', this.selectedElementContent);
        author_name.set('CSRF', csrf.content);

        ServerRequest.execute(
            this.url.destroy,
            data => this.#processRemoveResponse(data),
            "post",
            this.errorPrg,
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
                this.selectedElement.remove();
                this.selectedElement = false;
            } else {
                this.errorPrg.textContent = response.description;
            }
        } catch(exception) {
            this.errorPrg.textContent = exception;
            console.log(responseData);
        }
    }
}