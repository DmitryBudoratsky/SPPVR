function logMessage(message) {
    // $("#messageContainer").append("<p>" + message + "</p><br/>");
    console.log(message);
}

$(document).ready(function () {

    var selection = $("#attachStatus");
    logMessage("start preparing");

    function call_native () {
        var result = parseInt(selection.val());
        console.log(result);
        logMessage("Result: " + result);
        // var resultString = "" + result;
        var resultString = String(result);
        console.log(resultString);

        if (typeof WebViewChannel !== 'undefined') {
            WebViewChannel.postMessage(resultString);
            logMessage("resultString dart: " + resultString);
        } else {
            logMessage("No WebViewChannel");
        }

        if ((typeof window.webkit !== 'undefined') && (typeof window.webkit.messageHandlers.onPaymentResult !== 'undefined')) {
            window.webkit.messageHandlers.onPaymentResult.postMessage(result);
            logMessage("resultString ios: " + resultString);
        } else {
            logMessage("No ios handler");
        }

        if (typeof Android !== 'undefined') {
            Android.onPaymentResult(result);
            logMessage("resultString Android: " + resultString);
        } else {
            logMessage("No android handler");
        }
    }

    setTimeout(call_native, 1000);
    selection.on("change", call_native);

    // Expose that function globally
    window.call_native = call_native;

    logMessage("End preparing");
});