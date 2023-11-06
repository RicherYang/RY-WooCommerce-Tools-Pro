<?php
/**
 * Plugin Name: RY WooCommerce Tools Pro
 * Plugin URI: https://richer.tw/ry-woocommerce-tools-pro/
 * Description: WooCommerce paymet and shipping tools
 * Version: 3.0.1
 * Requires at least: 6.0
 * Requires PHP: 7.4
 * Author: Richer Yang
 * Author URI: https://richer.tw/
 * License: GPLv3
 * License URI: https://www.gnu.org/licenses/gpl-3.0.txt
 *
 * Text Domain: ry-woocommerce-tools-pro
 * Domain Path: /languages
 *
 * WC requires at least: 7
 */

function_exists('plugin_dir_url') or exit('No direct script access allowed');

define('RY_WTP_VERSION', '3.0.1');
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
