const path = require('path', true);
const glob = require('glob', true);
const CopyWebpackPlugin = require('copy-webpack-plugin', true);
const { CleanWebpackPlugin } = require('clean-webpack-plugin', true);

const defaultConfig = require('@wordpress/scripts/config/webpack.config', true);
const WooCommerceDependencyExtractionWebpackPlugin = require('@woocommerce/dependency-extraction-webpack-plugin', true);
const { fromProjectRoot } = require('@wordpress/scripts/utils/file', true);

const srcPath = fromProjectRoot('assets-src');
const distPath = fromProjectRoot('assets');

function getWebpackEntryPoints() {
    let entryPoints = {};

    glob.sync(
        path.join(srcPath, 'blocks', '**', '[^_]*.{js,tsx}')
    ).forEach((file) => {
        const relative = path.relative(srcPath, file);
        const entryName = relative.substring(0, relative.lastIndexOf('.')) || relative;

        entryPoints[entryName] = '/' + file;
    });

    entryPoints['admin/ry-shipping'] = path.join(srcPath, 'admin/ry-shipping.js');

    return entryPoints;
}

function getCopyPatterns() {
    let patterns = [];

    glob.sync(
        path.join(srcPath, 'blocks', '**', 'block.json')
    ).forEach((file) => {
        patterns.push({
            from: file,
            to: path.join('.', path.relative(srcPath, file))
        });
    });

    return patterns;
}

module.exports = {
    ...defaultConfig,
    entry: getWebpackEntryPoints(),
    output: {
        ...defaultConfig.output,
        path: distPath,
        filename: '[name].js',
    },
    plugins: [
        ...defaultConfig.plugins.filter((plugin) => plugin.constructor.name !== 'CleanWebpackPlugin' && plugin.constructor.name !== 'DependencyExtractionWebpackPlugin'),
        new CleanWebpackPlugin({
            cleanOnceBeforeBuildPatterns: [
                path.join(distPath)
            ],
            cleanStaleWebpackAssets: false,
        }),
        new WooCommerceDependencyExtractionWebpackPlugin(),
        new CopyWebpackPlugin({
            patterns: getCopyPatterns()
        })
    ]
};
