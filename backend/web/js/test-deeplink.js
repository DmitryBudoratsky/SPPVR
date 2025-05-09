function logMessage(message) {
    console.log(message);
}

function isAndroid() {
    var userAgent = navigator.userAgent || navigator.vendor || window.opera;
    if (/android/i.test(userAgent)) {
        return true;
    }
    return false;
}

function isIOS() {
    var userAgent = navigator.userAgent || navigator.vendor || window.opera;
    if (/iPad|iPhone|iPod/.test(userAgent) && !window.MSStream) {
        return true;
    }
}

function openApp() {
    if (isAndroid()) {
        window.location.replace("promminer://open.my.app");
    } else if (isIOS()) {
        window.location.replace('promminer://promminer.app')
    }
}
$(document).ready(function () {

    $('#openApp').on('click', function(event){
        openApp()
    });
});