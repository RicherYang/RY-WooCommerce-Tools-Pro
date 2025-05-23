<?php

final class RY_WTP_SmilePay_Shipping
{
    protected static $_instance = null;

    public static function instance(): RY_WTP_SmilePay_Shipping
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

        include_once RY_WTP_PLUGIN_DIR . 'woocommerce/shipping/smilepay/includes/cron.php';
        RY_WTP_SmilePay_Shipping_Cron::add_action();

        include_once RY_WTP_PLUGIN_DIR . 'woocommerce/shipping/smilepay/shipping-cvs-711.php';
        include_once RY_WTP_PLUGIN_DIR . 'woocommerce/shipping/smilepay/shipping-cvs-fami.php';
        include_once RY_WTP_PLUGIN_DIR . 'woocommerce/shipping/smilepay/shipping-home-tcat.php';

        RY_WT_WC_SmilePay_Shipping::$support_methods['ry_smilepay_shipping_cvs_711'] = 'RY_SmilePay_Shipping_CVS_711_Pro';
        RY_WT_WC_SmilePay_Shipping::$support_methods['ry_smilepay_shipping_cvs_fami'] = 'RY_SmilePay_Shipping_CVS_Fami_Pro';
        RY_WT_WC_SmilePay_Shipping::$support_methods['ry_smilepay_shipping_home_tcat'] = 'RY_SmilePay_Shipping_Home_Tcat_Pro';

        RY_WTP_WC_Shipping::instance();

        add_filter('woocommerce_checkout_fields', [$this, 'hide_billing_info'], 9999);

        if ('yes' === RY_WT::get_option('smilepay_shipping_auto_order_status', 'yes')) {
            add_action('ry_smilepay_shipping_response_status_1', [$this, 'shipping_transporting'], 10, 2);
        }

        if ('yes' === RY_WT::get_option('smilepay_shipping_auto_get_no', 'yes')) {
            if ('yes' === RY_WTP::get_option('smilepay_shipping_auto_with_scheduler', 'no')) {
                remove_action('woocommerce_order_status_processing', [RY_WT_WC_SmilePay_Shipping::instance(), 'get_code'], 10, 2);
                add_action('woocommerce_order_status_processing', [$this, 'get_code'], 10, 2);
            }
        }

        if (is_admin()) {
            include_once RY_WTP_PLUGIN_DIR . 'woocommerce/shipping/smilepay/includes/admin.php';
            RY_WTP_SmilePay_Shipping_Admin::instance();
        } else {
            add_action('woocommerce_review_order_after_shipping', [$this, 'shipping_choose_cvs']);
            add_action('woocommerce_after_checkout_validation', [$this, 'check_phone'], 10, 2);
            add_action('woocommerce_view_order', [$this, 'shipping_info']);
        }
    }

    public function hide_billing_info($fields)
    {
        if (is_checkout()) {
            $chosen_method = WC()->session->get('chosen_shipping_methods', []);
            $is_support = false;
            if (count($chosen_method)) {
                foreach (RY_WT_WC_SmilePay_Shipping::$support_methods as $method => $method_class) {
                    if (str_starts_with($chosen_method[0], $method)) {
                        $is_support = true;
                    }
                }
            }

            if ($is_support) {
                if ('yes' == RY_WTP::get_option('smilepay_cvs_billing_address', 'no')) {
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
            if (RY_WTP::get_option('smilepay_cvs_billing_address', 'no') == 'yes') {
                $used_cvs = false;
                $shipping_method = wp_unslash($_POST['shipping_method'] ?? []); // phpcs:ignore WordPress.Security.NonceVerification.Missing , WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
                foreach ($shipping_method as $method) {
                    $method_ID = strstr($method, ':', true);
                    if (isset(RY_WT_WC_SmilePay_Shipping::$support_methods[$method_ID])) {
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

    public function shipping_transporting($ipn_info, $order)
    {
        $order->update_status('ry-transporting');
    }

    public function get_code($order_ID, $order)
    {
        foreach ($order->get_items('shipping') as $shipping_item) {
            $shipping_method = RY_WT_WC_SmilePay_Shipping::instance()->get_order_support_shipping($shipping_item);
            if ($shipping_method) {
                $shipping_list = $order->get_meta('_smilepay_shipping_info', true);
                if (!is_array($shipping_list)) {
                    $shipping_list = [];
                }
                if (0 === count($shipping_list)) {
                    if (str_contains($shipping_method, '_home')) {
                        WC()->queue()->schedule_single(time() + 10, 'ry_wtp_get_smilepay_home_code', [$order_ID], '');
                    }
                } else {
                    $list = array_filter(array_column($shipping_list, 'PaymentNo'));
                    if (0 === count($list)) {
                        foreach ($shipping_list as $smse_ID => $info) {
                            WC()->queue()->schedule_single(time() + 10, 'ry_wtp_get_smilepay_cvs_code', [$order_ID, $smse_ID], '');
                        }
                    }
                }
                break;
            }
        }
    }

    public function shipping_choose_cvs()
    {
        wp_enqueue_script('ry-wtp-shipping');
    }

    public function check_phone($data, $errors)
    {
        if (WC()->cart && WC()->cart->needs_shipping()) {
            $chosen_shipping = wc_get_chosen_shipping_method_ids();
            $chosen_shipping = array_intersect($chosen_shipping, array_keys(RY_WT_WC_SmilePay_Shipping::$support_methods));

            if (count($chosen_shipping)) {
                if ((!$data['ship_to_different_address'] || !WC()->cart->needs_shipping_address())) {
                    $phone = $data['billing_phone'];
                } else {
                    $phone = $data['shipping_phone'];
                }
                $phone = str_replace(['-', ' '], ' ', $phone);
                if (!preg_match('/^09[0-9]{8}$/', $phone)) {
                    $errors->add('validation', __('Invalid cellphone number', 'ry-woocommerce-tools-pro'));
                }
            }
        }
    }

    public function shipping_info($order_ID)
    {
        if (!$order = wc_get_order($order_ID)) {
            return;
        }
        $shipping_info_list = $order->get_meta('_smilepay_shipping_info', true);
        if (!is_array($shipping_info_list)) {
            $shipping_info_list = [];
        }

        $args = [
            'order' => $order,
            'shipping_info_list' => $shipping_info_list,
        ];
        wc_get_template('order/order-smilepay-shipping-info.php', $args, '', RY_WTP_PLUGIN_DIR . 'templates/');
    }
}
