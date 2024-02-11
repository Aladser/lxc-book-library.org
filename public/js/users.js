/** URL методов*/
const url = {
  store: "/user/store",
  destroy: "/user/destroy",
};
/** блок ошибок */
const errorPrg = document.querySelector("#prg-error");
/** CSRF */
const csrf = document.querySelector("meta[name='csrf']");
/** таблица жанров */
const genreTable = document.querySelector("#user-table").childNodes[1];
/** форма добавления жанра*/
const addGenreForm = document.querySelector("#form-add-user");
/** кнопки удаления жанра*/
const removeGenreButtons = document.querySelectorAll(
  ".user-table__btn-remove"
);

/** клиентский контроллер жанров */
const userClientController = new UserClientController(url, errorPrg);

window.addEventListener("DOMContentLoaded", function () {
  // добавление пользователя
  addGenreForm.onsubmit = (e) => {
    genreClientController.store(e, genreTable, csrf);
  };
  // удаление пользователя
  removeGenreButtons.forEach((btn) => {
    btn.onclick = (e) => {
      genreClientController.destroy(e.target.closest(".table-row"), csrf);
    };
  });
});
