document.addEventListener("DOMContentLoaded", function (event) {
    let container = document.getElementById('container');
    let loginButton = document.getElementById('submitBtn');
    let contentContainer = document.getElementById('contentContainer');
    let headerImage = document.getElementById('headerImage');
    let iframe = document.createElement('iframe');
    iframe.src = 'http://rest.cleverreach.com/oauth/authorize.php?client_id=rbUPpLYzJh&grant=basic&response_type=code&redirect_uri=http://prestashop.test/en/module/demo/view';
    iframe.classList.add('iframe');
    container.classList.add('border');

    if (loginButton !== null) {
        loginButton.addEventListener("click", function () {
            container.classList.add('noBorder');
            contentContainer.classList.add('noDisplay');
            headerImage.classList.add('noDisplay');
            container.appendChild(iframe);
            interval = setInterval(checkIfApiClientExists, 500);
        });
    }

    function checkIfApiClientExists() {
        $.ajax({
            type: 'POST',
            cache: false,
            dataType: 'json',
            url: adminAjaxLink,
            data: {
                ajax: true,
                action: 'checkifclientexist'
            },
            success: function (data) {
                if (data == true) {
                    clearInterval(interval);
                    location.reload();
                }
            }
        });
    }

});
