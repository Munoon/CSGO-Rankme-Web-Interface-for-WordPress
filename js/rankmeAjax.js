'use strict';

(function() {
    let select = document.getElementById('rankme_select');
    let next = document.getElementById('rankme_next');
    let prev = document.getElementById('rankme_prev');
    let start = 0;

    function ajax(data) {
        jQuery.get(rankmeAjaxPhp.ajaxurl, data, function (response) {
            let result = JSON.parse(response);
            let table = document.getElementById('rankme_table');
            let tableTbody = document.querySelector('#rankme_table > tbody');
            let tbody = document.createElement('tbody');
            for (let i = 0; i < result.length; i++) {
                let tr = document.createElement('tr');
                if (result[i].place) {
                    let td = document.createElement('td');
                    td.innerText = result[i].place;
                    tr.appendChild(td);
                }
                if (result[i].name) {
                    let td = document.createElement('td');
                    td.innerText = result[i].name;
                    tr.appendChild(td);
                }
                if (result[i].steam) {
                    let td = document.createElement('td');
                    td.innerText = result[i].steam;
                    tr.appendChild(td);
                }
                if (result[i].score) {
                    let td = document.createElement('td');
                    td.innerText = result[i].score;
                    tr.appendChild(td);
                }
                if (result[i].kills) {
                    let td = document.createElement('td');
                    td.innerText = result[i].kills;
                    tr.appendChild(td);
                }
                if (result[i].headshots) {
                    let td = document.createElement('td');
                    td.innerText = result[i].headshots;
                    tr.appendChild(td);
                }
                if (result[i].kd) {
                    let td = document.createElement('td');
                    td.innerText = (result[i].kd).toFixed(2);
                    tr.appendChild(td);
                }
                if (result[i].button) {
                    let td = document.createElement('td');

                    let form = document.createElement('form');
                    form.method = "get";
                    form.action = result[i].button.action;

                    let inputText = document.createElement('input');
                    inputText.type = "hidden";
                    inputText.name = "steam";
                    inputText.value = result[i].button.value;
                    // inputText.hidden = true;
                    form.appendChild(inputText);

                    let inputButton = document.createElement('input');
                    inputButton.type = "submit";
                    inputButton.value = "Profile";
                    form.append(inputButton);

                    td.appendChild(form);
                    tr.append(td);
                }
                tbody.appendChild(tr);
            }
            table.replaceChild(tbody, tableTbody);
        });
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
        start += +select.value;
        ajax({
            action: 'rankme',
            id: rankmeAjaxPhp.id,
            start,
            count: select.value
        });
    });

    prev.addEventListener('click', function(e) {
        start -= +select.value;
        if (start < 0) start = 0;
        ajax({
            action: 'rankme',
            id: rankmeAjaxPhp.id,
            start,
            count: select.value
        });
    });
})();