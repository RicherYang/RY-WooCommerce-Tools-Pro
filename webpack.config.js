const path = require('path');
const glob = require('glob');
const CopyWebpackPlugin = require('copy-webpack-plugin');

const { fromProjectRoot } = require('@wordpress/scripts/utils/file');
const defaultConfig = require('@wordpress/scripts/config/webpack.config');
const WooCommerceDependencyExtractionWebpackPlugin = require('@woocommerce/dependency-extraction-webpack-plugin');

const srcPath = fromProjectRoot('assets-src/blocks-js/');

function getWebpackEntryPoints() {
    let entryPoints = {};

    glob.sync(
        path.join(srcPath, '**', '[^_]*.{js,tsx}')
    ).forEach((file) => {
        const relative = path.relative(srcPath, file);
        const entryName = relative.substring(0, relative.lastIndexOf('.')) || relative;

        entryPoints[entryName] = '/' + file;
    });

    return entryPoints;
}

function getCopyPatterns() {
    let patterns = [];
    patterns.push({ from: 'assets-src/js', to: './js' });

    glob.sync(
        path.join(srcPath, '**', 'block.json')
    ).forEach((file) => {
        patterns.push({ from: file, to: path.join('js/blocks', path.relative(srcPath, file)) });
    });

    return patterns;
}

module.exports = {
    ...defaultConfig,
    entry: getWebpackEntryPoints(),
    output: {
        path: fromProjectRoot('assets'),
        filename: 'js/blocks/[name].js',
    },
    plugins: [
        ...defaultConfig.plugins.filter(
            function (plugin) {
                return plugin.constructor.name !== 'DependencyExtractionWebpackPlugin';
            }
        ),
        new WooCommerceDependencyExtractionWebpackPlugin(),
        new CopyWebpackPlugin({
            patterns: getCopyPatterns()
        })
    ]
};
