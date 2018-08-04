var conn = new ab.Session('ws://localhost:8079',
    function() {
        conn.subscribe('cryptoData', function(topic, cryptoData) {
            //This is where you would add the new article to the DOM (beyond the scope of this tutorial)
            const cryptoDataElement = document.getElementById('crypto-data');
            cryptoDataElement.innerHTML = '';

            for(var symbol in cryptoData) {
                let div = `
                    <div id="${symbol}" style="padding-bottom: 5px">
                        <span class="crypto">${symbol} - $${cryptoData[symbol]}</span>
                        <input type="text" name="buy-amount">
                        <button type="submit" class="buy-crypto" value="${cryptoData[symbol]}">Buy</button>
                    </div>
                `;
                cryptoDataElement.innerHTML += div;
            }
        });
    },
    function() {
        console.warn('WebSocket connection closed');
    },
    {'skipSubprotocolCheck': true}
);