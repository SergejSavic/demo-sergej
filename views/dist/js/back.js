document.addEventListener("DOMContentLoaded", function (event) {
    let container = document.getElementById('container');
    let loginButton = document.getElementById('submitBtn');
    let contentContainer = document.getElementById('contentContainer');
    let headerImage = document.getElementById('headerImage');
    let containerSync = document.getElementById('containerSync');
    let spanSyncStatus = document.getElementById('spanSyncStatus');
    let syncButton = document.getElementById('submitBtnSync');
    let iframe = document.createElement('iframe');
    iframe.src = 'http://rest.cleverreach.com/oauth/authorize.php?client_id=rbUPpLYzJh&grant=basic&response_type=code&redirect_uri=http://prestashop.test/en/module/demo/view';
    iframe.classList.add('iframe');

    if (container !== null) {
        container.classList.add('border');
    }

    if (loginButton !== null) {
        loginButton.addEventListener("click", function () {
            container.classList.add('noBorder');
            contentContainer.classList.add('noDisplay');
            headerImage.classList.add('noDisplay');
            container.appendChild(iframe);
            interval = setInterval(checkIfApiClientExists, 500);
        });
    }

    if (containerSync !== null) {
        syncInterval = setInterval(checkSyncStatus, 500);
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

    function checkSyncStatus() {
        $.ajax({
            type: 'POST',
            cache: false,
            dataType: 'json',
            url: adminAjaxLink,
            data: {
                ajax: true,
                action: 'checksyncstatus'
            },
            success: function (data) {
                editSyncTemplate(data);
                console.log(data);
            }
        });
    }

    function editSyncTemplate(data) {
        if (data === 'In progress') {
            spanSyncStatus.classList.add('inProgressSync');
        } else {
            spanSyncStatus.classList.remove('inProgressSync');
            syncButton.classList.remove('disable');
            clearInterval(syncInterval);
            if (data === 'Done') {
                spanSyncStatus.classList.add('DoneSync');
            } else {
                spanSyncStatus.classList.add('ErrorSync');
            }
            syncButton.addEventListener("click", synchronize);
        }
        spanSyncStatus.innerHTML = data;
    }

    function synchronize() {
        spanSyncStatus.classList.remove('DoneSync');
        spanSyncStatus.classList.remove('ErrorSync');
        spanSyncStatus.classList.add('inProgressSync');
        spanSyncStatus.innerHTML = 'In progress';
        syncInterval = setInterval(checkSyncStatus, 500);
        $.ajax({
            type: 'POST',
            cache: false,
            dataType: 'json',
            url: adminAjaxLink,
            data: {
                ajax: true,
                action: 'synchronize'
            }
        });
    }
});
