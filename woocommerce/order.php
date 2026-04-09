<?php

final class RY_WTP_WC_Order
{
    protected static $_instance = null;

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
        if ('yes' === RY_WTP::get_option('virtual_skip_processing', 'no')) {
            add_filter('woocommerce_order_item_needs_processing', [__CLASS__, 'virtual_skip_processing'], 10, 2);
        }
    }

    public static function virtual_skip_processing($need_processing, $product)
    {
        if ($need_processing === true) {
            return !$product->is_virtual();
        }

        return $need_processing;
    }
}

RY_WTP_WC_Order::instance();
