const url = {
  update: "/book/update",
  show: "/book/show/",
};
/** форма изменения книги*/
const editBookForm = document.querySelector("#form-edit-user");
/** блок ошибок */
const errorPrg = document.querySelector("#prg-error");

// --- DOMContentLoaded ---
window.addEventListener("DOMContentLoaded", function () {
  // отправка формы
  editBookForm.addEventListener("submit", function (e) {
    // id книги
    let urlArr = window.location.href.split("/");
    let bookId = urlArr[urlArr.length - 1];
    // данные для отправки
    let formData = new FormData(this);
    formData.append('id', bookId);

    e.preventDefault();
    ServerRequest.execute(
      url.update,
      (data) => processUpdateResponse(data, bookId),
      "post",
      errorPrg,
      formData
    );
  });
});

/** обработать ответ сервера на обновление данных */
function processUpdateResponse(data, bookId) {
  try {
    let response = JSON.parse(data);
    if (response.result == 1) {
      window.open(url.show + bookId, '_self');
    } else {
      errorPrg.textContent = response.description;
    }
  } catch (exception) {
    errorPrg.textContent = 'Серверная ошибка';
    console.log(exception);
    console.log(data);
  }
}
