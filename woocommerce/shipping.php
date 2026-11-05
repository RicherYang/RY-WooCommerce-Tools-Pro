<?php

defined('ABSPATH') or exit;

use RY\WooCommerce\Pro\Main;

final class RY_WTP_WC_Shipping
{
    private static ?self $_instance = null;

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

        add_filter('woocommerce_checkout_fields', [$this, 'hide_billing_info'], 9999);

        add_filter('ry_shipping_evaluate_cost_args', [$this, 'add_shipping_cost_args'], 10, 3);
        foreach (WC()->shipping()->get_shipping_methods() as $id => $shipping_method) {
            add_filter('woocommerce_shipping_' . $id . '_is_available', [$this, 'product_skip_shipping'], 11, 3);
        }
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

    public function add_shipping_cost_args($args, $package, $rate)
    {
        $args['offisland'] = $rate['meta_data']['RYCvsInfo']['CVSOutSide'] ?? '';

        return $args;
    }

    public function product_skip_shipping($available, $package, $shipping_method): bool
    {
        if ($available) {
            foreach ($package['contents'] as $values) {
                $skip_shipping = $values['data']->get_meta('_ry_shipping_skip_shipping', true);
                if (empty($skip_shipping) && 'variation' === $values['data']->get_type()) {
                    $parent_product = wc_get_product($values['data']->get_parent_id());
                    $skip_shipping = $parent_product->get_meta('_ry_shipping_skip_shipping', true);
                }

                if (!empty($skip_shipping)) {
                    $skip_shipping = (array) $skip_shipping;
                    if (in_array($shipping_method->id, $skip_shipping, true)) {
                        return false;
                    }
                    if (in_array($shipping_method->id . ':' . $shipping_method->instance_id, $skip_shipping, true)) {
                        return false;
                    }
                }
            }
            return true;
        }

        return false;
    }
}
