document.addEventListener("DOMContentLoaded", function (event) {
    const IN_PROGRESS = 'In progress';
    const DONE = 'Done';
    const ERROR = 'Error';
    let container = document.getElementById('container');
    let loginButton = document.getElementById('submit-btn');
    let contentContainer = document.getElementById('content-container');
    let headerImage = document.getElementById('header-image');
    let containerSync = document.getElementById('container-sync');
    let spanSyncStatus = document.getElementById('span-sync-status');
    let syncButton = document.getElementById('submit-btn-sync');
    let iframe = document.createElement('iframe');
    iframe.src = 'http://rest.cleverreach.com/oauth/authorize.php?client_id=rbUPpLYzJh&grant=basic&response_type=code&redirect_uri=' + redirectURL;
    iframe.classList.add('iframe');

    if (container !== null) {
        container.classList.add('border');
    }

    if (loginButton !== null) {
        loginButton.addEventListener("click", function () {
            container.classList.add('no-border');
            contentContainer.classList.add('no-display');
            headerImage.classList.add('no-display');
            container.appendChild(iframe);
            interval = setInterval(checkIfApiClientExists, 500);
        });
    }

    if (containerSync !== null) {
        setTimeout(isFirstTimeLoad, 500);
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

    function isFirstTimeLoad() {
        $.ajax({
            type: 'POST',
            cache: false,
            dataType: 'json',
            url: adminAjaxLink,
            data: {
                ajax: true,
                action: 'isfirsttimeload'
            },
            success: function (data) {
                if (data === true) {
                    changeLoadStatus();
                    synchronize();
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
            }
        });
    }

    function changeLoadStatus() {
        $.ajax({
            type: 'POST',
            cache: false,
            dataType: 'json',
            url: adminAjaxLink,
            data: {
                ajax: true,
                action: 'changeloadstatus'
            }
        });
    }


    function editSyncTemplate(data) {
        if (data === IN_PROGRESS) {
            spanSyncStatus.classList.add('in-progress-sync');
            syncButton.classList.add('disable');
        } else {
            spanSyncStatus.classList.remove('in-progress-sync');
            syncButton.classList.remove('disable');
            clearInterval(syncInterval);
            if (data === DONE) {
                spanSyncStatus.classList.add('done-sync');
            } else {
                spanSyncStatus.classList.add('error-sync');
            }
            syncButton.addEventListener("click", synchronize);
        }
        spanSyncStatus.innerHTML = data;
    }

    function synchronize() {
        spanSyncStatus.classList.remove('done-sync');
        spanSyncStatus.classList.remove('error-sync');
        spanSyncStatus.classList.add('in-progress-sync');
        spanSyncStatus.innerHTML = IN_PROGRESS;
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
