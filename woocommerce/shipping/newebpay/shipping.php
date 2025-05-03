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

        add_filter('woocommerce_checkout_fields', [$this, 'hide_billing_info'], 9999);

        if (is_admin()) {
            include_once RY_WTP_PLUGIN_DIR . 'woocommerce/shipping/newebpay/includes/admin.php';
            RY_WTP_NewebPay_Shipping_Admin::instance();
        } else {
            add_action('woocommerce_review_order_after_shipping', [$this, 'shipping_choose_cvs']);
        }
    }

    public function hide_billing_info($fields)
    {
        if (is_checkout()) {
            $chosen_method = WC()->session->get('chosen_shipping_methods', []);
            $is_support = false;
            if (count($chosen_method)) {
                foreach (RY_WT_WC_NewebPay_Shipping::$support_methods as $method => $method_class) {
                    if (str_starts_with($chosen_method[0], $method)) {
                        $is_support = true;
                    }
                }
            }

            if ($is_support) {
                if ('yes' == RY_WTP::get_option('ecpay_cvs_billing_address', 'no')) {
                    if (str_contains($chosen_method[0], '_cvs')) {
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
                $shipping_method = wp_unslash($_POST['shipping_method'] ?? []); // phpcs:ignore WordPress.Security.NonceVerification.Missing , WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
                foreach ($shipping_method as $method) {
                    $method_ID = strstr($method, ':', true);
                    if (isset(RY_WT_WC_NewebPay_Shipping::$support_methods[$method_ID])) {
                        if (str_contains($method_ID, '_cvs')) {
                            $used_cvs = true;
                        }
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

    public function shipping_choose_cvs()
    {
        wp_enqueue_script('ry-wtp-shipping');
    }
}
