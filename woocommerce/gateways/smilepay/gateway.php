<?php

defined('ABSPATH') or exit;

final class RY_WTP_WC_SmilePay_Gateway extends RY_WTP_Gateway_Model
{
    protected static ?self $_instance = null;

    public static function instance(): RY_WTP_WC_SmilePay_Gateway
    {
        if (null === self::$_instance) {
            self::$_instance = new self();
            self::$_instance->do_init();
        }

        return self::$_instance;
    }

    protected function do_init(): void
    {
        include_once RY_WTP_PLUGIN_DIR . 'woocommerce/gateways/smilepay/gateway-block.php';
        RY_WTP_WC_SmilePay_Gateway_Block::instance();

        if (is_admin()) {
            include_once RY_WTP_PLUGIN_DIR . 'woocommerce/gateways/smilepay/includes/admin.php';
            RY_WTP_WC_SmilePay_Gateway_Admin::instance();
        }

        if ('yes' === RY_WTP::get_option('smilepay_email_payment_info', 'no')) {
            add_action('woocommerce_email_after_order_table', [$this, 'add_payment_info'], 10, 4);
        }
    }
}
