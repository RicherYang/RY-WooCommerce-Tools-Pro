<?php

namespace RY\WooCommerce\Pro;

defined('ABSPATH') or exit;

use RY\General\V20260810\AbstractBasic;
use RY\WooCommerce\Main as WT_Main;
use RY\WooCommerce\Pro\Admin\Admin;

final class Main extends AbstractBasic
{
    public const PREFIX = '\RY_WTP_';

    public const PLUGIN_NAME = 'RY Tools (Pro) for WooCommerce';

    public const MIN_TOOLS_VERSION = '2026.9.22';

    private static ?self $_instance = null;

    public Admin $admin;

    public static function instance(): Main
    {
        if (null === self::$_instance) {
            self::$_instance = new self();
            self::$_instance->do_init();
        }

        return self::$_instance;
    }

    protected function do_init(): void
    {
        load_plugin_textdomain('ry-woocommerce-tools-pro', false, plugin_basename(dirname(__DIR__)) . '/languages');

        if (is_admin()) {
            Update::update();
        }

        add_action('ry_woo_tools_loaded', [$this, 'do_woo_init']);
    }

    public function do_woo_init(): void
    {
        include_once \RY_WTP_PLUGIN_DIR . 'includes/functions.php';
        Updater::instance();
        Cron::add_action();

        if (is_admin()) {
            $this->admin = Admin::instance();

            if (version_compare(RY_WT_VERSION, self::MIN_TOOLS_VERSION, '<')) {
                return;
            }
            if (defined(WT_Main::class . '::MIN_PRO_TOOLS_VERSION') && version_compare(\RY_WTP_VERSION, WT_Main::MIN_PRO_TOOLS_VERSION, '<')) {
                return;
            }
            include_once \RY_WTP_PLUGIN_DIR . 'woocommerce/admin/admin.php';
            \RY_WTP_WC_Admin::instance();
        }

        if (License::instance()->is_activated()) {
            if (version_compare(RY_WT_VERSION, self::MIN_TOOLS_VERSION, '<')) {
                return;
            }
            if (defined(WT_Main::class . '::MIN_PRO_TOOLS_VERSION') && version_compare(\RY_WTP_VERSION, WT_Main::MIN_PRO_TOOLS_VERSION, '<')) {
                return;
            }

            if (is_admin()) {
                include_once \RY_WTP_PLUGIN_DIR . 'woocommerce/admin/admin.php';
                \RY_WTP_WC_Admin::instance();
            }

            include_once \RY_WTP_PLUGIN_DIR . 'woocommerce/abstracts/abstract-gateway-model.php';

            include_once \RY_WTP_PLUGIN_DIR . 'woocommerce/account.php';
            include_once \RY_WTP_PLUGIN_DIR . 'woocommerce/order.php';

            if ('yes' === WT_Main::get_option('enabled_ecpay_gateway', 'no')) {
                include_once \RY_WTP_PLUGIN_DIR . 'woocommerce/gateways/ecpay/gateway.php';
                \RY_WTP_WC_ECPay_Gateway::instance();
            }
            if ('yes' === WT_Main::get_option('enabled_ecpay_shipping', 'no')) {
                include_once \RY_WTP_PLUGIN_DIR . 'woocommerce/shipping/ecpay/shipping.php';
                \RY_WTP_ECPay_Shipping::instance();
            }

            if ('yes' === WT_Main::get_option('enabled_newebpay_gateway', 'no')) {
                include_once \RY_WTP_PLUGIN_DIR . 'woocommerce/gateways/newebpay/gateway.php';
                \RY_WTP_WC_NewebPay_Gateway::instance();
            }
            if ('yes' === WT_Main::get_option('enabled_newebpay_shipping', 'no')) {
                include_once \RY_WTP_PLUGIN_DIR . 'woocommerce/shipping/newebpay/shipping.php';
                \RY_WTP_NewebPay_Shipping::instance();
            }

            if ('yes' === WT_Main::get_option('enabled_payuni_gateway', 'no')) {
                include_once \RY_WTP_PLUGIN_DIR . 'woocommerce/gateways/payuni/gateway.php';
                \RY_WTP_WC_PAYUNi_Gateway::instance();
            }

            if ('yes' === WT_Main::get_option('enabled_smilepay_gateway', 'no')) {
                include_once \RY_WTP_PLUGIN_DIR . 'woocommerce/gateways/smilepay/gateway.php';
                \RY_WTP_WC_SmilePay_Gateway::instance();
            }
            if ('yes' === WT_Main::get_option('enabled_smilepay_shipping', 'no')) {
                include_once \RY_WTP_PLUGIN_DIR . 'woocommerce/shipping/smilepay/shipping.php';
                \RY_WTP_SmilePay_Shipping::instance();
            }
        }
    }

    public static function usage_tracking(): void
    {
        if (get_option('RY_General_tracking', 'yes') !== 'yes') {
            return;
        }

        LinkServer::instance()->send_tracking();
    }

    public static function plugin_activation(): void {}

    public static function plugin_deactivation(): void
    {
        wp_unschedule_hook(self::get_prefix_name('check_expire'));
    }
}
