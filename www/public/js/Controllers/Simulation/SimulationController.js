// // import elements from '/js/Views/Simulation/base.js';
//
// // Stores data about the state once a transaction is started
// const transactionState = {
//     cryptoAbbreviation : '',
//     worthInUsd: ''
// };
//
// // Initialization START
//
// // Initialize popovers
// $(initializePopovers());
//
// // Initialization END
//
// // Controllers START
//
// // Controller for the popover that contains the transaction controls
// const transactionWindowController = function(button, cryptoName, cryptoPrice) {
//     var usdAmount; // get from view
//     var cryptoAmount; // get from view
//
//     // In SimulationView.js
//     renderTransactionWindow(button, cryptoPrice);
// }
//
// const transactionController = async function(cryptoID, type, usdAmount, cryptoAmount){
//     const transaction = new Transaction(cryptoID, type, usdAmount, cryptoAmount);
//
//     try {
//         const results = await transaction.saveTransaction();
//         const portfolioData = {
//             totalUSDAmount : results.updatedPortfolio.USDAmount,
//             cryptoWorthInUSD : results.updatedPortfolio.cryptoWorthInUSD,
//             cryptocurrencies : results.updatedPortfolio.cryptocurrencies,
//             portfolioID : results.updatedPortfolio.id,
//             portfolioWorth : results.updatedPortfolio.portfolioWorth,
//             title : results.updatedPortfolio.title,
//             portfolioHTML : results.content
//         };
//
//         // In SimulationView.js
//         renderPortfolio(portfolioData);
//
//         // reinitialize popovers since the sell buttons are new and uninitialized
//         initializePopovers();
//
//     } catch(e) {
//         console.error(e);
//         alert('Error in transactionController');
//     }
// };
//
//
//
// const usdInputChangeController = function(inputElement) {
//     const usd = inputElement.value;
//     const crypto = calculateCryptoFromUsd(usd);
//
//     if(crypto !== null) {
//         renderCryptoInput(crypto);
//     }
// };
//
// const cryptoInputChangeController = function(inputElement) {
//     const crypto = inputElement.value;
//     const usd = calculateUsdFromCrypto(crypto);
//
//     if(usd !== null) {
//         renderUsdInput(usd);
//     }
// };
//
// // Controllers END
//
// // Event listeners START
//
// // Initial 'Buy' button that opens up the transaction window popover
// elements.buyWrapper.addEventListener('click', function(e) {
//     if(e.target.id === IdNames.buyButton) {
//         // $('[data-toggle="popover"]').popover('hide');
//         populateTransactionState(e.target);
//         handleBuyOrSellButtonPress(e);
//     }
// });
//
// // Submit transaction button (buy or sell)
// elements.popoverWrapper.addEventListener('click', function(e) {
//     if(e.target.id === IdNames.submitTransaction) {
//         const cryptoDataDiv = `div[data-abbreviation=${e.target.dataset.id}]`; // ex: <div id="ETH-buy"></div>
//         const cryptoID = document.querySelector(`${cryptoDataDiv} input[name="cryptocurrency-id"]`).value;
//         const type = document.querySelector(`${cryptoDataDiv} input[name="type"]`).value;
//         const enteredUSDAmount = document.querySelector('.popover-body input[name="transaction-amount"]').value;
//
//         // TODO - Implement the cryptoAmount (instead of NULL placeholder)
//         transactionController(cryptoID, type, enteredUSDAmount, null);
//         $('[data-toggle="popover"]').popover('hide');
//     }
// });
//
// // Initial 'Sell' button that opens up the transaction window popover
// elements.sellWrapper.addEventListener('click', function(e){
//     if(e.target.id === IdNames.sellButton) {
//         populateTransactionState(e.target);
//         handleBuyOrSellButtonPress(e);
//     }
// });
//
// elements.popoverWrapper.addEventListener('input', function(e){
//     if(e.target.id === IdNames.usdInput) {
//         handleUsdInputChange(e.target);
//     } else if(e.target.id === IdNames.cryptoInput) {
//         handleCryptoInputChange(e.target);
//     }
// });
//
// // closes popover once you click out of it
// $('html').on('click', function(e) {
//     if (typeof $(e.target).data('toggle') == 'undefined' && !$(e.target).parents().is('.popover-wrapper')) {
//         $('[data-toggle="popover"]').popover('hide');
//     }
// });
// // Event listeners END
//
// // Handlers START
// function handleUsdInputChange(inputElement) {
//     usdInputChangeController(inputElement);
// }
//
// function handleCryptoInputChange(inputElement) {
//     cryptoInputChangeController(inputElement);
// }
//
// /**
//  * Handles the even when the buy or sell button gets clicked.
//  * Runs transactionWindowController() to open a transaction window
//  * @param {Event} e - The click event that is being handled
//  */
// function handleBuyOrSellButtonPress(e) {
//     // e.preventDefault(); // prevent submit
//
//     // Grab crypto data from clicked target
//     const cryptoName = e.target.closest('div').id; // ex: 'BTC'
//     const cryptoPrice = e.target.value; // ex: '6536.07000000'
//     // const type =
//
//     // Call transaction controller
//     transactionWindowController(e.target, cryptoName, cryptoPrice);
// }
//
// // Handlers END
//
// // Helpers START
//
// function initializePopovers(popoverIdentifiers) {
//     if(popoverIdentifiers === undefined) {
//         popoverIdentifiers = '';
//     }
//
//     $(`[data-toggle="popover"] ${popoverIdentifiers}`).popover({
//         container : '.popover-wrapper'
//     })
// }
//
// function calculateCryptoFromUsd(usd) {
//     usd = currency(usd, { precision : 8 });
//     const cryptoWorthInUSD = currency(transactionState['worthInUsd'], { precision : 8 });
//
//     return usd.divide(cryptoWorthInUSD);
// }
//
// function calculateUsdFromCrypto(crypto) {
//     crypto = currency(crypto, { precision : 8 });
//     const cryptoWorthInUSD = currency(transactionState['worthInUsd'], { precision : 8 });
//
//     return crypto.multiply(cryptoWorthInUSD);
// }
//
// function populateTransactionState(button) {
//     transactionState['cryptoAbbreviation'] = '';
//     transactionState['worthInUsd'] = '';
//
//     transactionState['cryptoAbbreviation'] = button.dataset.abbr;
//     transactionState['worthInUsd'] = currency(
//         button.value,
//         {
//             precision : 8
//         }
//     );
// }
// // Helpers END
//
// // START - Websocket connection
//
// var conn = new ab.Session('ws://localhost:8079',
//     function() {
//         conn.subscribe('cryptoData', async function (topic, cryptoData) {
//             // render cryptocurrencies to buy
//             renderBuyCryptocurrencies(cryptoData);
//
//             // get updated portfolio
//             const portfolioId = getPortfolioId();
//             const update = new Update(portfolioId);
//             const results = await update.updatePortfolio();
//             const portfolioData = {
//                 totalUSDAmount : results.updatedPortfolio.USDAmount,
//                 cryptoWorthInUSD : results.updatedPortfolio.cryptoWorthInUSD,
//                 cryptocurrencies : results.updatedPortfolio.cryptocurrencies,
//                 portfolioID : results.updatedPortfolio.id,
//                 portfolioWorth : results.updatedPortfolio.portfolioWorth,
//                 title : results.updatedPortfolio.title,
//                 portfolioHTML : results.content
//             };
//             // render updated portfolio
//             renderPortfolio(portfolioData);
//
//             // reinitalize popovers
//             $('.popover-wrapper')[0].innerHTML = ''; // TEMP - this deletes the popover once data is refreshed
//             initializePopovers();
//         });
//     },
//     function() {
//         console.warn('WebSocket connection closed');
//     },
//     {'skipSubprotocolCheck': true}
// );
//
// // END - Websocket Connection

$(document).ready( function () {
    const buyCryptoTableHTML = getBuyCryptoTable();
    var config = {
        content: [{
            type: 'row',
            content:[{
                type: 'component',
                componentName: 'testComponent',
                componentState: { label: buyCryptoTableHTML }
            },{
                type: 'column',
                content:[{
                    type: 'component',
                    componentName: 'testComponent',
                    componentState: { label: buyCryptoTableHTML }
                },{
                    type: 'component',
                    componentName: 'testComponent',
                    componentState: { label: buyCryptoTableHTML }
                }]
            }]
        }]
    };
    var myLayout = new GoldenLayout( config );

    myLayout.registerComponent( 'testComponent', function( container, componentState ){
        container.getElement().html( '<h5>' + componentState.label + '</h5>' );
    });

    myLayout.init();

    $(`#${IdNames.buyCryptoTable}`).DataTable();
} );
