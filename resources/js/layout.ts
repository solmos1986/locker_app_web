
const data = document.querySelector("body");
export const base_url = data!.dataset.url_base;

var items = document.querySelectorAll(".menu-item");

// Recorres cada uno de los links
items.forEach(function (item) {
    // Si la url del link es igual a la url actual del navegador
    // entonces a ese link se le coloca la clase .active
    /* console.log(item.getAttribute('href'))
    console.log(document.URL) */
    if (item.getAttribute('href')=== document.URL) {
        item.className = "menu-item group menu-item-active";
    }
});