// import elements from '/js/Views/Simulation/base.js';

const state = {};

const transactionWindowController = function(button, cryptoName, cryptoPrice) {
    var usdAmount; // get from view
    var cryptoAmount; // get from view

    // In SimulationView.js
    renderTransactionWindow(button, cryptoPrice);
}

// Initialize popovers
$(function () {
    $('[data-toggle="popover"]').popover()
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