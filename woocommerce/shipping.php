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
        } else {
            add_action('wp_enqueue_scripts', [$this, 'load_scripts']);
        }
    }

    public function load_scripts()
    {
        wp_register_script('ry-wtp-shipping', RY_WTP_PLUGIN_URL . 'assets/js/ry_shipping.js', ['jquery'], RY_WTP_VERSION, true);
    }
}
