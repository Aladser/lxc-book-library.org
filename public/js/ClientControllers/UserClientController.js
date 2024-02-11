class UserClientController extends ClientController {
  /** добавить нового автора
   * @param {*} e 
   * @param {*} genreTable таблица жанров
   * @param {*} csrf 
   * @returns 
   */
  async store(e, genreTable, csrf) {
    e.preventDefault();
    return await ServerRequest.execute(
      this.url.store,
      (data) =>
        this.#processStoreResponse(data, e.target, genreTable, csrf),
      "post",
      this.errorPrg,
      new FormData(e.target)
    );
  }

  /** обработать ответ сервера о добавлении нового автора
   * @param {*} responseData ответ сервера
   * @param {*} form форма добавления
   * @param {*} authorTable таблица жанров
   * @param {*} csrf 
   * @returns 
   */
  #processStoreResponse(responseData, form, authorTable, csrf) {
    try {
      let response = JSON.parse(responseData);
      if (response.is_added > 0) {
        let trElem = document.createElement("tr");

        let isAdmin = form.is_admin.value == 1 ? 'есть' : 'нет';
        trElem.className =
          "table-row p-3 theme-bg-сolor-white theme-border-top theme-border-bottom";
        trElem.innerHTML = `
              <td class='table-row p-3 theme-border-bottom'>${form.email.value}</td>
              <td class='table-row p-3 theme-border-bottom'></td>
              <td class='table-row p-3 theme-border-bottom'>${isAdmin}</td>
              `;
        authorTable.prepend(trElem);

        this.errorPrg.textContent = "";
        form.reset();
      } else {
        this.errorPrg.textContent = response.description;
      }
    } catch (exception) {
      this.errorPrg.textContent = response.description;
      console.log("processStoreGenreResponse: " + responseData);
    }
  }

  /**  удалить жанр
   * @param {*} genreElem DOM жанра
   * @param {*} csrf
   */
  destroy(genreElem, csrf) {
    let genre_name = new URLSearchParams();
    genre_name.set(
      "genre_name",
      genreElem.querySelector(".genre-table__content").textContent
    );
    genre_name.set("CSRF", csrf.content);

    ServerRequest.execute(
      this.url.destroy,
      (data) => this.#processRemoveResponse(data, genreElem),
      "post",
      this.errorPrg,
      genre_name
    );
  }

  /** обработать ответ сервера об удалении автора
   * @param {*} responseData ответа сервера
   * @param {*} genreElem DOM жанра
   */
  #processRemoveResponse(responseData, genreElem) {
    try {
      let response = JSON.parse(responseData);
      if (response.is_removed == 1) {
        genreElem.remove();
        this.errorPrg.textContent = "";
      } else {
        this.errorPrg.textContent = response.description;
      }
    } catch (exception) {
      console.log(responseData);
      this.errorPrg.textContent = response.description;
    }
  }
}
