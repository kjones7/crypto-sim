var conn = new ab.Session('ws://localhost:8079',
    function() {
        conn.subscribe('cryptoData', function(topic, cryptoData) {
            //This is where you would add the new article to the DOM (beyond the scope of this tutorial)
            const cryptoDataElement = document.getElementById('crypto-data');
            cryptoDataElement.innerHTML = '';
            var br = document.createElement("br");

            for(var symbol in cryptoData) {
                let div = document.createElement('div');
                div.innerHTML = symbol + ' - ' + cryptoData[symbol];
                cryptoDataElement.appendChild(div);
                cryptoDataElement.appendChild(br);
            }
            // console.log('working');
        });
    },
    function() {
        console.warn('WebSocket connection closed');
    },
    {'skipSubprotocolCheck': true}
);