/* ==Клиентский контроллер авторов
================================== */
/** URL серверного контроллера авторов */
const url = {
  'store': '/author/store',
  'update': '/author/update',
  'destroy': '/author/destroy'
};
/** блок ошибок */
const errorPrg = document.querySelector("#prg-error");
/** клиентский контроллер авторов */
const authorClientController = new AuthorClientController(url, errorPrg);


/** базовое CSS имя контекстного меню автора*/
const authorContextMenuClassName = "context-menu";
/** контекстное меню автора */
const authorContextMenu = new ContextMenu(authorContextMenuClassName);

/** форма добавления автора*/
const addAuthorForm = document.querySelector("#form-add-author");
/** CSRF */
const csrf = document.querySelector("meta[name='csrf']");
/** таблица авторов */
const authorTable = document.querySelector("#author-table").childNodes[1];

window.addEventListener("DOMContentLoaded", function (e) {
  document.oncontextmenu = () => false;
  window.addEventListener("click", () => authorContextMenu.hide());

  // добавление нового автора
  addAuthorForm.onsubmit = async (e) => {
    let tr = await authorClientController.store(e, authorTable);
    tr.oncontextmenu = (e) => openContextMenu(e);
  };

  // контекстное меню автора
  document.querySelectorAll(".table-row").forEach((row) => {
    row.addEventListener("contextmenu", (e) => openContextMenu(e));
  });
  // изменить автора
  document.querySelector(".context-menu__btn-edit").onclick = () =>
    authorClientController.edit(csrf);
  // удалить автора
  document.querySelector(".context-menu__btn-remove").onclick = () =>
    authorClientController.destroy(csrf);
});

/** ПКМ по автору */
function openContextMenu(e) {
  addAuthorForm.reset();
  errorPrg.textContent = "";
  authorContextMenu.show(e);
  authorClientController.selectElement(e.target);
}
