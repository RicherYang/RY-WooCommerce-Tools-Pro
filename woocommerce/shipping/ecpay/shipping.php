<?php

final class RY_WTP_ECPay_Shipping
{
    protected static $_instance = null;

    public static function instance(): RY_WTP_ECPay_Shipping
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

        include_once RY_WTP_PLUGIN_DIR . 'woocommerce/shipping/ecpay/shipping-cvs-711.php';
        include_once RY_WTP_PLUGIN_DIR . 'woocommerce/shipping/ecpay/shipping-cvs-family.php';
        include_once RY_WTP_PLUGIN_DIR . 'woocommerce/shipping/ecpay/shipping-cvs-hilife.php';
        include_once RY_WTP_PLUGIN_DIR . 'woocommerce/shipping/ecpay/shipping-cvs-ok.php';
        include_once RY_WTP_PLUGIN_DIR . 'woocommerce/shipping/ecpay/shipping-home-post.php';
        include_once RY_WTP_PLUGIN_DIR . 'woocommerce/shipping/ecpay/shipping-home-tcat.php';

        include_once RY_WTP_PLUGIN_DIR . 'woocommerce/shipping/ecpay/shipping-block.php';
        RY_WTP_ECPay_Shipping_Block::instance();

        RY_WT_WC_ECPay_Shipping::$support_methods['ry_ecpay_shipping_cvs_711'] = 'RY_ECPay_Shipping_CVS_711_Pro';
        RY_WT_WC_ECPay_Shipping::$support_methods['ry_ecpay_shipping_cvs_family'] = 'RY_ECPay_Shipping_CVS_Family_Pro';
        RY_WT_WC_ECPay_Shipping::$support_methods['ry_ecpay_shipping_cvs_hilife'] = 'RY_ECPay_Shipping_CVS_Hilife_Pro';
        RY_WT_WC_ECPay_Shipping::$support_methods['ry_ecpay_shipping_cvs_ok'] = 'RY_ECPay_Shipping_CVS_Ok_Pro';
        RY_WT_WC_ECPay_Shipping::$support_methods['ry_ecpay_shipping_home_post'] = 'RY_ECPay_Shipping_Home_Post_Pro';
        RY_WT_WC_ECPay_Shipping::$support_methods['ry_ecpay_shipping_home_tcat'] = 'RY_ECPay_Shipping_Home_Tcat_Pro';

        RY_WTP_WC_Shipping::instance();

        add_filter('woocommerce_checkout_fields', [$this, 'hide_billing_info'], 9999);

        add_action('ry_ecpay_shipping_response_status_2030', [$this, 'shipping_transporting'], 10, 2);
        add_action('ry_ecpay_shipping_response_status_2068', [$this, 'shipping_transporting'], 10, 2);
        add_action('ry_ecpay_shipping_response_status_3006', [$this, 'shipping_transporting'], 10, 2);
        add_action('ry_ecpay_shipping_response_status_3032', [$this, 'shipping_transporting'], 10, 2);

        if ('yes' === RY_WT::get_option('ecpay_shipping_auto_get_no', 'yes')) {
            if ('yes' === RY_WTP::get_option('ecpay_shipping_auto_with_scheduler', 'no')) {
                remove_action('woocommerce_order_status_processing', [RY_WT_WC_ECPay_Shipping::instance(), 'get_code'], 10, 2);
                add_action('woocommerce_order_status_processing', [$this, 'get_code'], 10, 2);
                add_action('ry_wtp_get_ecpay_code', [RY_WT_WC_ECPay_Shipping_Api::instance(), 'get_code'], 10, 2);
            }
        }

        if (is_admin()) {
            include_once RY_WTP_PLUGIN_DIR . 'woocommerce/shipping/ecpay/includes/admin.php';
            RY_WTP_ECPay_Shipping_Admin::instance();
        } else {
            add_action('woocommerce_review_order_after_shipping', [$this, 'shipping_choose_cvs']);
            add_action('woocommerce_view_order', [$this, 'shipping_info']);
        }
    }

    public function hide_billing_info($fields)
    {
        $cvs_hide_fields = ['billing_postcode', 'billing_state', 'billing_city', 'billing_address_1', 'billing_address_2'];

        if (is_checkout()) {
            if ('yes' == RY_WTP::get_option('ecpay_cvs_billing_address', 'no')) {
                if(isset($fields['billing'])) {
                    foreach ($cvs_hide_fields as $key) {
                        if(isset($fields['billing'][$key])) {
                            if(isset($fields['billing'][$key]['class'])) {
                                $fields['billing'][$key]['class'][] = 'ry-cvs-hide';
                            } else {
                                $fields['billing'][$key]['class'] = ['ry-cvs-hide'];
                            }
                        }
                    }
                }
            }
        }

        if (did_action('woocommerce_checkout_process')) {
            if (RY_WTP::get_option('ecpay_cvs_billing_address', 'no') == 'yes') {
                $used_cvs = false;
                $shipping_method = isset($_POST['shipping_method']) ? wc_clean($_POST['shipping_method']) : [];
                foreach ($shipping_method as $method) {
                    $method = strstr($method, ':', true);
                    if (array_key_exists($method, RY_WT_WC_ECPay_Shipping::$support_methods)) {
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

    public function shipping_transporting($ipn_info, $order)
    {
        $order->update_status('ry-transporting');
    }

    public function get_code($order_ID, $order)
    {
        $shipping_list = $order->get_meta('_ecpay_shipping_info', true);
        if (!is_array($shipping_list)) {
            $shipping_list = [];
        }
        if (count($shipping_list) == 0) {
            WC()->queue()->schedule_single(time() + 10, 'ry_wtp_get_ecpay_code', [$order_ID], '');
        }
    }

    public function shipping_choose_cvs()
    {
        wp_enqueue_script('ry-wtp-shipping');
    }

    public function shipping_info($order_ID)
    {
        if (!$order = wc_get_order($order_ID)) {
            return;
        }

        $shipping_info_list = $order->get_meta('_ecpay_shipping_info', true);
        if (!is_array($shipping_info_list)) {
            $shipping_info_list = [];
        }

        $args = [
            'order' => $order,
            'shipping_info_list' => $shipping_info_list,
        ];
        wc_get_template('order/order-ecpay-shipping-info.php', $args, '', RY_WTP_PLUGIN_DIR . 'templates/');
    }
}
