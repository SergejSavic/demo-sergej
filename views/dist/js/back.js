document.addEventListener("DOMContentLoaded", function (event) {
    let container = document.getElementById('container');
    let loginButton = document.getElementById('submitBtn');
    let contentContainer = document.getElementById('contentContainer');
    let headerImage = document.getElementById('headerImage');
    let iframe = document.createElement('iframe');
    iframe.src = 'http://rest.cleverreach.com/oauth/authorize.php?client_id=rbUPpLYzJh&grant=basic&response_type=code&redirect_uri=http://prestashop.test/en/module/demo/view';
    iframe.classList.add('iframe');

    if (loginButton !== null) {
        loginButton.addEventListener("click", function () {
            container.style.border = 'none';
            contentContainer.style.display = 'none';
            headerImage.style.display = 'none';
            container.appendChild(iframe);
            interval = setInterval(checkIfApiClientExist, 50);
        });
    }

    function checkIfApiClientExist() {
        fetch('http://prestashop.test/module/demo/validation', {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
            }
        }).then(function (response) {
            if (response.status === 200) {
                clearInterval(interval);
                location.reload();
            }
        });
    }
});
