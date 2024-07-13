<?php

final class RY_WTP_WC_Admin_Shipping
{
    protected static $_instance = null;

    public static function instance(): RY_WTP_WC_Admin_Shipping
    {
        if (null === self::$_instance) {
            self::$_instance = new self();
            self::$_instance->do_init();
        }

        return self::$_instance;
    }

    protected function do_init(): void
    {
        add_action('admin_enqueue_scripts', [$this, 'add_scripts']);
        add_action('ry_shipping_info-action', [$this, 'add_shipping_info_action'], 10, 2);

        if ('yes' == RY_WT::get_option('enabled_ecpay_shipping', 'no')) {
            add_action('woocommerce_product_options_dimensions', [$this, 'shipping_options'], 99);
            add_action('woocommerce_variation_options_dimensions', [$this, 'variation_shipping_options'], 99, 3);

            add_action('woocommerce_admin_process_product_object', [$this, 'save_shipping_options']);
            add_action('woocommerce_admin_process_variation_object', [$this, 'save_variation_shipping_options'], 20, 2);
        }
    }

    public function add_scripts()
    {
        $asset_info = include RY_WTP_PLUGIN_DIR . 'assets/admin/ry-shipping.asset.php';
        wp_register_script('ry-wtp-admin-shipping', RY_WTP_PLUGIN_URL . 'assets/admin/ry-shipping.js', $asset_info['dependencies'], $asset_info['version'], true);
    }

    public function add_shipping_info_action($order, $type)
    {
        foreach ($order->get_items('shipping') as $item) {
            $shipping_method_ID = $item->get_method_id();
            if (class_exists('RY_WT_WC_ECPay_Shipping') && array_key_exists($shipping_method_ID, RY_WT_WC_ECPay_Shipping::$support_methods)) {
                $support_temp = RY_WT_WC_ECPay_Shipping::$support_methods[$shipping_method_ID]::get_support_temp();
            }
            if (class_exists('RY_WT_WC_NewebPay_Shipping') && array_key_exists($shipping_method_ID, RY_WT_WC_NewebPay_Shipping::$support_methods)) {
                $support_temp = RY_WT_WC_NewebPay_Shipping::$support_methods[$shipping_method_ID]::get_support_temp();
            }
            if (class_exists('RY_WT_WC_SmilePay_Shipping') && array_key_exists($shipping_method_ID, RY_WT_WC_SmilePay_Shipping::$support_methods)) {
                $support_temp = RY_WT_WC_SmilePay_Shipping::$support_methods[$shipping_method_ID]::get_support_temp();
            }
        }

        if(isset($support_temp)) {
            if (in_array('1', $support_temp)) {
                echo '<button type="button" class="button ry-' . esc_attr($type) . '-shipping-info" data-orderid="' . esc_attr($order->get_id()) . '" data-temp="1">' . esc_html__('Get shipping no (normal temperature)', 'ry-woocommerce-tools-pro') . '</button>';
            }

            if (in_array('2', $support_temp)) {
                echo '<button type="button" class="button ry-' . esc_attr($type) . '-shipping-info" data-orderid="' . esc_attr($order->get_id()) . '" data-temp="2">' . esc_html__('Get shipping no (refrigerated)', 'ry-woocommerce-tools-pro') . '</button>';
            }

            if (in_array('3', $support_temp)) {
                echo '<button type="button" class="button ry-' . esc_attr($type) . '-shipping-info" data-orderid="' . esc_attr($order->get_id()) . '" data-temp="3">' . esc_html__('Get shipping no (frozen)', 'ry-woocommerce-tools-pro') . '</button>';
            }
        }
    }

    public function shipping_options()
    {
        global $product_object;

        include RY_WTP_PLUGIN_DIR . 'woocommerce/admin/view/product-shipping-option.php';
    }

    public function variation_shipping_options($loop, $variation_data, $variation)
    {
        $variation_object = wc_get_product($variation);
        $product_object = wc_get_product($variation_object->get_parent_id());

        include RY_WTP_PLUGIN_DIR . 'woocommerce/admin/view/product-variation-shipping-option.php';
    }

    public function save_shipping_options($product)
    {
        $product->update_meta_data('_ry_shipping_amount', wc_clean(wp_unslash($_POST['ry_shipping_amount'] ?? '')));
        $temp = wp_unslash($_POST['ry_shipping_temp']);
        if(!in_array($temp, ['1', '2', '3'])) {
            $temp = '1';
        }
        $product->update_meta_data('_ry_shipping_temp', $temp);
    }

    public function save_variation_shipping_options($variation, $i)
    {
        $variation->update_meta_data('_ry_shipping_amount', wc_clean(wp_unslash($_POST['variable_ry_shipping_amount'][$i] ?? '')));
        $temp = wp_unslash($_POST['variable_ry_shipping_temp'][$i]);
        if(!in_array($temp, ['0', '1', '2', '3'])) {
            $temp = '0';
        }
        $variation->update_meta_data('_ry_shipping_temp', $temp);
    }
}
