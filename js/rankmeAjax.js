'use strict';

(function() {
    let select = document.getElementById('rankme_select');
    let next = document.getElementById('rankme_next');
    let prev = document.getElementById('rankme_prev');
    let start = 0;

    function ajax(data) {
        jQuery.get(rankmeAjaxPhp.ajaxurl, data, function (response) {
            console.log(response);
        })
    }

    select.addEventListener('change', function(e) { 
        ajax({
            action: 'rankme',
            id: rankmeAjaxPhp.id,
            start,
            count: select.value
        });
    });

    next.addEventListener('click', function(e) {
        start += select.value;
        ajax({
            action: 'rankme',
            id: rankmeAjaxPhp.id,
            start,
            count: select.value
        });
    });

    prev.addEventListener('click', function(e) {
        start -= select.value;
        if (start < 0) start = 0;
        ajax({
            action: 'rankme',
            id: rankmeAjaxPhp.id,
            start,
            count: select.value
        });
    })
})();