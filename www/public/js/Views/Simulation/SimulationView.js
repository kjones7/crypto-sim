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

// const renderPortfolio = function(portfolioData) {
//     // Overwrite values
//     elements.portfolioUSDAmount.innerHTML = portfolioData.totalUSDAmount;
//     elements.cryptoWorthInUSD.innerHTML = portfolioData.cryptoWorthInUSD;
//     elements.portfolioWorth.innerHTML = portfolioData.portfolioWorth;
//
//     // Add portfolio HTML
//     elements.sellWrapper.innerHTML = portfolioData.portfolioHTML;
// };

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

const updatePortfolio = function(portfolioData) {
    const numRecords = state.sellDataTable.rows().data().length;

    var cellCryptoId = '';
    var newWorthInUSD = '';

    for(var i = 0; i < numRecords; i++) {
        cellCryptoId = state.sellDataTable.cell(i, 1).data();
        newWorthInUSD = portfolioData['cryptocurrencies'][cellCryptoId]['worthInUSD'];

        state.sellDataTable.cell(i, 4).data(newWorthInUSD);
    }

    state.sellDataTable.draw();
};

const getPortfolioId = function()
{
    return elements.portfolioID.value;
};

const getBuyCryptoTable = function() {
    return `
        <table id="buy-crypto-table" class="data-table">
            <thead>
                <tr>
                    <th></th>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Abbreviation</th>
                    <th>Worth In USD</th>
                </tr>
            </thead>
            <tfoot>
                <tr>
                    <th></th>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Abbreviation</th>
                    <th>Worth In USD</th>
                </tr>
            </tfoot>
            
        </table>
    `;
};

const getSellCryptoTable = function() {
    return `
        <table id="sell-crypto-table" class="data-table">
            <thead>
                <tr>
                    <th></th>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Abbreviation</th>
                    <th>Worth In USD</th>
                    <th>Quantity</th>
                </tr>
            </thead>
            <tfoot>
                <tr>
                    <th></th>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Abbreviation</th>
                    <th>Worth In USD</th>
                    <th>Quantity</th>
                </tr>
            </tfoot>
        </table>
    `;
};

/**
 * Repopulates the buy crypto datatable with updated cryptocurrency data from the database. This is used in the
 * websocket connection to update the table periodically with fresh data.
 * @param {array} cryptoData - Array containing all of the cryptocurrencies from the database
 */
const repopulateBuyCryptoTable = function(cryptoData) {
    const numRecords = state.buyDataTable.rows().data().length;

    var counter = 0;
    var cellCryptoId = '';
    var newWorthInUSD = '';

    while(counter < numRecords) {
        cellCryptoId = state.buyDataTable.cell(counter, 1).data();
        newWorthInUSD = cryptoData[cellCryptoId]['worth_in_USD'];

        state.buyDataTable.cell(counter, 4).data(newWorthInUSD);

        counter += 1;
    }

    state.buyDataTable.draw();
};

const renderChildRow = function(elementClicked, dataTable, tableId, cryptoId) {
    var childRowContent = getChildRowContent(tableId, cryptoId);

    var tr = $(elementClicked).parents('tr');
    var row = dataTable.row( tr );

    if ( row.child.isShown() ) {
        // This row is already open - close it
        row.child.hide();
        tr.removeClass('shown');
    }
    else {
        // Open this row (the format() function would return the data to be shown)
        row.child( childRowContent ).show();
        tr.addClass('shown');
    }
};

const getChildRowContent = function(tableId, cryptoId) {
    if(tableId === IdNames.buyCryptoTable) {
        return `
            <div class="row transaction-wrapper">
                <div class="col-md-1 offset-md-1">
                    <button class="btn btn-success btn-sm submit-transaction-btn" data-type="buy" data-id="${cryptoId}">Submit</button>
                </div>
                <div class="col-md-8">
                    <div class="form-group form-inline">
                        <label for="buy-usd-transaction-input">USD</label>
                        <input class="form-control usd-transaction-input" id="buy-usd-transaction-input">
                        <input type="hidden" name="cryptocurrency-id" value="${cryptoId}">
                    </div>
                </div>
            </div>
        `;
    } else if (tableId === IdNames.sellCryptoTable) {
        return `
            <div class="row transaction-wrapper">
                <div class="col-md-1 offset-md-1">
                    <button class="btn btn-success btn-sm submit-transaction-btn" data-type="sell" data-id="${cryptoId}">Submit</button>
                </div>
                <div class="col-md-8">
                    <div class="form-group form-inline">
                        <label for="buy-usd-transaction-input">USD</label>
                        <input class="form-control usd-transaction-input" id="sell-usd-transaction-input">
                    </div>
                </div>
            </div>
        `;
    } else {
        throw new Error;
    }
};

const getTransactionType = function(element) {
    return element.dataset['type'];
};

const getTransactionAmount = function(element) {
    return element.parentElement.parentElement.querySelector(`.${classNames.transactionInput}`).value;
};

const getCryptocurrencyId = function(element) {
    return element.dataset['id'];
};
