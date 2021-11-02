document.addEventListener("DOMContentLoaded", function (event) {
    let container = document.getElementById('container');
    let loginButton = document.getElementById('submit-btn');
    let contentContainer = document.getElementById('content-container');
    let headerImage = document.getElementById('header-image');
    let iframe = document.createElement('iframe');
    iframe.src = 'http://rest.cleverreach.com/oauth/authorize.php?client_id=rbUPpLYzJh&grant=basic&response_type=code&redirect_uri=' + redirectURL;
    iframe.classList.add('iframe');

    if (loginButton !== null) {
        loginButton.addEventListener("click", function () {
            container.classList.add('no-border');
            contentContainer.classList.add('no-display');
            headerImage.classList.add('no-display');
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
