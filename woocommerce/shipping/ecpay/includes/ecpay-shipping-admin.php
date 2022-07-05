<?php
final class RY_WTP_ECPay_Shipping_admin
{
    public static function init()
    {
        if ('yes' === RY_WT::get_option('ecpay_shipping', 'no')) {
            add_filter('bulk_actions-edit-shop_order', [__CLASS__, 'shop_order_list_action']);
            add_filter('handle_bulk_actions-edit-shop_order', [__CLASS__, 'print_shipping_note'], 10, 3);

            add_action('woocommerce_admin_order_data_after_shipping_address', [__CLASS__, 'add_choose_cvs_btn']);

            add_filter('woocommerce_order_actions', [__CLASS__, 'add_order_actions']);
            add_action('woocommerce_order_action_get_new_ecpay_no_t2', ['RY_ECPay_Shipping_Api', 'get_code_t2']);
            add_action('woocommerce_order_action_get_new_ecpay_no_t3', ['RY_ECPay_Shipping_Api', 'get_code_t3']);
        }
    }

    public static function shop_order_list_action($actions)
    {
        switch (RY_WT::get_option('ecpay_shipping_cvs_type')) {
            case 'B2C':
                $actions['ry_print_ecpay_cvs_711'] = __('Print ECPay shipping booking note (711)', 'ry-woocommerce-tools-pro');
                $actions['ry_print_ecpay_cvs_family'] = __('Print ECPay shipping booking note (family)', 'ry-woocommerce-tools-pro');
                $actions['ry_print_ecpay_cvs_hilife'] = __('Print ECPay shipping booking note (hilife)', 'ry-woocommerce-tools-pro');
                break;
            case 'C2C':
                $actions['ry_print_ecpay_cvs_711'] = __('Print ECPay shipping booking note (711)', 'ry-woocommerce-tools-pro');
                $actions['ry_print_ecpay_cvs_family'] = __('Print ECPay shipping booking note (family)', 'ry-woocommerce-tools-pro');
                $actions['ry_print_ecpay_cvs_hilife'] = __('Print ECPay shipping booking note (hilife)', 'ry-woocommerce-tools-pro');
                $actions['ry_print_ecpay_cvs_ok'] = __('Print ECPay shipping booking note (ok)', 'ry-woocommerce-tools-pro');
                break;
        }

        $actions['ry_print_ecpay_home_tcat'] = __('Print ECPay shipping booking note (tcat)', 'ry-woocommerce-tools-pro');

        return $actions;
    }

    public static function print_shipping_note($redirect_to, $action, $ids)
    {
        if (false !== strpos($action, 'ry_print_ecpay_')) {
            $redirect_to = add_query_arg(
                [
                    'orderid' => implode(',', $ids),
                    'type' => substr($action, 15),
                    'noheader' => 1,
                ],
                admin_url('admin.php?page=ry_print_ecpay_shipping')
            );
            wp_redirect($redirect_to);
            exit();
        }

        return esc_url_raw($redirect_to);
    }

    public static function add_choose_cvs_btn($order)
    {
        foreach ($order->get_items('shipping') as $item) {
            $shipping_method = RY_ECPay_Shipping::get_order_support_shipping($item);
            if ($shipping_method !== false && strpos($shipping_method, 'cvs') !== false) {
                list($MerchantID, $HashKey, $HashIV, $CVS_type) = RY_ECPay_Shipping::get_ecpay_api_info();

                $choosed_cvs = '';
                if (isset($_POST['MerchantID']) && $_POST['MerchantID'] == $MerchantID) {
                    $choosed_cvs = [
                        'CVSStoreID' => wc_clean(wp_unslash($_POST['CVSStoreID'])),
                        'CVSStoreName' => wc_clean(wp_unslash($_POST['CVSStoreName'])),
                        'CVSAddress' => wc_clean(wp_unslash($_POST['CVSAddress'])),
                        'CVSTelephone' => wc_clean(wp_unslash($_POST['CVSTelephone']))
                    ];
                }

                $method_class = RY_ECPay_Shipping::$support_methods[$shipping_method];
                wp_localize_script('ry-pro-admin-shipping', 'ECPayInfo', [
                    'postUrl' => RY_ECPay_Shipping_Api::get_map_post_url(),
                    'postData' => [
                        'MerchantID' => $MerchantID,
                        'LogisticsType' => $method_class::$LogisticsType,
                        'LogisticsSubType' => $method_class::$LogisticsSubType . (('C2C' == $CVS_type) ? 'C2C' : ''),
                        'IsCollection' => 'Y',
                        'ServerReplyURL' => esc_url(WC()->api_request_url('ry_ecpay_map_callback')),
                        'ExtraData' => 'ry' . $order->get_id()
                    ],
                    'newStore' => $choosed_cvs,
                ]);

                wp_enqueue_script('ry-pro-admin-shipping');

                include RY_WTP_PLUGIN_DIR . 'woocommerce/admin/meta-boxes/views/choose_cvs_btn.php';
                break;
            }
        }
    }

    public static function add_order_actions($order_actions)
    {
        global $theorder, $post;
        if (!is_object($theorder)) {
            $theorder = wc_get_order($post->ID);
        }

        foreach ($theorder->get_items('shipping') as $item) {
            $shipping_method = RY_ECPay_Shipping::get_order_support_shipping($item);
            if ($shipping_method !== false) {
                $method_class = RY_ECPay_Shipping::$support_methods[$shipping_method];
                if (in_array('2', $method_class::$support_temp)) {
                    $order_actions['get_new_ecpay_no_t2'] = __('Get new Ecpay shipping no (refrigerated)', 'ry-woocommerce-tools-pro');
                }
                if (in_array('3', $method_class::$support_temp)) {
                    $order_actions['get_new_ecpay_no_t3'] = __('Get new Ecpay shipping no (freezer)', 'ry-woocommerce-tools-pro');
                }
            }
        }
        return $order_actions;
    }
}

RY_WTP_ECPay_Shipping_admin::init();
