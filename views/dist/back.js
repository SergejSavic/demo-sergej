document.addEventListener("DOMContentLoaded", function(event) {
    let container = document.getElementById('container');
    let button = document.getElementById('submitBtn');
    let contentContainer = document.getElementById('contentContainer');
    let headerImage = document.getElementById('headerImage');
    let iframe = document.createElement('iframe');
    iframe.src = 'http://rest.cleverreach.com/oauth/authorize.php?client_id=rbUPpLYzJh&grant=basic&response_type=code&redirect_uri=http://prestashop.test/en/module/demo/view';
    iframe.style.cssText += 'height: 60vh; width: 63vw; margin-left: 0vw;';

    button.addEventListener("click", function () {
        container.style.border = 'none';
        contentContainer.style.display = 'none';
        headerImage.style.display = 'none';
        container.appendChild(iframe);
        setTimeout(myFunction, 9000)
    });

    function myFunction() {
        location.reload();
    }

});
