/** URL методов*/
const url = {
  store: "/user/store",
  destroy: "/user/destroy",
};
/** блок ошибок */
const errorPrg = document.querySelector("#prg-error");
/** CSRF */
const csrf = document.querySelector("meta[name='csrf']");

/** таблица пользователей */
const userTable = document.querySelector("#user-table").childNodes[1];
/** форма добавления пользователя*/
const addUserForm = document.querySelector("#form-add-user");
/** кнопки изменения пользователя*/
const editUserButtons = document.querySelectorAll(".user-table__btn-edit");
/** кнопки удаления пользователя*/
const removeUserButtons = document.querySelectorAll(".user-table__btn-remove");

/** клиентский контроллер пользователей */
const userClientController = new UserClientController(url, errorPrg);

window.addEventListener("DOMContentLoaded", function () {
  // добавление пользователя
  addUserForm.onsubmit = (e) => {
    userClientController.store(e, userTable);
  };
  // удаление пользователя
  removeUserButtons.forEach((btn) => {
    btn.onclick = (e) => {
      userClientController.destroy(e.target.closest(".table-row").closest('tr'), csrf);
    };
  });
});
