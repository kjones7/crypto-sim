const merge = require('webpack-merge')

module.exports = merge(require('./config.base.js'), {
    mode: 'development',
    watch: true,
    watchOptions: {
        poll: true
    },
    // Loaders
    module: {
        rules : [
            // JavaScript Files
            {
                test: /\.js$/,
                exclude: /node_modules/,
                use: {
                    loader: 'babel-loader',
                    options: {
                        // See https://babeljs.io/docs/en/options for possible options
                        presets: ['@babel/preset-env']
                    }
                }
            },
            // CSS Files
            {
                test: /\.css$/,
                use: ['style-loader', 'css-loader']
            }
        ]
    },

    // All webpack configuration for development environment will go here
});