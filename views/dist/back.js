let container = document.getElementsByClassName("container")[0];
let button = document.getElementsByClassName("submitBtn")[0];
let contentContainer = document.getElementsByClassName("contentContainer")[0];
let headerImage = document.getElementsByClassName("headerImage")[0];
console.log("yeah");

button.addEventListener("click", function () {
    container.style.border = 'none';
    contentContainer.style.display = 'none';
    headerImage.style.display = 'none';
    let element = '<iframe style="height: 60vh; width: 63vw; margin-left: 0vw;" src="https://rest.cleverreach.com/oauth/authorize.php?client_id=rbUPpLYzJh&grant=basic&response_type=code&redirect_uri=http%3A%2F%2Fcleverreach.test%2Findex.php"></iframe>';
    container.innerHTML = element;
});


