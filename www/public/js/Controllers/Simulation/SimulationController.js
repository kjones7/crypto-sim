import elements from '/js/Views/Simulation/base.js';

$(document).ready( async function () {
    // Initialize
    initializeGoldenLayout();
    initializeBuyCryptoDataTable();
    initializeWebsocketConn();
    initializeSellCryptoDataTable();

    const results = await state.updateModel.updatePortfolio();
    const portfolioData = {
        totalUSDAmount : results.updatedPortfolio.USDAmount,
        cryptoWorthInUSD : results.updatedPortfolio.cryptoWorthInUSD,
        cryptocurrencies : results.updatedPortfolio.cryptocurrencies,
        portfolioID : results.updatedPortfolio.id,
        portfolioWorth : results.updatedPortfolio.portfolioWorth,
        title : results.updatedPortfolio.title,
        portfolioHTML : results.content
    };
    updatePortfolioInfo(portfolioData);
} );

var state = {
    buyDataTable : null,
    sellDataTable : null,
    updateModel : new Update(getPortfolioId()),
};

function initializeGoldenLayout() {
    const buyCryptoTableHTML = getBuyCryptoTable();
    const sellCryptoTableHTML = getSellCryptoTable();
    const portfolioInfo = getPortfolioInfo();

    var config = {
        content: [{
            type: 'row',
            content: [
                {
                    type:'component',
                    componentName: 'Buy',
                    componentState: { text: buyCryptoTableHTML}
                },
                {
                    type:'component',
                    componentName: 'Sell',
                    componentState: { text: portfolioInfo + sellCryptoTableHTML }
                }
            ]
        }]
    };
    var myLayout = new GoldenLayout( config );

    myLayout.registerComponent( 'Buy', function( container, componentState ){
        container.getElement().html(componentState.text);
    });
    myLayout.registerComponent( 'Sell', function( container, componentState ){
        container.getElement().html(componentState.text);
    });

    myLayout.init();
}

function initializeBuyCryptoDataTable() {
    state.buyDataTable = $(`#${IdNames.buyCryptoTable}`).DataTable( {
        "ajax" : {
            "url": api.getBuyCryptoData,
            "type": "POST",
            "dataSrc": ""
        },
        "columns" : [
            {
                className:      'details-control',
                orderable:      false,
                data:           null,
                defaultContent: '<button class="btn btn-primary btn-sm">Buy</button>'
            },
            { "data": "id" },
            { "data": "name" },
            { "data": "abbreviation" },
            { "data": "worth_in_USD" }
        ],
        "createdRow": function(row, data) {
            $(row).data('id', data.id);
        }
    });

    initializeDetailsControlEventListener(state.buyDataTable, IdNames.buyCryptoTable);
}

async function initializeSellCryptoDataTable() {
    const initialPortfolio = await state.updateModel.getPortfolio();

    state.sellDataTable = $(`#${IdNames.sellCryptoTable}`).DataTable({
        "data": initialPortfolio.cryptocurrencies,
        "columns" : [
            {
                className:      'details-control',
                orderable:      false,
                data:           null,
                defaultContent: '<button class="btn btn-success btn-sm">Sell</button>'
            },
            { "data": "id" },
            { "data": "name" },
            { "data": "abbreviation" },
            { "data": "worthInUSD" },
            { "data": "quantity" },
        ],
        "createdRow": function(row, data) {
            $(row).data('id', data.id);
        }
    });

    initializeDetailsControlEventListener(state.sellDataTable, IdNames.sellCryptoTable);
}

function initializeDetailsControlEventListener(dataTable, tableId) {
    $(`#${tableId} tbody`).on('click', 'td.details-control', function () {
        const cryptoId = $(this.parentElement).data('id');
        renderChildRow(this, dataTable, tableId, cryptoId);
        initializeSubmitTransactionEventListener();
    } );
}

function initializeSubmitTransactionEventListener() {
    $('.transaction-wrapper').on('click', '.submit-transaction-btn', async function() {
        const type = getTransactionType(this);
        const transactionAmount = getTransactionAmount(this);
        const cryptocurrencyId = getCryptocurrencyId(this);

        const results = await state.updateModel.saveTransaction(type, transactionAmount, cryptocurrencyId);
        const portfolioData = {
            totalUSDAmount : results.updatedPortfolio.USDAmount,
            cryptoWorthInUSD : results.updatedPortfolio.cryptoWorthInUSD,
            cryptocurrencies : results.updatedPortfolio.cryptocurrencies,
            portfolioID : results.updatedPortfolio.id,
            portfolioWorth : results.updatedPortfolio.portfolioWorth,
            title : results.updatedPortfolio.title,
            portfolioHTML : results.content
        };
        repopulateSellCryptoTable(portfolioData);
        updatePortfolioInfo(portfolioData);
    });
}

function initializeWebsocketConn() {
    var conn = new ab.Session('ws://localhost:8079',
        function() {
            conn.subscribe('cryptoData', async function (topic, cryptoData) {
                // render cryptocurrencies to buy
                // renderBuyCryptocurrencies(cryptoData);
                repopulateBuyCryptoTable(cryptoData, state.buyDataTable);

                // get updated portfolio
                const results = await state.updateModel.updatePortfolio();
                const portfolioData = {
                    totalUSDAmount : results.updatedPortfolio.USDAmount,
                    cryptoWorthInUSD : results.updatedPortfolio.cryptoWorthInUSD,
                    cryptocurrencies : results.updatedPortfolio.cryptocurrencies,
                    portfolioID : results.updatedPortfolio.id,
                    portfolioWorth : results.updatedPortfolio.portfolioWorth,
                    title : results.updatedPortfolio.title,
                    portfolioHTML : results.content
                };
                // render updated portfolio
                updatePortfolio(portfolioData);
                updatePortfolioInfo(portfolioData);
            });
        },
        function() {
            console.warn('WebSocket connection closed');
        },
        {'skipSubprotocolCheck': true}
    );
}
