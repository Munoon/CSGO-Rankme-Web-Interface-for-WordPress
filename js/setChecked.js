'use strict';

(function() {

    let checkbox = document.getElementById('rankme_checkbox');
    let childrens = checkbox.children;
    for (let i = 0; i < childrens.length; i++) {
        if (childrens[i].dataset.checked === '1') {
            childrens[i].checked = true;
        } else if (childrens[i].tagName === 'DIV') {
            for (let k = 0; k < childrens[i].children.length; k++) {
                if (childrens[i].children[k].dataset.checked === '1') {
                    childrens[i].children[k].checked = true;
                }
            }
        }
    }

})();