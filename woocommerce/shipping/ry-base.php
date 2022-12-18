<?php
final class RY_WTP_Shipping
{
    protected static $_instance = null;

    public static function instance()
    {
        if (self::$_instance === null) {
            self::$_instance = new self();
            self::$_instance->do_init();
        }

        return self::$_instance;
    }

    protected function do_init()
    {
        if (is_admin()) {
            include_once RY_WTP_PLUGIN_DIR . 'woocommerce/admin/shipping-base.php';
        } else {
            wp_register_script('ry-pro-shipping', RY_WTP_PLUGIN_URL . 'style/js/ry_shipping.js', ['jquery'], RY_WTP_VERSION, true);
        }
    }
}

RY_WTP_Shipping::instance();
