<?php

final class RY_WTP
{
    public const OPTION_PREFIX = 'RY_WTP_';

    public const MIN_TOOLS_VERSION = '3.5.0';

    protected static $_instance = null;

    public static function instance(): RY_WTP
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
            include_once RY_WTP_PLUGIN_DIR . 'includes/update.php';
            RY_WTP_Update::update();

            include_once RY_WTP_PLUGIN_DIR . 'includes/admin.php';
            RY_WTP_Admin::instance();
        }

        add_action('ry_woo_tools_loaded', [$this, 'do_woo_init']);
    }

    public function do_woo_init(): void
    {
        include_once RY_WTP_PLUGIN_DIR . 'woocommerce/admin/notes/license-auto-deactivate.php';
        include_once RY_WTP_PLUGIN_DIR . 'includes/license.php';
        include_once RY_WTP_PLUGIN_DIR . 'includes/link-server.php';
        include_once RY_WTP_PLUGIN_DIR . 'includes/updater.php';
        RY_WTP_Updater::instance();

        if (is_admin()) {
            include_once RY_WTP_PLUGIN_DIR . 'woocommerce/admin/admin.php';
            RY_WTP_WC_Admin::instance();
        }

        if (RY_WTP_License::instance()->is_activated()) {
            if (version_compare(RY_WT_VERSION, RY_WTP::MIN_TOOLS_VERSION, '<')) {
                return;
            }
            if (defined('RY_WT::MIN_PRO_TOOLS_VERSION') && version_compare(RY_WTP_VERSION, RY_WT::MIN_PRO_TOOLS_VERSION, '<')) {
                return;
            }

            if (is_admin()) {
                include_once RY_WTP_PLUGIN_DIR . 'woocommerce/admin/admin.php';
                RY_WTP_WC_Admin::instance();
            }

            include_once RY_WTP_PLUGIN_DIR . 'includes/cron.php';
            RY_WTP_Cron::add_action();

            // 綠界金流
            if ('yes' == RY_WT::get_option('enabled_ecpay_gateway', 'no')) {
                include_once RY_WTP_PLUGIN_DIR . 'woocommerce/gateways/ecpay/gateway.php';
                RY_WTP_WC_ECPay_Gateway::instance();
            }
            // 綠界物流
            if ('yes' == RY_WT::get_option('enabled_ecpay_shipping', 'no')) {
                include_once RY_WTP_PLUGIN_DIR . 'woocommerce/shipping/ecpay/shipping.php';
                RY_WTP_ECPay_Shipping::instance();
            }

            // 藍新金流
            if ('yes' == RY_WT::get_option('enabled_newebpay_gateway', 'no')) {
                include_once RY_WTP_PLUGIN_DIR . 'woocommerce/gateways/newebpay/gateway.php';
                RY_WTP_WC_NewebPay_Gateway::instance();
            }
            // 藍新物流
            if ('yes' == RY_WT::get_option('enabled_newebpay_shipping', 'no')) {
                include_once RY_WTP_PLUGIN_DIR . 'woocommerce/shipping/newebpay/shipping.php';
                RY_WTP_NewebPay_Shipping::instance();
            }

            // 速買配金流
            if ('yes' == RY_WT::get_option('enabled_smilepay_gateway', 'no')) {
                include_once RY_WTP_PLUGIN_DIR . 'woocommerce/gateways/smilepay/gateway.php';
                RY_WTP_WC_SmilePay_Gateway::instance();
            }
            // 速買配物流
            if ('yes' == RY_WT::get_option('enabled_smilepay_shipping', 'no')) {
                include_once RY_WTP_PLUGIN_DIR . 'woocommerce/shipping/smilepay/shipping.php';
                RY_WTP_SmilePay_Shipping::instance();
            }
        }
    }

    public static function get_option($option, $default = false)
    {
        return get_option(self::OPTION_PREFIX . $option, $default);
    }

    public static function update_option($option, $value, $autoload = null): bool
    {
        return update_option(self::OPTION_PREFIX . $option, $value, $autoload);
    }

    public static function delete_option($option): bool
    {
        return delete_option(self::OPTION_PREFIX . $option);
    }

    public static function get_transient($transient)
    {
        return get_transient(self::OPTION_PREFIX . $transient);
    }

    public static function set_transient($transient, $value, $expiration = 0): bool
    {
        return set_transient(self::OPTION_PREFIX . $transient, $value, $expiration);
    }

    public static function delete_transient($transient): bool
    {
        return delete_transient(self::OPTION_PREFIX . $transient);
    }

    public static function plugin_activation() {}

    public static function plugin_deactivation()
    {
        wp_unschedule_hook(self::OPTION_PREFIX . 'check_expire');
        wp_unschedule_hook(self::OPTION_PREFIX . 'check_update');
    }
}
