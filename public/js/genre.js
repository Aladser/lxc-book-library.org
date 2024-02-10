/* ==Клиентский контроллер жанров
================================== */
/** URL методов*/
const url = {
  'store': '/genre/store',
  'destroy': '/genre/destroy'
};
/** блок ошибок */
const errorPrg = document.querySelector("#prg-error");
/** клиентский контроллер жанров */
const genreClientController = new GenreClientController(url, errorPrg);


/** базовое CSS имя контекстного меню жанра*/
const genreContextMenuClassName = "context-menu";
/** контекстное меню жанра */
const genreContextMenu = new ContextMenu(genreContextMenuClassName);

/** CSRF */
const csrf = document.querySelector("meta[name='csrf']");
/** таблица жанров */
const genreTable = document.querySelector("#genre-table").childNodes[1];

window.addEventListener("DOMContentLoaded", function () {
  document.oncontextmenu = () => false;
  window.addEventListener("click", () => genreContextMenu.hide());

  // контекстное меню жанра
  document.querySelectorAll(".table-row").forEach((row) => {
    row.oncontextmenu = (e) => openContextMenu(e);
  });
  // удалить жанр
  document.querySelector(".context-menu__btn-remove").onclick = () =>
    genreClientController.destroy(csrf);
});

/** форма добавления жанра*/
const addGenreForm = document.querySelector("#form-add-genre");
addGenreForm.onsubmit = async (e) => {
  let tr = await genreClientController.store(e, genreTable);
  tr.oncontextmenu = (e) => openContextMenu(e);
};

/** ПКМ по жанру */
function openContextMenu(e) {
  addGenreForm.reset();
  errorPrg.textContent = "";
  genreContextMenu.show(e);
  genreClientController.selectElement(e.target);
}
