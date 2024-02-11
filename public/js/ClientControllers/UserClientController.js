class UserClientController extends ClientController {
  constructor(url, errorPrg) {
    super(url, errorPrg);
    this.selectedUserName = false;
  }
  
  /** добавить нового автора
   * @param {*} e
   * @param {*} genreTable таблица жанров
   * @param {*} csrf
   * @returns
   */
  async store(e, genreTable) {
    e.preventDefault();
    return await ServerRequest.execute(
      this.url.store,
      (data) => this.#processStoreResponse(data, e.target, genreTable),
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
  #processStoreResponse(responseData, form, authorTable) {
    try {
      let response = JSON.parse(responseData);
      if (response.is_added > 0) {
        let trElem = document.createElement("tr");

        let isAdmin = form.is_admin.value == 1 ? "есть" : "нет";
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
   * @param {*} userElem DOM жанра
   * @param {*} csrf
   */
  destroy(userElem, csrf) {
    let user_name = new URLSearchParams();
    user_name.set(
      "user_name",
      userElem.querySelector(".user-table__content").textContent
    );
    user_name.set("CSRF", csrf.content);

    ServerRequest.execute(
      this.url.destroy,
      (data) => this.#processRemoveResponse(data, userElem),
      "post",
      this.errorPrg,
      user_name
    );
  }

  /** обработать ответ сервера об удалении автора
   * @param {*} responseData ответа сервера
   * @param {*} userElem DOM жанра
   */
  #processRemoveResponse(responseData, userElem) {
    try {
      let response = JSON.parse(responseData);
      if (response.is_removed == 1) {
        userElem.remove();
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
