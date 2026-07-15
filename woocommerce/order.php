<?php

defined('ABSPATH') or exit;

final class RY_WTP_WC_Order
{
    private static ?self $_instance = null;

    public static function instance(): RY_WTP_WC_Order
    {
        if (null === self::$_instance) {
            self::$_instance = new self();
            self::$_instance->do_init();
        }

        return self::$_instance;
    }

    protected function do_init(): void
    {
        if (is_admin()) {
            include_once RY_WTP_PLUGIN_DIR . 'woocommerce/admin/order.php';
            RY_WTP_WC_Admin_Order::instance();
        }

        add_filter('woocommerce_order_item_needs_processing', [__CLASS__, 'virtual_skip_processing'], 10, 2);
    }

    public static function virtual_skip_processing($need_processing, $product)
    {
        if ($need_processing === true) {
            if ('yes' === RY_WTP::get_option('virtual_skip_processing', 'no')) {
                return !$product->is_virtual();
            }
        }

        return $need_processing;
    }
}

RY_WTP_WC_Order::instance();
