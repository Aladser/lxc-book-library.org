class ArticleClientController {
    /** Клиентский контроллер статей
     * 
     * @param {*} URL url-сервера
     * @param {*} msgElement поле сообщений
     * @param {*} table таблица
     * @param {*} addForm форма добавления
     * @param {*} editForm форма редактирования
     * @param {*} editBtn кнопка отправки формы редактирования
     * @param {*} removeBtn кнопка удаления
     * @param {*} aboutBtn кнопка открытия страницы статьи
     */
    constructor(URL, msgElement, table = null, addForm = null, editForm = null, editBtn=null, removeBtn=null, aboutBtn=null) {
        this.URL = URL;
        this.msgElement = msgElement;
        this.table = table;
        
        this.addForm = addForm;
        this.editForm = editForm;

        this.editBtn = editBtn;
        this.removeBtn = removeBtn;
        this.aboutBtn = aboutBtn;

        this.activeArticleId = false;

        // форма добавления нового товара
        if (this.addForm) {
            this.addForm.onsubmit = (event) => this.add(event);
            this.addFormId = addForm.id;
        }
        // форма изменения товара
        if (this.editForm) {
            this.editForm.onsubmit = (event) => this.update(event);
            this.editFormId = editForm.id;
        }
        // кнопка изменения
        if (this.editBtn) {
            this.editBtn.onclick = () => this.update();
        }
        // кнопка удаления
        if (this.removeBtn) {
            this.removeBtn.onclick = () => this.remove();
        }
    }

    // добавить тему в БД - article/store
    add(event) {
        event.preventDefault();
        // ---данные---
        let formData = new FormData(this.addForm);
        // ---запрос на сервер---
        ServerRequest.execute(
            this.URL+'/store',
            (data) => {
                data = JSON.parse(data);
                if (data.result==1) {
                    event.target.reset();
                    this.msgElement.innerHTML = 'статья добавлена';
                } else {
                    this.msgElement.innerHTML = data.description;
                }
            },
            "post",
            this.msgElement,
            formData
        );
    }

    // обновить товар в БД - article/update
    update(event) {
        event.preventDefault();
        // ---данные---
        let formData = new FormData(this.editForm);
        // ---запрос на сервер---
        ServerRequest.execute(
            this.URL+'/update',
            (data) => {
                if (data == 1) {
                    this.msgElement.innerHTML = 'статья обновлена';
                } else if(data != 0) {
                    this.msgElement.innerHTML = data;
                }            
            },
            "post",
            this.msgElement,
            formData
        );
    }

    // удалить статью в БД - article/remove
    remove() {
        let article = document.querySelector(`#${this.activeArticleId}`);
        // действия после успешного удаления данных в БД
        let process = (data) => {
            data = JSON.parse(data);
            if (data.result == 1) {
                article.remove();
                this.activeArticleId = false;
                this.msgElement.textContent = "";
            } else {
                this.msgElement.textContent = data;
            }
        };

        let params = new URLSearchParams();
        params.set('id', this.activeArticleId.substring(3));

        // запрос на сервер
        ServerRequest.execute(
            this.URL + '/remove',
            process,
            "post",
            this.msgElement,
            params
        );
    }
    
}
