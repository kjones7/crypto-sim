export const IdNames = {
    submitTransaction : 'submit-transaction',
    usdInput : 'usd-transaction',
    cryptoInput : 'crypto-transaction',
    buyButton : 'buy-crypto',
    sellButton: 'sell-crypto',
    buyWrapper: 'buy-wrapper',
    buyCryptoTable: 'buy-crypto-table',
    sellCryptoTable: 'sell-crypto-table',
};

export const classNames = {
    popoverWrapper : 'popover-wrapper',
    portfolioTitle: 'portfolio-title',
    portfolioID : 'portfolio-id',
    portfolioUSDLeft: 'portfolio-usd-left',
    cryptoWorthInUSD: 'portfolio-crypto-worth',
    portfolioWorth: 'portfolio-worth',
    sellWrapper: 'sell-wrapper',
    dataTables: 'data-table',
    transactionInput: 'usd-transaction-input',
};

export const elements = {
    buyWrapper : document.querySelector('#buy-wrapper'),
    buyButton : document.querySelectorAll('#buy-crypto'),
    submitTransaction : document.querySelector('#submit-transaction'),
    popoverWrapper : document.querySelector('.popover-wrapper'),
    portfolioTitle: document.querySelector('.portfolio-title'),
    portfolioID: document.querySelector(`.${classNames.portfolioID}`),
    portfolioUSDAmount: document.querySelector('.usd-amount'),
    sellWrapper : document.querySelector('.sell-wrapper'),
    buyCryptoTable : document.querySelector(`#${IdNames.buyCryptoTable}`),
    sellCryptoTable : document.querySelector(`#${IdNames.sellCryptoTable}`),
};

export const api = {
    getBuyCryptoData : '/api/v1/simulation/getBuyCryptoData',
    getPortfolio : '/api/v1/simulation/getPortfolio',
    saveTransaction: '/api/v1/simulation/saveTransaction'
};
