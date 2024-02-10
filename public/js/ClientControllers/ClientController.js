class ClientController {
    constructor(url, errorPrg) {
        this.url = url;
        this.errorPrg = errorPrg;
        
        /** DOM выбранного элемента */
        this.selectedElement = false;
        /** содержание выбранного элемента */
        this.selectedElementContent = false;
    }

    /** установить выбранного автора */
    selectElement(selectedElement) {
        this.selectedElement = selectedElement;
        this.selectedElementContent = selectedElement.textContent.trim();
    }
    
    /** отменить обновление автора */
    restore() {
        this.selectedElement.innerHTML = this.selectedElementContent;
        this.errorPrg.textContent = '';
    }
}