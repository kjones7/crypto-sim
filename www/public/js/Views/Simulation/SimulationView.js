const renderTransactionWindow  = function(button, cryptoPrice) {
    // Get body of popover
    const popoverBody = document.querySelector('.popover-body');
    const divID = button.closest('div').dataset.abbreviation; // ID of div that contains crypto data
    const cryptoAbbreviation = transactionState['cryptoAbbreviation'];

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
};

const renderPortfolio = function(portfolioData) {
    // Overwrite values
    elements.portfolioUSDAmount.innerHTML = portfolioData.totalUSDAmount;
    elements.cryptoWorthInUSD.innerHTML = portfolioData.cryptoWorthInUSD;
    elements.portfolioWorth.innerHTML = portfolioData.portfolioWorth;

    // Add portfolio HTML
    elements.sellWrapper.innerHTML = portfolioData.portfolioHTML;
};

const renderUsdInput = function(usd) {
    const usdInput= document.querySelector(`#${IdNames.usdInput}`);
    usdInput.value = usd;
};

const renderCryptoInput = function(crypto) {
    const cryptoInput = document.querySelector(`#${IdNames.cryptoInput}`);
    cryptoInput.value = crypto;
};

const renderBuyCryptocurrencies = function(cryptoData) {
    const cryptoDataElement = document.getElementById(IdNames.buyWrapper);
    cryptoDataElement.innerHTML = '';
    var accumulator = '';

    for(var symbol in cryptoData) {
        let div = `
                    <div data-abbreviation="${symbol}-buy" style="padding-bottom: 5px">
                        <span class="crypto-abbr">${symbol}</span> - <span class="worth-in-usd">${currency(cryptoData[symbol]['price'], { precision : 8 })}</span>
                        <button type="submit" class="btn btn-info" id="buy-crypto" data-toggle="popover" title="Buy" name="crypto-worth"
                                value="${cryptoData[symbol]['price']}" data-abbr="${symbol}">Buy</button>
                        <input type="hidden" value="${cryptoData[symbol]['id']}" name="cryptocurrency-id">
                        <input type="hidden" name="type" value="buy">
                    </div>
                `;
        accumulator += div;
    }

    cryptoDataElement.innerHTML = accumulator;
};

const getPortfolioId = function()
{
    return elements.portfolioID.innerHTML;
};

const getBuyCryptoTable = function() {
    return `
        <table id="buy-crypto-table" class="display">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Abbreviation</th>
                    <th>Worth In USD</th>
                </tr>
            </thead>
            <tfoot>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Abbreviation</th>
                    <th>Worth In USD</th>
                </tr>
            </tfoot>
            
        </table>
    `;
};

/**
 * Repopulates the buy crypto datatable with updated cryptocurrency data from the database. This is used in the
 * websocket connection to update the table periodically with fresh data.
 * @param {array} cryptoData - Array containing all of the cryptocurrencies from the database
 * @param dataTable - The buy crypto data table
 */
const repopulateBuyCryptoTable = function(cryptoData, dataTable) {
    // var table = $(`#${IdNames.buyCryptoTable}`).DataTable();

    state.buyDataTable.clear();
    state.buyDataTable.rows.add(cryptoData);
    state.buyDataTable.draw();
    // dataTable.rows().add(cryptoData);
    // dataTable.draw();
};
