const path = require('path');
const { SRC, DIST, ASSETS } = require('./paths');
require("babel-register");
require("babel-polyfill");

module.exports = {
    entry: {
        simulation: ['babel-polyfill', path.resolve(SRC, 'js', 'Controllers', 'Simulation', 'SimulationController.js')]
    },
    output: {
        // Put all the bundled stuff in your dist folder
        path: DIST,
        filename: '[name].js',
        // The output path as seen from the domain we're visiting in the browser
        publicPath: ASSETS
    },
};