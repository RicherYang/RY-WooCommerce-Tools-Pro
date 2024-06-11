<?php

final class RY_WTP_WC_Shipping
{
    protected static $_instance = null;

    public static function instance(): RY_WTP_WC_Shipping
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
            include_once RY_WTP_PLUGIN_DIR . 'woocommerce/admin/shipping.php';
            RY_WTP_WC_Admin_Shipping::instance();
        }
    }
}
