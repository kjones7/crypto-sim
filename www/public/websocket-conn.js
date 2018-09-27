// var conn = new ab.Session('ws://localhost:8079',
//     function() {
//         conn.subscribe('cryptoData', function(topic, cryptoData) {
//             //This is where you would add the new article to the DOM (beyond the scope of this tutorial)
//             const cryptoDataElement = document.getElementById('crypto-data');
//             cryptoDataElement.innerHTML = '';
//             var accumulator = '';
//
//             for(var symbol in cryptoData) {
//                 // let div = `
//                 //     <!--<form action="" method="POST">-->
//                 //         <div id="${symbol}" style="padding-bottom: 5px">
//                 //             <span class="crypto">${symbol} - $${cryptoData[symbol]}</span>
//                 //             <input type="text" name="transaction-amount">
//                 //             <button type="submit" class="buy-crypto" value="${cryptoData[symbol]}">Buy</button>
//                 //         </div>
//                 //     <!--</form>-->
//                 // `;
//                 let div = `
//                     <div data-abbreviation="${symbol}-buy" style="padding-bottom: 5px">
//                         <span class="crypto-abbr">${symbol}</span> - <span class="worth-in-usd">${currency(cryptoData[symbol], { precision : 8 })}</span>
//                         <button type="submit" class="btn btn-info" id="buy-crypto" data-toggle="popover" title="Buy" name="crypto-worth"
//                                 value="${cryptoData[symbol]}" data-abbr="${symbol}">Buy</button>
//                         <input type="hidden" value="${cryptoData[symbol]}" name="cryptocurrency-id">
//                         <input type="hidden" name="type" value="buy">
//                     </div>
//                 `;
//                 accumulator += div;
//             }
//
//             cryptoDataElement.innerHTML = accumulator;
//             initializePopovers();
//         });
//     },
//     function() {
//         console.warn('WebSocket connection closed');
//     },
//     {'skipSubprotocolCheck': true}
// );