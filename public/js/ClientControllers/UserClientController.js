class UserClientController extends ClientController {
  async store(e, authorTable) {
    e.preventDefault();

    return await ServerRequest.execute(
      this.url.store,
      (data) => this.#processStoreResponse(data, e.target, authorTable),
      "post",
      this.errorPrg,
      new FormData(e.target)
    );
  }

  #processStoreResponse(responseData, form, genreTable) {
    try {
      let response = JSON.parse(responseData);
      console.log(response);
      if (response.is_added > 0) {
        let trElem = document.createElement("tr");
        let tdElem = document.createElement("td");
        trElem.append(tdElem);

        tdElem.className =
          "table-row p-3 theme-bg-сolor-white theme-border-top theme-border-bottom";
        tdElem.innerHTML = `
                    <span class='genre-table__content'>${form.name.value}</span>
                    <button class='genre-table__btn-remove' title='удалить пользователя'>✘</button>
                `;
        tdElem.querySelector("button").onclick = (e) =>
          this.destroy(tdElem, csrf);

        genreTable.prepend(trElem);

        this.errorPrg.textContent = "";
        form.reset();

        return tdElem;
      } else {
        this.errorPrg.textContent = response.description;
        return false;
      }
    } catch (exception) {
      this.errorPrg.textContent = 'JSON ошибка. Подробности см. в консоли';
      console.log("processStoreGenreResponse: " + responseData);
      return false;
    }
  }

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
