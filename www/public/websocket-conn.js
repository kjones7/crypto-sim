var conn = new ab.Session('ws://localhost:8079',
    function() {
        conn.subscribe('cryptoData', function(topic, cryptoData) {
            // This is where you would add the new article to the DOM (beyond the scope of this tutorial)
            // const cryptoDataElement = document.getElementsByClassName('.crypto-data');
            // for(var symbol in cryptoData) {
            //     cryptoDataElement.append(symbol + ' - ' + cryptoData[symbol]);
            // }
            console.log('working');
        });
    },
    function() {
        console.warn('WebSocket connection closed');
    },
    {'skipSubprotocolCheck': true}
);