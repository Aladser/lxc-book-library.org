class ContextMenu {
  constructor(baseCSSName) {
    /** базовое CSS имя*/
    this.baseCSSName = baseCSSName;
    /** DOM контекстное меню*/
    this.contextMenuElem = document.querySelector("." + baseCSSName);
  }

  hide() {
    this.contextMenuElem.classList.remove(this.baseCSSName + "--active");
  }

  show(e) {
    this.contextMenuElem.style.left = e.pageX - 10 + "px";
    this.contextMenuElem.style.top = e.pageY - 10 + "px";
    this.contextMenuElem.classList.add(this.baseCSSName + "--active");
  }
}
