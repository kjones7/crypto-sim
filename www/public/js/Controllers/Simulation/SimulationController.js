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
        const results = await transaction.buy();
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
    } catch(e) {
        console.error(e);
        alert('Error in transactionController');
    }
}

// Initialize popovers
$(function () {
    $('[data-toggle="popover"]').popover({
        container : '.popover-wrapper'
    })
});

// Buy button
elements.buyWrapper.addEventListener('click', function(e) {
    if(e.target.className === classNames.buyButton) {
        e.preventDefault(); // prevent submit

        // Grab crypto data from clicked target
        const cryptoName = e.target.closest('div').id; // ex: 'BTC'
        const cryptoPrice = e.target.value; // ex: '6536.07000000'

        // Call transaction controller
        transactionWindowController(e.target, cryptoName, cryptoPrice);
    }
});

// Submit (buy) button
elements.popoverWrapper.addEventListener('click', function(e) {
    if(e.target.id === IdNames.buySubmit) {
        const cryptoDataDiv = `div#${e.target.dataset.id}`; // ex: <div id="ETH"></div>
        const cryptoID = document.querySelector(`${cryptoDataDiv} input[name="cryptocurrency-id"]`).value;
        const type = document.querySelector(`${cryptoDataDiv} input[name="type"]`).value;
        const enteredUSDAmount = document.querySelector('.popover-body input[name="transaction-amount"]').value;

        // TODO - Implement the cryptoAmount (instead of NULL placeholder)
        transactionController(cryptoID, type, enteredUSDAmount, null);
    }
});