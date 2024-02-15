class AuthorClientController extends ClientController {
  constructor(url, errorPrg) {
    super(url, errorPrg);
    this.selectedAuthorName = false;
  }

  /** отменить обновление автора */
  restore() {
    this.selectedElement.innerHTML = this.selectedElementContent;
    this.errorPrg.textContent = "";
    this.selectedElement.querySelector(".author-table__btn-edit").onclick = (
      e
    ) => this.edit(e.target.closest(".table-row"), csrf);
    this.selectedElement.querySelector(".author-table__btn-remove").onclick = (
      e
    ) => this.destroy(e.target.closest(".table-row"), csrf);
  }

  // создать строку таблицы авторов
  appendButtonListeners(authorRow) {
    authorRow.querySelector(".author-table__btn-edit").onclick = () =>
      this.edit(authorRow, csrf);
    authorRow.querySelector(".author-table__btn-remove").onclick = () =>
      this.destroy(authorRow, csrf);
  }

  /** добавить нового автора */
  async store(e, authorTable) {
    e.preventDefault();
    return await ServerRequest.execute(
      this.url.store,
      (data) => this.#processStoreAuthorResponse(data, e.target, authorTable),
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
        let trElem = document.createElement("tr");
        let tdElem = document.createElement("td");
        trElem.append(tdElem);

        tdElem.className =
          "table-row p-3 theme-bg-сolor-with-hover theme-border-top theme-border-bottom";
        tdElem.innerHTML = `
                  <span class='author-table__content'>${form.name.value} ${form.surname.value}</span>
                  <button class='author-table__btn author-table__btn-edit' title='изменить автора'>✏</button>
                  <button class='author-table__btn author-table__btn-remove' title='удалить пользователя'>✘</button>
              `;
        this.appendButtonListeners(tdElem);

        authorTable.prepend(trElem);
        this.errorPrg.textContent = "";
        form.reset();
      } else {
        this.errorPrg.textContent = response.description;
      }
    } catch (exception) {
      this.errorPrg.textContent = exception;
      console.log("#processStoreAuthorResponse: " + responseData);
    }
  }

  /** показать форму обновления автора */
  edit(authorElem, csrf) {
    this.selectedElement = authorElem;
    this.selectedElementContent = this.selectedElement.innerHTML;
    this.selectedAuthorName = authorElem.querySelector(
      ".author-table__content"
    ).textContent;

    let authorName = authorElem.querySelector(
      ".author-table__content"
    ).textContent;
    let [name, surname] = authorName.split(" ");
    this.selectedElement.innerHTML = `
            <form id='table-row__form-edit'>
                <input type='text' name='name' class='table-row__input-author theme-border' value='${name}'>
                <input type='text' name='surname' class='table-row__input-author theme-border' value='${surname}'>
                <input type='button' id='table-row__btn-cancel' class='table-row__btn theme-border' value='Отмена'>
                <input type='submit' class='table-row__btn theme-border' value='OK'>
                <input type="hidden" name="CSRF" value="${csrf.content}">
            </form>
        `;
    document.querySelector("#table-row__form-edit").onsubmit = (e) =>
      this.update(e);
    document.querySelector("#table-row__btn-cancel").onclick = (e) =>
      this.restore(e);
  }

  /** обновить автора */
  update(e) {
    e.preventDefault();

    let newAuthorName = `${e.target.name.value} ${e.target.surname.value}`;
    // если не изменилось имя
    if (this.selectedAuthorName === newAuthorName) {
      this.restore(e);
      return;
    }

    let formData = new FormData(e.target);
    formData.set("current_author_name", this.selectedAuthorName);
    ServerRequest.execute(
      this.url.update,
      (data) => this.#processUpdateAuthorResponse(data, newAuthorName),
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
        this.selectedElement.innerHTML = this.selectedElementContent;
        this.selectedElement.querySelector('.author-table__content').textContent = newAuthorName;
        this.appendButtonListeners(this.selectedElement);
        this.errorPrg.textContent = "";
      } else {
        this.errorPrg.textContent = response.description;
      }
    } catch (exception) {
      this.errorPrg.textContent = exception;
      console.log(responseData);
    }
  }

  /**  удалить автора
   * @param {*} authorElem DOM жанра
   * @param {*} csrf
   */
  destroy(authorElem, csrf) {
    let author_name = new URLSearchParams();
    author_name.set(
      "author_name",
      authorElem.querySelector(".author-table__content").textContent
    );
    author_name.set("CSRF", csrf.content);

    ServerRequest.execute(
      this.url.destroy,
      (data) => this.#processRemoveResponse(data, authorElem),
      "post",
      this.errorPrg,
      author_name
    );
  }

  /** обработать ответ сервера об удалении автора
   * @param {*} responseData
   */
  #processRemoveResponse(responseData, authorElem) {
    try {
      let response = JSON.parse(responseData);
      if (response.is_removed == 1) {
        authorElem.remove();
        this.errorPrg.textContent = "";
      } else {
        this.errorPrg.textContent = response.description;
      }
    } catch (exception) {
      this.errorPrg.textContent = exception;
      console.log(responseData);
    }
  }
}
