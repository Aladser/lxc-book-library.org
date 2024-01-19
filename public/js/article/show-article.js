/** Клиентский контроллер тем*/
const commentController = new CommentClientController(
    "/comment",
    document.querySelector("#prg-error"),
    document.querySelector("#form-send-message"),
    document.querySelector("#comment-list"),
    document.querySelector('meta[name="csrf"]')
);
