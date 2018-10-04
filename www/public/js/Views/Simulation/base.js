const IdNames = {
    submitTransaction : 'submit-transaction',
    usdInput : 'usd-transaction',
    cryptoInput : 'crypto-transaction',
    buyButton : 'buy-crypto',
    sellButton: 'sell-crypto',
    buyWrapper: 'buy-wrapper',
    buyCryptoTable: 'buy-crypto-table'
};

const classNames = {
    popoverWrapper : 'popover-wrapper',
    portfolioTitle: 'portfolio-title',
    portfolioID : 'portfolio-id',
    portfolioUSDAmount: 'usd-amount',
    cryptoWorthInUSD: 'crypto-worth-in-usd',
    portfolioWorth: 'portfolio-worth',
    sellWrapper: 'sell-wrapper',
};

const elements = {
    buyWrapper : document.querySelector('#buy-wrapper'),
    buyButton : document.querySelectorAll('#buy-crypto'),
    submitTransaction : document.querySelector('#submit-transaction'),
    popoverWrapper : document.querySelector('.popover-wrapper'),
    portfolioTitle: document.querySelector('.portfolio-title'),
    portfolioID: document.querySelector('.portfolio-id'),
    portfolioUSDAmount: document.querySelector('.usd-amount'),
    cryptoWorthInUSD : document.querySelector('.crypto-worth-in-usd'),
    portfolioWorth : document.querySelector('.portfolio-worth'),
    sellWrapper : document.querySelector('.sell-wrapper'),
    buyCryptoTable : document.querySelector(`#${IdNames.buyCryptoTable}`)
};

const api = {
    getBuyCryptoData : '/api/v1/simulation/getBuyCryptoData'
};
