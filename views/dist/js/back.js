document.addEventListener("DOMContentLoaded", function (event) {
    let container = document.getElementById('container');
    let loginButton = document.getElementById('submit-btn');
    let contentContainer = document.getElementById('content-container');
    let headerImage = document.getElementById('header-image');
    let iframe = document.createElement('iframe');
    let interval;
    iframe.src = cleverReachURL;
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
                if (data === true) {
                    clearInterval(interval);
                    location.reload();
                }
            }
        });
    }
});
