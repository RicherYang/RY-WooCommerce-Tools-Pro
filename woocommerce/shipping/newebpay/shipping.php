<?php

defined('ABSPATH') or exit;

use RY\WooCommerce\Pro\Main;

final class RY_WTP_NewebPay_Shipping
{
    private static ?self $_instance = null;

    public static function instance(): RY_WTP_NewebPay_Shipping
    {
        if (null === self::$_instance) {
            self::$_instance = new self();
            self::$_instance->do_init();
        }

        return self::$_instance;
    }

    protected function do_init(): void
    {
        include_once RY_WTP_PLUGIN_DIR . 'woocommerce/shipping.php';

        include_once RY_WTP_PLUGIN_DIR . 'woocommerce/shipping/newebpay/shipping-cvs.php';

        RY_WT_WC_NewebPay_Shipping::$support_methods['ry_newebpay_shipping_cvs'] = 'RY_NewebPay_Shipping_CVS_Pro';

        RY_WTP_WC_Shipping::instance();

        if (is_admin()) {
        } else {
            add_action('woocommerce_review_order_after_shipping', [$this, 'shipping_choose_cvs']);
        }
    }

    public function shipping_choose_cvs()
    {
        wp_enqueue_script('ry-wtp-shipping');
    }
}
