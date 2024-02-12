class UserClientController extends ClientController {
  /** добавить пользователя
   * @param {*} e 
   * @param {*} userTable таблица пользователей
   * @returns 
   */
  async store(e, userTable) {
    e.preventDefault();

    return await ServerRequest.execute(
      this.url.store,
      (data) => this.#processStoreResponse(data, e.target, userTable),
      "post",
      this.errorPrg,
      new FormData(e.target)
    );
  }

  /** обработать ответа сервера на сохранение пользователя
   * @param {*} responseData данные сервера
   * @param {*} form форма добавления пользователя
   * @param {*} userTable таблица пользователей 
   */
  #processStoreResponse(responseData, form, userTable) {
    try {
      let response = JSON.parse(responseData);
      if (response.is_added > 0) {
        let trElem = document.createElement("tr");
        trElem.innerHTML = `
          <td class='table-row p-3 theme-border-bottom'>
            <button class='user-table__btn user-table__btn-remove' title='удалить автора'>✘</button>
            <span class='user-table__content'>${form.email.value}</span>
          </td>
          <td class='table-row p-3 theme-border-bottom'></td>
          <td class='table-row p-3 theme-border-bottom'>${
            form.is_admin.value == 1 ? "да" : "нет"
          }</td>
        `;
        trElem.querySelector(".user-table__btn-remove").onclick = () =>
          this.destroy(trElem, csrf);

        userTable.childNodes[0].after(trElem);

        this.errorPrg.textContent = "";
        form.reset();
      } else {
        this.errorPrg.textContent = response.description;
      }
    } catch (exception) {
      this.errorPrg.textContent = "JSON ошибка. Подробности см. в консоли";
      console.log("processStoreGenreResponse: " + responseData);
    }
  }

  /** удалить пользователя
   * @param {*} userElem DOM пользователя
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

  /** обработать ответа сервера на удаление пользователя
   * @param {*} responseData ответ сервера 
   * @param {*} userElem DOM пользователя
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
