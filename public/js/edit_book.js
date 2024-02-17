const url = {
  update: "/book/update",
};
/** форма изменения книги*/
const editBookForm = document.querySelector("#form-edit-user");
/** блок ошибок */
const errorPrg = document.querySelector("#prg-error");

// --- DOMContentLoaded ---
window.addEventListener("DOMContentLoaded", function () {
  // отправка формы
  editBookForm.addEventListener("submit", function (e) {
    e.preventDefault();
    ServerRequest.execute(
      url.update,
      (data) => processUpdateResponse(data),
      "post",
      this.errorPrg,
      new FormData(this)
    );
  });
});

/** обработать ответ сервера на обновление данных */
function processUpdateResponse(data) {
  let response = JSON.parse(data);
  errorPrg.textContent = response.result == 1 ? "ОК" : response.description;
}
