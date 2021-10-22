document.addEventListener("DOMContentLoaded", function(event) {
    let container = document.getElementsByClassName("container")[0];
    let button = document.getElementsByClassName("submitBtn")[0];
    let contentContainer = document.getElementsByClassName("contentContainer")[0];
    let headerImage = document.getElementsByClassName("headerImage")[0];
    let element = '<iframe id="frameId" style="height: 60vh; width: 63vw; margin-left: 0vw;" src="http://rest.cleverreach.com/oauth/authorize.php?client_id=rbUPpLYzJh&grant=basic&response_type=code&redirect_uri=' +
        'http://prestashop.test/en/module/demo/view"></iframe>';

    button.addEventListener("click", function () {
        container.style.border = 'none';
        contentContainer.style.display = 'none';
        headerImage.style.display = 'none';
        container.innerHTML = element;
        setTimeout(myFunction, 7000)
    });


    function myFunction() {
        location.reload();
    }

});
