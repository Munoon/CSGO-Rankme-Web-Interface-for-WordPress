'use strict';

(function() {

    let password = document.getElementById('rankme_password');
    let input = document.getElementById('rankme_password_field');

    password.addEventListener('click', function(e) {
        if (e.target.checked) {
            input.type = 'text';
        } else {
            input.type = 'password';
        }
    });

})();