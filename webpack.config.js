const path = require('path');
const fs = require('fs');
const CopyWebpackPlugin = require('copy-webpack-plugin', true);

const defaultConfig = require('@wordpress/scripts/config/webpack.config', true);
const WooCommerceDependencyExtractionWebpackPlugin = require('@woocommerce/dependency-extraction-webpack-plugin', true);
const { fromProjectRoot } = require('@wordpress/scripts/utils/file', true);

const srcPath = fromProjectRoot('assets-src');
const distPath = fromProjectRoot('assets');

function getFilesInDir(dirPath) {
    if (!fs.existsSync(dirPath)) {
        return [];
    }

    const files = [];

    function walk(currentDir) {
        for (const entry of fs.readdirSync(currentDir, { withFileTypes: true })) {
            const fullPath = path.join(currentDir, entry.name);

            if (entry.isDirectory()) {
                walk(fullPath);
                continue;
            }

            files.push(fullPath);
        }
    }

    walk(dirPath);

    return files;
}

function getWebpackEntryPoints() {
    let entryPoints = {};

    getFilesInDir(path.join(srcPath, 'blocks'))
        .filter((file) => {
            const baseName = path.basename(file);
            return !baseName.startsWith('_') && /\.(js|tsx)$/.test(file);
        })
        .forEach((file) => {
            const relative = path.relative(srcPath, file);
            const entryName = relative.substring(0, relative.lastIndexOf('.')) || relative;

            entryPoints[entryName] = '/' + file;
        });

    entryPoints['admin/order'] = path.join(srcPath, 'admin/order.js');
    entryPoints['admin/setting'] = path.join(srcPath, 'admin/setting.js');

    return entryPoints;
}

function getCopyPatterns() {
    let patterns = [];

    getFilesInDir(path.join(srcPath, 'blocks'))
        .filter((file) => path.basename(file) === 'block.json')
        .forEach((file) => {
            patterns.push({
                from: file,
                to: path.relative(srcPath, file)
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
        ...defaultConfig.plugins.filter((plugin) => plugin.constructor.name !== 'DependencyExtractionWebpackPlugin'),
        new WooCommerceDependencyExtractionWebpackPlugin(),
        new CopyWebpackPlugin({
            patterns: getCopyPatterns()
        })
    ]
};
