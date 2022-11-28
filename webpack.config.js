const defaultConfig = require( './node_modules/@wordpress/scripts/config/webpack.config.js' );
const path = require('path')

module.exports = {
    ...defaultConfig,
    entry: {
        'modifications': path.resolve(__dirname, './src/modifications/modifications.tsx'),
        'settings': path.resolve(__dirname, './src/settings/settings.jsx'),
    },
    output: {
        path: path.resolve(__dirname, './public/dist/.'),
    },
}

// https://github.com/webpack-contrib/webpack-bundle-analyzer check size
