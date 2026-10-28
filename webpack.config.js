const defaultConfig = require( './node_modules/@wordpress/scripts/config/webpack.config.js' );
const path = require('path')
const { CycloneDxWebpackPlugin } = require('@cyclonedx/webpack-plugin');

const config = {
    ...defaultConfig,
    entry: {
        'modifications': path.resolve(__dirname, './src/modifications/modifications.tsx'),
        'settings': path.resolve(__dirname, './src/settings/settings.tsx'),
    },
    output: {
        path: path.resolve(__dirname, './public/dist/.'),
    },
}

/**
 * Production builds also write a CycloneDX SBOM of what webpack bundled
 * (public/dist/cyclonedx/bom.json). The release workflows of palasthotel/github-workflows
 * collect it for Dependency-Track and keep it out of the plugin payload.
 */
function withSbom(config) {
    if (config.mode !== 'production') {
        return config;
    }
    return {
        ...config,
        plugins: [
            ...config.plugins,
            new CycloneDxWebpackPlugin({
                specVersion: '1.6',
                outputLocation: './cyclonedx',
                includeWellknown: false,
                reproducibleResults: true,
            }),
        ],
    };
}

module.exports = Array.isArray(config) ? config.map(withSbom) : withSbom(config)

// https://github.com/webpack-contrib/webpack-bundle-analyzer check size
