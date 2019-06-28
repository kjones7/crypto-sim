const path = require('path');
const { SRC, DIST, ASSETS } = require('./paths');
require("babel-register");
require("babel-polyfill");

module.exports = {
    entry: {
        simulation: ['babel-polyfill', path.resolve(SRC, 'js', 'Controllers', 'Simulation', 'SimulationController.js')],
        createPortfolio: ['babel-polyfill', path.resolve(SRC, 'js', 'Controllers', 'CreatePortfolio', 'CreatePortfolioController.js')],
        dashboard: ['babel-polyfill', path.resolve(SRC, 'js', 'Controllers', 'Dashboard', 'DashboardController.js')],
    },
    output: {
        // Put all the bundled stuff in your dist folder
        path: DIST,
        // [name] means use entry name, allows multiple entries to be output to different files
        filename: '[name].js',
        // The output path as seen from the domain we're visiting in the browser
        publicPath: ASSETS
    },
};