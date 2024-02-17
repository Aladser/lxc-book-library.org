const url = {
  store: "/genre/store",
  destroy: "/genre/destroy",
};
/** блок ошибок */
const errorPrg = document.querySelector("#prg-error");
/** CSRF */
const csrf = document.querySelector("meta[name='csrf']");
/** таблица жанров */
const genreTable = document.querySelector("#genre-table").childNodes[1];
/** форма добавления жанра*/
const addGenreForm = document.querySelector("#form-add-genre");
/** кнопки удаления жанра*/
const removeGenreButtons = document.querySelectorAll(
  ".genre-table__btn-remove"
);
/** клиентский контроллер жанров */
const genreClientController = new GenreClientController(url, errorPrg);

// --- DOMContentLoaded ---
window.addEventListener("DOMContentLoaded", function () {
  // добавление жанра
  addGenreForm.onsubmit = (e) => {
    genreClientController.store(e, genreTable, csrf);
  };
  // удаление жанра
  removeGenreButtons.forEach((btn) => {
    btn.onclick = (e) => {
      genreClientController.destroy(e.target.closest(".table-row"), csrf);
    };
  });
});
