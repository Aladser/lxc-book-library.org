/** кнопки изменения автора */
let edit_buttons = document.querySelectorAll('.table-row__btn-edit');
/** кнопки удаления автора */
let remove_buttons = document.querySelectorAll('.table-row__btn-remove'); 

edit_buttons.forEach(btn => btn.onclick = e => {
    let tableRow = e.target.closest('.table-row');
    let authorName = tableRow.querySelector('.table-row__author').textContent;
    console.log('изменить ' + authorName);
});
remove_buttons.forEach(btn => btn.onclick = e => {
    let tableRow = e.target.closest('.table-row');
    let authorName = tableRow.querySelector('.table-row__author').textContent;
    console.log('удалить ' + authorName);
});