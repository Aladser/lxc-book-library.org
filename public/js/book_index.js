const url = {
  'show': '/book/show/',
};

/** таблица строк */
const bookTableRows = Array.from(document.querySelectorAll(".book-table__row"));
/** переходы на страницы книг */
bookTableRows.forEach((row) => {
  row.addEventListener("click", function () {
    let bookIndex = bookTableRows.indexOf(this) + 1;
    window.open(url.show + bookIndex, '_self');
  });
});
