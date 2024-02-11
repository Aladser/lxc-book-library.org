/* ==Клиентский контроллер авторов
================================== */
/** URL серверного контроллера авторов */
const url = {
  store: "/author/store",
  update: "/author/update",
  destroy: "/author/destroy",
};
/** блок ошибок */
const errorPrg = document.querySelector("#prg-error");
/** клиентский контроллер авторов */
const authorClientController = new AuthorClientController(url, errorPrg);

/** форма добавления автора*/
const addAuthorForm = document.querySelector("#form-add-author");
/** CSRF */
const csrf = document.querySelector("meta[name='csrf']");
/** таблица авторов */
const authorTable = document.querySelector("#author-table").childNodes[1];

/** кнопки изменения жанра*/
const editGenreButtons = document.querySelectorAll(
  ".author-table__btn-edit"
);

/** кнопки удаления жанра*/
const removeGenreButtons = document.querySelectorAll(
  ".author-table__btn-remove"
);

window.addEventListener("DOMContentLoaded", function (e) {
  // добавление нового автора
  addAuthorForm.onsubmit = async (e) => {
    authorClientController.store(e, authorTable);
  };
  // изменение жанра
  editGenreButtons.forEach((btn) => {
    btn.onclick = (e) => authorClientController.edit(e.target.closest(".table-row"), csrf);
  });
  // удаление жанра
  removeGenreButtons.forEach((btn) => {
    btn.onclick = (e) => {
      authorClientController.destroy(e.target.closest(".table-row"), csrf);
    };
  });
});
