const IdNames = {
    submitTransaction : 'submit-transaction',
    usdInput : 'usd-transaction',
    cryptoInput : 'crypto-transaction',
    buyButton : 'buy-crypto',
    sellButton: 'sell-crypto',
    buyWrapper: 'buy-wrapper',
    buyCryptoTable: 'buy-crypto-table',
    sellCryptoTable: 'sell-crypto-table',
};

const classNames = {
    popoverWrapper : 'popover-wrapper',
    portfolioTitle: 'portfolio-title',
    portfolioID : 'portfolio-id',
    portfolioUSDAmount: 'usd-amount',
    cryptoWorthInUSD: 'crypto-worth-in-usd',
    portfolioWorth: 'portfolio-worth',
    sellWrapper: 'sell-wrapper',
    dataTables: 'data-table',
};

const elements = {
    buyWrapper : document.querySelector('#buy-wrapper'),
    buyButton : document.querySelectorAll('#buy-crypto'),
    submitTransaction : document.querySelector('#submit-transaction'),
    popoverWrapper : document.querySelector('.popover-wrapper'),
    portfolioTitle: document.querySelector('.portfolio-title'),
    portfolioID: document.querySelector(`.${classNames.portfolioID}`),
    portfolioUSDAmount: document.querySelector('.usd-amount'),
    cryptoWorthInUSD : document.querySelector('.crypto-worth-in-usd'),
    portfolioWorth : document.querySelector('.portfolio-worth'),
    sellWrapper : document.querySelector('.sell-wrapper'),
    buyCryptoTable : document.querySelector(`#${IdNames.buyCryptoTable}`),
    sellCryptoTable : document.querySelector(`#${IdNames.sellCryptoTable}`),
};

const api = {
    getBuyCryptoData : '/api/v1/simulation/getBuyCryptoData',
    getPortfolio : '/api/v1/simulation/getPortfolio',
};
