<?php

final class RY_WTP_NewebPay_Shipping
{
    protected static $_instance = null;

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

        add_filter('woocommerce_shipping_methods', [$this, 'use_pro_method'], 11);
        add_filter('woocommerce_checkout_fields', [$this, 'hide_billing_info'], 9999);

        add_filter('woocommerce_update_order_review_fragments', [$this, 'shipping_choose_cvs_info'], 11);

        if (is_admin()) {
            include_once RY_WTP_PLUGIN_DIR . 'woocommerce/shipping/newebpay/includes/admin.php';
            RY_WTP_NewebPay_Shipping_Admin::instance();
        } else {
            add_action('woocommerce_review_order_after_shipping', [$this, 'shipping_choose_cvs']);
        }
    }

    public function use_pro_method($shipping_methods)
    {
        if (isset($shipping_methods['ry_newebpay_shipping_cvs'])) {
            $shipping_methods['ry_newebpay_shipping_cvs'] = 'RY_NewebPay_Shipping_CVS_Pro';
        }

        return $shipping_methods;
    }

    public function hide_billing_info($fields)
    {
        if (is_checkout()) {
            $chosen_method = isset(WC()->session->chosen_shipping_methods) ? WC()->session->chosen_shipping_methods : [];
            $is_support = false;
            if (count($chosen_method)) {
                foreach (RY_WT_WC_NewebPay_Shipping::$support_methods as $method => $method_class) {
                    if (0 === strpos($chosen_method[0], $method)) {
                        $is_support = true;
                    }
                }
            }

            if ($is_support) {
                if ('yes' == RY_WTP::get_option('ecpay_cvs_billing_address', 'no')) {
                    if (strpos($chosen_method[0], '_cvs')) {
                        $hide_fields = ['billing_country', 'billing_address_1', 'billing_address_2', 'billing_city', 'billing_state', 'billing_postcode'];
                        foreach ($hide_fields as $field_name) {
                            if (isset($fields['billing'][$field_name])) {
                                $fields['billing'][$field_name]['class'][] = 'ry-hide';
                            }
                        }
                    }
                }
            }
        }

        if (did_action('woocommerce_checkout_process')) {
            if (RY_WTP::get_option('newebpay_cvs_billing_address', 'no') == 'yes') {
                $used_cvs = false;
                $shipping_method = isset($_POST['shipping_method']) ? wc_clean($_POST['shipping_method']) : [];
                foreach ($shipping_method as $method) {
                    $method = strstr($method, ':', true);
                    if (array_key_exists($method, RY_WT_WC_NewebPay_Shipping::$support_methods)) {
                        if (strpos($method, '_cvs')) {
                            $used_cvs = true;
                        }
                        break;
                    }
                }

                if ($used_cvs) {
                    $fields['billing']['billing_country']['required'] = false;
                    $fields['billing']['billing_address_1']['required'] = false;
                    $fields['billing']['billing_address_2']['required'] = false;
                    $fields['billing']['billing_city']['required'] = false;
                    $fields['billing']['billing_state']['required'] = false;
                    $fields['billing']['billing_postcode']['required'] = false;
                }
            }
        }

        return $fields;
    }

    public function shipping_choose_cvs_info($fragments)
    {
        if (isset($fragments['newebpay_shipping_info'])) {
            if (RY_WTP::get_option('newebpay_cvs_billing_address', 'no') == 'yes') {
                $chosen_method = isset(WC()->session->chosen_shipping_methods) ? WC()->session->chosen_shipping_methods : [];
                $fragments['hide_billing_address'] = strpos($chosen_method[0], '_cvs');
            }
        }

        return $fragments;
    }

    public function shipping_choose_cvs()
    {
        wp_enqueue_script('ry-wtp-shipping');
    }
}
