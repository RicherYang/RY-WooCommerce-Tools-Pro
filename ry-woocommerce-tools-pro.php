<?php
/**
 * Plugin Name: RY Tools (Pro) for WooCommerce
 * Plugin URI: https://ry-plugin.com/ry-woocommerce-tools-pro/
 * Description: WooCommerce paymet and shipping tools
 * Version: 3.4.0
 * Requires at least: 6.3
 * Requires PHP: 8.0
 * Requires Plugins: ry-woocommerce-tools
 * Author: Richer Yang
 * Author URI: https://richer.tw/
 * License: GPLv3
 * License URI: https://www.gnu.org/licenses/gpl-3.0.txt
 * Update URI: https://ry-plugin.com/ry-woocommerce-tools-pro
 *
 * Text Domain: ry-woocommerce-tools-pro
 * Domain Path: /languages
 *
 * WC requires at least: 8
 */

function_exists('plugin_dir_url') or exit('No direct script access allowed');

define('RY_WTP_VERSION', '3.4.0');
define('RY_WTP_PLUGIN_URL', plugin_dir_url(__FILE__));
define('RY_WTP_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('RY_WTP_PLUGIN_BASENAME', plugin_basename(__FILE__));
define('RY_WTP_PLUGIN_LANGUAGES_DIR', plugin_dir_path(__FILE__) . '/languages');

require_once RY_WTP_PLUGIN_DIR . 'includes/main.php';

register_activation_hook(__FILE__, ['RY_WTP', 'plugin_activation']);
register_deactivation_hook(__FILE__, ['RY_WTP', 'plugin_deactivation']);

function RY_WTP(): RY_WTP
{
    return RY_WTP::instance();
}

RY_WTP();
