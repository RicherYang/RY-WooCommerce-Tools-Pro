<?php

defined('ABSPATH') or exit;

use RY\WooCommerce\Pro\Main;

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

        add_filter('woocommerce_checkout_fields', [$this, 'hide_billing_info'], 9999);
        add_filter('woocommerce_order_item_needs_processing', [__CLASS__, 'virtual_skip_processing'], 10, 2);
    }

    public function hide_billing_info($fields)
    {
        if (!isset($fields['billing'])) {
            return $fields;
        }

        $cvs_hide_fields = ['billing_postcode', 'billing_state', 'billing_city', 'billing_address_1', 'billing_address_2'];
        $csv_hide = Main::get_option('hide_cvs_billing_address', 'no') === 'yes';
        $virtual_hide = Main::get_option('hide_virtual_billing_address', 'no') === 'yes';

        if ($virtual_hide) {
            foreach (WC()->cart->get_cart_contents() as $values) {
                $virtual_hide = $virtual_hide && $values['data']->is_virtual();
            }
        }

        if (is_checkout()) {
            if ($virtual_hide) {
                foreach ($cvs_hide_fields as $key) {
                    if (isset($fields['billing'][$key])) {
                        if (isset($fields['billing'][$key]['class'])) {
                            $fields['billing'][$key]['class'][] = 'ry-hide';
                        } else {
                            $fields['billing'][$key]['class'] = ['ry-hide'];
                        }
                    }
                }
            }
            if ($csv_hide) {
                foreach ($cvs_hide_fields as $key) {
                    if (isset($fields['billing'][$key])) {
                        if (isset($fields['billing'][$key]['class'])) {
                            $fields['billing'][$key]['class'][] = 'ry-cvs-hide';
                        } else {
                            $fields['billing'][$key]['class'] = ['ry-cvs-hide'];
                        }
                    }
                }
            }
        }

        if (did_action('woocommerce_checkout_process')) {
            $change_required = false;
            if ($virtual_hide) {
                $change_required = true;
            }
            if ($csv_hide) {
                $shipping_method = wp_unslash($_POST['shipping_method'] ?? []);
                foreach ($shipping_method as $method) {
                    $method_ID = strstr($method, ':', true);
                    if (isset(RY_WT_WC_ECPay_Shipping::$support_methods[$method_ID])) {
                        if (str_contains($method_ID, '_cvs')) {
                            $change_required = true;
                        }
                    }
                }
            }

            if ($change_required) {
                foreach ($cvs_hide_fields as $key) {
                    if (isset($fields['billing'][$key])) {
                        $fields['billing'][$key]['required'] = false;
                    }
                }
            }
        }

        return $fields;
    }

    public static function virtual_skip_processing($need_processing, $product)
    {
        if ($need_processing === true) {
            if ('yes' === Main::get_option('virtual_skip_processing', 'no')) {
                return !$product->is_virtual();
            }
        }

        return $need_processing;
    }
}

RY_WTP_WC_Order::instance();
