'use strict';

(function() {

    let checkbox = document.getElementById('rankme_checkbox');
    let childrens = checkbox.children;
    for (let i = 0; i < childrens.length; i++) {
        if (childrens[i].dataset.checked === '1') {
            childrens[i].checked = true;
        }
    }

})();