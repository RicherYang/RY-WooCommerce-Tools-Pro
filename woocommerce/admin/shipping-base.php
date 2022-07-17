<?php
final class RY_WTP_Admin_Shipping
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
        if ('yes' == RY_WT::get_option('enabled_ecpay_shipping', 'no')) {
            add_action('woocommerce_product_options_shipping', [$this, 'shipping_options']);
            add_action('woocommerce_admin_process_product_object', [$this, 'save_shipping_options']);
        }
    }

    public function shipping_options()
    {
        global $product_object;

        include RY_WTP_PLUGIN_DIR . 'woocommerce/admin/view/product-shipping-option.php';
    }

    public function save_shipping_options($product)
    {
        $product->update_meta_data('_ry_shipping_temp', isset($_POST['_ry_shipping_temp']) ? wc_clean(wp_unslash($_POST['_ry_shipping_temp'])) : '1');
    }
}

RY_WTP_Admin_Shipping::instance();
