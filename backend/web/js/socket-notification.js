$(document).ready(function () {

    var ws = null;

    function connectWS() {
        console.log('try to connect');
        closeWS();

        ws = new WebSocket(socketUrl);
        ws.onopen = function() {
            // // subscribe to some channels
            // ws.send(JSON.stringify({
            //     //.... some message the I must send when I connect ....
            // }));
            console.log('websocket open:' + socketUrl);
        };

        ws.onmessage = function(e) {
            console.log('Message:', e.data);

            handler(JSON.parse(e.data));
        };

        ws.onclose = function(e) {
            console.log('Socket is closed. Reconnect will be attempted in 1 second.', e.code);
            reconnectWS();
        };

        ws.onerror = function(err) {
            console.error('Socket encountered error: ', err.message, 'Closing socket');
            reconnectWS();
        };
        console.log('connected');
    }

    function closeWS() {
        console.log('try close ws');
        if (ws != null) {
            console.log('close ws');
            ws.close();
            ws = null;
        }
    }

    function reconnectWS() {
        console.log('try reconnect');
        closeWS();
        setTimeout(function() {
            console.log('Reconnect');
            connectWS();
        }, 10000);
    }

    window.onload = function() {
        connectWS();
    };
});