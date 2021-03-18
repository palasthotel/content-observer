const defaultConfig = require( './node_modules/@wordpress/scripts/config/webpack.config.js' );
const path = require('path')

module.exports = {
    ...defaultConfig,
    entry: {
        'settings': path.resolve(__dirname, './src/scripts/settings.jsx'),
    },
    output: {
        path: path.resolve(__dirname, './public/js/settings/.'),
    },
}

// https://github.com/webpack-contrib/webpack-bundle-analyzer check size