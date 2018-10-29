import {IdNames, classNames, elements} from "../../Views/Simulation/base";
// TODO - View should not import from controller. Anything that needs to be used should be passed as function params
import {state} from "../../Controllers/Simulation/SimulationController";

// export const renderTransactionWindow  = function(button, cryptoPrice) {
//     // Get body of popover
//     const popoverBody = document.querySelector('.popover-body');
//     const divID = button.closest('div').dataset.abbreviation; // ID of div that contains crypto data
//     const cryptoAbbreviation = transactionState['cryptoAbbreviation'];
//
//     // Clear stale contents of popover
//     popoverBody.innerHTML = '';
//
//     // Add transaction controls into popover
//     popoverBody.innerHTML = `
//         <label for="usd-transaction" class="currency-unit-label"><span style="font-size: 20px; font-weight: bold">USD</span></label>
//
//         <div class="usd-transaction-wrapper">
//             <input type="text" name="transaction-amount" id="usd-transaction">
//         </div>
//
//         <div class="or-text-wrapper text-center"><span class="or-text">or</span></div>
//
//         <label for="crypto-transaction" class="currency-unit-label"><span style="font-size: 20px; font-weight: bold">${cryptoAbbreviation}</span></label>
//         <div class="crypto-transaction-wrapper">
//             <input type="text" id="crypto-transaction">
//         </div>
//
//         <div style="margin-top: .5rem" class="transaction-buttons text-center">
//             <button class="btn btn-success btn-sm" id="submit-transaction" data-id="${divID}" type="button" name="crypto-worth" value="${cryptoPrice}">Submit</button>
//         </div>
//     `;
// };

// const renderPortfolio = function(portfolioData) {
//     // Overwrite values
//     elements.portfolioUSDAmount.innerHTML = portfolioData.totalUSDAmount;
//     elements.cryptoWorthInUSD.innerHTML = portfolioData.cryptoWorthInUSD;
//     elements.portfolioWorth.innerHTML = portfolioData.portfolioWorth;
//
//     // Add portfolio HTML
//     elements.sellWrapper.innerHTML = portfolioData.portfolioHTML;
// };

// export const renderUsdInput = function(usd) {
//     const usdInput = document.querySelector(`#${IdNames.usdInput}`);
//     usdInput.value = usd;
// };

// export const renderCryptoInput = function(crypto) {
//     const cryptoInput = document.querySelector(`#${IdNames.cryptoInput}`);
//     cryptoInput.value = crypto;
// };

// export const renderBuyCryptocurrencies = function(cryptoData) {
//     const cryptoDataElement = document.getElementById(IdNames.buyWrapper);
//     cryptoDataElement.innerHTML = '';
//     var accumulator = '';
//
//     for(var symbol in cryptoData) {
//         let div = `
//                     <div data-abbreviation="${symbol}-buy" style="padding-bottom: 5px">
//                         <span class="crypto-abbr">${symbol}</span> - <span class="worth-in-usd">${currency(cryptoData[symbol]['price'], { precision : 8 })}</span>
//                         <button type="submit" class="btn btn-info" id="buy-crypto" data-toggle="popover" title="Buy" name="crypto-worth"
//                                 value="${cryptoData[symbol]['price']}" data-abbr="${symbol}">Buy</button>
//                         <input type="hidden" value="${cryptoData[symbol]['id']}" name="cryptocurrency-id">
//                         <input type="hidden" name="type" value="buy">
//                     </div>
//                 `;
//         accumulator += div;
//     }
//
//     cryptoDataElement.innerHTML = accumulator;
// };

export const updatePortfolio = function(portfolioData) {
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

export const updatePortfolioInfo = function(portfolioData) {
    document.querySelector(`.${classNames.portfolioWorth}`).innerHTML = portfolioData.portfolioWorth;
    document.querySelector(`.${classNames.cryptoWorthInUSD}`).innerHTML = portfolioData.cryptoWorthInUSD;
    document.querySelector(`.${classNames.portfolioUSDLeft}`).innerHTML = portfolioData.totalUSDAmount;
};

export const getPortfolioId = function()
{
    return elements.portfolioID.value;
};

export const getBuyCryptoTable = function() {
    return `
        <table id="buy-crypto-table" class="data-table display compact">
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

export const getSellCryptoTable = function() {
    return `
        <table id="sell-crypto-table" class="data-table display compact">
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

export const getPortfolioInfo = function() {
    return `<div class="card">
                <div class="card-header">
                    Portfolio Information
                </div>
                <div class="card-body">
                    <div>
                        <p>Portfolio Worth: $<span class="portfolio-worth"></span></p>
                    </div>
                    <div>
                        <p>Crypto Worth: $<span class="portfolio-crypto-worth"></span></p>
                    </div>
                    <div>
                        <p>USD Left to Spend: $<span class="portfolio-usd-left"></span></p>
                    </div>
                </div>
            </div>`;
};

/**
 * Repopulates the buy crypto datatable with updated cryptocurrency data from the database. This is used in the
 * websocket connection to update the table periodically with fresh data.
 * @param {array} cryptoData - Array containing all of the cryptocurrencies from the database
 */
export const repopulateBuyCryptoTable = function(cryptoData) {
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

export const repopulateSellCryptoTable = function(cryptoData) {
    state.sellDataTable.clear();
    state.sellDataTable.rows.add(cryptoData.cryptocurrencies);
    state.sellDataTable.draw();
};

export const renderChildRow = function(elementClicked, dataTable, tableId, cryptoId) {
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
                        <input type="hidden" name="cryptocurrency-id" value="${cryptoId}">
                    </div>
                </div>
            </div>
        `;
    } else {
        throw new Error;
    }
};

export const getTransactionType = function(element) {
    return element.dataset['type'];
};

export const getTransactionAmount = function(element) {
    return element.parentElement.parentElement.querySelector(`.${classNames.transactionInput}`).value;
};

export const getCryptocurrencyId = function(element) {
    return element.dataset['id'];
};

export const getLeaderboardButton = function() {
    return `
        <br>
        <div id="leaderboard-button">
            <button type="button" class="btn btn-info" data-toggle="modal" data-target="#leaderboardModal">Leaderboard</button>
        </div>
        <br>
    `;
};

/**
 * Renders the leaderboard
 * @param {DataTable} leaderboardTable - The leaderboard datatable
 * @param {object} leaderboardData - Contains an object that contains the position, username, portfolio title, and
 *     portfolio worth of all public competitive portfolios
 */
export const renderLeaderboard = function(leaderboardTable, leaderboardData) {
    leaderboardTable.clear();
    leaderboardTable.rows.add(leaderboardData.leaderboardEntries);
    leaderboardTable.draw();
};