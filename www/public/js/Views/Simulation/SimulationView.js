const renderTransactionWindow  = function(button, cryptoPrice) {
    // Get body of popover
    const popoverBody = document.querySelector('.popover-body');
    const divID = button.closest('div').dataset.abbreviation; // ID of div that contains crypto data
    const cryptoAbbreviation = document.querySelector(`${button.parentElement.className} .crypto-abbr`).innerHTML;

    // Clear stale contents of popover
    popoverBody.innerHTML = '';

    // Add transaction controls into popover
    popoverBody.innerHTML = `
        <label for="usd-transaction" class="currency-unit-label"><span style="font-size: 20px; font-weight: bold">USD</span></label>
        
        <div class="usd-transaction-wrapper">
            <input type="text" name="transaction-amount" id="usd-transaction">
        </div>

        <div class="or-text-wrapper text-center"><span class="or-text">or</span></div>
        
        <label for="crypto-transaction" class="currency-unit-label"><span style="font-size: 20px; font-weight: bold">${cryptoAbbreviation}</span></label>
        <div class="crypto-transaction-wrapper">
            <input type="text" id="crypto-transaction">
        </div>
        
        <div style="margin-top: .5rem" class="transaction-buttons text-center">
            <button class="btn btn-success btn-sm" id="submit-transaction" data-id="${divID}" type="button" name="crypto-worth" value="${cryptoPrice}">Submit</button>
        </div>
    `;
}

const renderPortfolio = function(portfolioData) {
    // Overwrite values
    elements.portfolioUSDAmount.innerHTML = portfolioData.totalUSDAmount;
    elements.cryptoWorthInUSD.innerHTML = portfolioData.cryptoWorthInUSD;
    elements.portfolioWorth.innerHTML = portfolioData.portfolioWorth;

    // Add portfolio HTML
    elements.sellWrapper.innerHTML = portfolioData.portfolioHTML;
}