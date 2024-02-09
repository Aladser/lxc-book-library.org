class GenreClientController {
    /** DOM выбранного автора */
    #selectedGenreElem = false;
    /** имя выбранного автора */
    #selectedGenreName = false;

    constructor(url, errorPrg) {
        this.url = url;
        this.errorPrg = errorPrg;
    }

    /** установить выбранного автора */
    setSelectedAuthor(selectedGenreElem) {
        this.#selectedGenreElem = selectedGenreElem;
        this.#selectedGenreName = selectedGenreElem.textContent.trim();
    }
    
    /** отменить обновление автора */
    restore() {
        this.#selectedGenreElem.innerHTML = this.#selectedGenreName;
        this.errorPrg.textContent = '';
    }

    /** добавить нового автора */
    async store(e, genreTable) {
        e.preventDefault();
        return await ServerRequest.execute(
            this.url.store,
            data => this.#processStoreGenreResponse(data, e.target, genreTable),
            "post",
            this.errorPrg,
            new FormData(e.target)
        );
    }

    /** обработать ответ сервера о добавлении нового автора
     * @param {*} responseData ответ сервера
     * @param {*} form форма добавления
     */
    #processStoreGenreResponse(responseData, form, genreTable) {
        try {
            let response = JSON.parse(responseData);
            if (response.is_added > 0) {
                let trElem = document.createElement('tr');
                let tdElem = document.createElement('td');
                trElem.append(tdElem);
                tdElem.className = 'table-row p-3 cursor-pointer theme-bg-сolor-white theme-border-top theme-border-bottom';
                tdElem.textContent = `${form.name.value} ${form.surname.value}`;
                genreTable.prepend(trElem);

                this.errorPrg.textContent = '';
                form.reset();

                return tdElem;
            } else {
                this.errorPrg.textContent = response.description;
                return false;
            }
        } catch(exception) {
            this.errorPrg.textContent = exception;
            console.log("processStoreGenreResponse: " + responseData);
            return false;
        }
    }

    /** показать форму обновления автора */
    edit(csrf) {
        this.#selectedGenreElem.innerHTML = `
            <form id='table-row__form-edit'>
                <input type='text' name='name' class='table-row__input-author theme-border' value='${this.#selectedGenreName}'>
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

        // если не изменилось имя
        if (this.#selectedGenreName === e.target.name.value) {
            this.restore(e);
            return;
        }

        let formData = new FormData(e.target);
        formData.set('current_genre_name', this.#selectedGenreName);
        ServerRequest.execute(
            this.url.update,
            data => this.#processUpdateGenreResponse(data, newAuthorName),
            "post",
            this.errorPrg,
            formData
        );
    }

    /** обработать ответ сервера на изменение автора
     * @param {*} responseData ответ сервера
     * @param {*} newGenreName новое имя
     */
    #processUpdateGenreResponse(responseData, newGenreName) {
        try {
            let response = JSON.parse(responseData);
            if (response.is_updated == 1) {
                this.#selectedGenreElem.innerHTML = newGenreName;
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
        let genre_name = new URLSearchParams();
        genre_name.set('genre_name', this.#selectedGenreName);
        genre_name.set('CSRF', csrf.content);

        ServerRequest.execute(
            this.url.destroy,
            data => this.#processRemoveResponse(data),
            "post",
            this.errorPrg,
            genre_name
        );
    }

    /** обработать ответ сервера об удалении автора
     * @param {*} responseData 
     */
    #processRemoveResponse(responseData) {
        try {
            let response = JSON.parse(responseData);
            if (response.is_removed == 1) {
                this.#selectedGenreElem.remove();
                this.#selectedGenreElem = false;
            } else {
                this.errorPrg.textContent = response.description;
            }
        } catch(exception) {
            this.errorPrg.textContent = exception;
            console.log(responseData);
        }
    }
}