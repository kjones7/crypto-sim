// import elements from '/js/Views/Simulation/base.js';

const state = {};

// Controller for the popover that contains the transaction controls
const transactionWindowController = function(button, cryptoName, cryptoPrice) {
    var usdAmount; // get from view
    var cryptoAmount; // get from view

    // In SimulationView.js
    renderTransactionWindow(button, cryptoPrice);
}

const transactionController = async function(cryptoID, type, usdAmount, cryptoAmount){
    const transaction = new Transaction(cryptoID, type, usdAmount, cryptoAmount);

    try {
        const results = await transaction.saveTransaction();
        const portfolioData = {
            totalUSDAmount : results.updatedPortfolio.USDAmount,
            cryptoWorthInUSD : results.updatedPortfolio.cryptoWorthInUSD,
            cryptocurrencies : results.updatedPortfolio.cryptocurrencies,
            portfolioID : results.updatedPortfolio.id,
            portfolioWorth : results.updatedPortfolio.portfolioWorth,
            title : results.updatedPortfolio.title,
            portfolioHTML : results.content
        }

        // In SimulationView.js
        renderPortfolio(portfolioData);

        // reinitialize popovers since the sell buttons are new and uninitialized
        initializePopovers();

    } catch(e) {
        console.error(e);
        alert('Error in transactionController');
    }
}

// Initialize popovers
$(initializePopovers());

// Initial 'Buy' button that opens up the transaction window popover
elements.buyWrapper.addEventListener('click', function(e) {
    if(e.target.className === classNames.buyButton) {
        handleBuyOrSellButtonPress(e);
    }
});

// Submit transaction button (buy or sell)
elements.popoverWrapper.addEventListener('click', function(e) {
    if(e.target.id === IdNames.submitTransaction) {
        const cryptoDataDiv = `div[data-abbreviation=${e.target.dataset.id}]`; // ex: <div id="ETH-buy"></div>
        const cryptoID = document.querySelector(`${cryptoDataDiv} input[name="cryptocurrency-id"]`).value;
        const type = document.querySelector(`${cryptoDataDiv} input[name="type"]`).value;
        const enteredUSDAmount = document.querySelector('.popover-body input[name="transaction-amount"]').value;

        // TODO - Implement the cryptoAmount (instead of NULL placeholder)
        transactionController(cryptoID, type, enteredUSDAmount, null);
    }
});

// Initial 'Sell' button that opens up the transaction window popover
elements.sellWrapper.addEventListener('click', function(e){
    if(e.target.className === classNames.sellButton) {
        handleBuyOrSellButtonPress(e);
    }
});

/**
 * Handles the even when the buy or sell button gets clicked.
 * Runs transactionWindowController() to open a transaction window
 * @param {Event} e - The click event that is being handled
 */
function handleBuyOrSellButtonPress(e) {
    e.preventDefault(); // prevent submit

    // Grab crypto data from clicked target
    const cryptoName = e.target.closest('div').id; // ex: 'BTC'
    const cryptoPrice = e.target.value; // ex: '6536.07000000'
    const type =

    // Call transaction controller
    transactionWindowController(e.target, cryptoName, cryptoPrice);
}

function initializePopovers(popoverIdentifiers) {
    if(popoverIdentifiers === undefined) {
        popoverIdentifiers = '';
    }

    $(`[data-toggle="popover"] ${popoverIdentifiers}`).popover({
        container : '.popover-wrapper'
    })
}