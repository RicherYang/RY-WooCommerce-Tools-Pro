<?php
final class RY_WTP_ECPay_Shipping
{
    public static function init()
    {
        include_once RY_WTP_PLUGIN_DIR . 'woocommerce/shipping/ry-base.php';
        include_once RY_WTP_PLUGIN_DIR . 'woocommerce/shipping/ecpay/ecpay-shipping-cvs-711.php';
        include_once RY_WTP_PLUGIN_DIR . 'woocommerce/shipping/ecpay/ecpay-shipping-cvs-family.php';
        include_once RY_WTP_PLUGIN_DIR . 'woocommerce/shipping/ecpay/ecpay-shipping-cvs-hilife.php';
        include_once RY_WTP_PLUGIN_DIR . 'woocommerce/shipping/ecpay/ecpay-shipping-home-tcat.php';
        include_once RY_WTP_PLUGIN_DIR . 'woocommerce/shipping/ecpay/ecpay-shipping-home-ecan.php';

        if (is_admin()) {
            add_filter('woocommerce_get_settings_rytools', [__CLASS__, 'add_setting'], 11, 2);

            if (version_compare(RY_WT_VERSION, '1.1.14', '>=')) {
                add_filter('bulk_actions-edit-shop_order', [__CLASS__, 'shop_order_list_action']);
                add_filter('handle_bulk_actions-edit-shop_order', [__CLASS__, 'print_cvs_shipping_note'], 10, 3);
                add_action('wp_ajax_RY_ECPay_Pro_Shipping_print', [__CLASS__, 'print_info']);
            }

            // Support plugin (WooCommerce Print Invoice & Delivery Note)
            add_filter('wcdn_order_info_fields', [__CLASS__, 'add_wcdn_shipping_info'], 10, 2);
        } else {
            add_action('woocommerce_view_order', [__CLASS__, 'shipping_info']);
        }

        if ('yes' === RY_WT::get_option('ecpay_shipping', 'no')) {
            add_filter('woocommerce_shipping_methods', [__CLASS__, 'use_pro_method'], 11);
            add_filter('woocommerce_checkout_fields', [__CLASS__, 'hide_billing_info'], 9999);

            add_action('ry_ecpay_shipping_response_status_2030', [__CLASS__, 'shipping_transporting'], 10, 2);
            add_action('ry_ecpay_shipping_response_status_2068', [__CLASS__, 'shipping_transporting'], 10, 2);
            add_action('ry_ecpay_shipping_response_status_3006', [__CLASS__, 'shipping_transporting'], 10, 2);
            add_action('ry_ecpay_shipping_response_status_3032', [__CLASS__, 'shipping_transporting'], 10, 2);

            if ('yes' === RY_WT::get_option('ecpay_shipping_auto_get_no', 'yes')) {
                if ('yes' === RY_WTP::get_option('ecpay_shipping_auto_with_scheduler', 'no')) {
                    remove_action('woocommerce_order_status_processing', ['RY_ECPay_Shipping', 'get_code'], 10, 2);
                    add_action('woocommerce_order_status_processing', [__CLASS__, 'get_code'], 10, 2);
                    add_action('ry_wtp_get_ecpay_cvs_code', ['RY_ECPay_Shipping_Api', 'get_code'], 10, 2);
                }
            }

            if (is_admin()) {
                if (version_compare(RY_WT_VERSION, '1.2.13', '>=')) {
                    add_action('woocommerce_admin_order_data_after_shipping_address', [__CLASS__, 'add_choose_cvs_btn']);
                }
            } else {
                add_action('woocommerce_review_order_after_shipping', [__CLASS__, 'shipping_choose_cvs']);
                add_filter('woocommerce_update_order_review_fragments', [__CLASS__, 'shipping_choose_cvs_info'], 11);
            }
        }
    }

    public static function add_setting($settings, $current_section)
    {
        if ($current_section == 'ecpay_shipping') {
            wp_enqueue_script('ry-pro-admin-shipping');

            $setting_id_idx = array_column($settings, 'id');
            $setting_idx = array_search(RY_WT::$option_prefix . 'ecpay_shipping_auto_get_no', $setting_id_idx);
            array_splice($settings, $setting_idx + 1, 0, [
                [
                    'title' => __('Auto get with scheduler action', 'ry-woocommerce-tools-pro'),
                    'id' => RY_WTP::$option_prefix . 'ecpay_shipping_auto_with_scheduler',
                    'type' => 'checkbox',
                    'default' => 'no',
                    'desc' => __('Get shipping payment no use scheduler action.', 'ry-woocommerce-tools-pro')
                ]
            ]);

            if (version_compare(RY_WT_VERSION, '1.7.0', '>=')) {
                $setting_id_idx = array_column($settings, 'id');
                $setting_idx = array_search(RY_WT::$option_prefix . 'ecpay_shipping_log_status_change', $setting_id_idx);
                array_splice($settings, $setting_idx + 1, 0, [
                    [
                        'title' => __('Clean up receiver name', 'ry-woocommerce-tools-pro'),
                        'id' => RY_WT::$option_prefix . 'ecpay_shipping_cleanup_receiver_name',
                        'type' => 'checkbox',
                        'default' => 'no',
                        'desc' => __('Clean up receiver name to comply with EcPay request.', 'ry-woocommerce-tools-pro')
                    ]
                ]);
            }

            $setting_id_idx = array_column($settings, 'id');
            $setting_idx = array_search(RY_WT::$option_prefix . 'ecpay_shipping_cvs_type', $setting_id_idx);
            $settings[$setting_idx]['options']['B2C'] = _x('B2C', 'Cvs type', 'ry-woocommerce-tools-pro');

            $setting_id_idx = array_column($settings, 'id');
            $setting_idx = array_search(RY_WT::$option_prefix . 'ecpay_shipping_auto_completed', $setting_id_idx);
            array_splice($settings, $setting_idx + 1, 0, [
                [
                    'title' => __('cvs remove billing address', 'ry-woocommerce-tools-pro'),
                    'id' => RY_WTP::$option_prefix . 'ecpay_cvs_billing_address',
                    'type' => 'checkbox',
                    'default' => 'no',
                    'desc' => __('Remove billing address when shipping mode is cvs.', 'ry-woocommerce-tools-pro') . '<br>'
                        . __('The billing address still will show in order details.', 'ry-woocommerce-tools-pro')
                ]
            ]);

            $setting_id_idx = array_column($settings, 'id');
            $setting_idx = array_search(RY_WT::$option_prefix . 'ecpay_shipping_order_prefix', $setting_id_idx);
            array_splice($settings, $setting_idx + 1, 0, [
                [
                    'title' => __('shipping item name', 'ry-woocommerce-tools-pro'),
                    'id' => RY_WT::$option_prefix . 'shipping_item_name',
                    'type' => 'text',
                    'default' => '',
                    'desc' => __('If empty use the first product name.', 'ry-woocommerce-tools-pro'),
                    'desc_tip' => true
                ]
            ]);
        }
        return $settings;
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
                if (version_compare(RY_WT_VERSION, '1.2.6', '>=')) {
                    $actions['ry_print_ecpay_cvs_family'] = __('Print ECPay shipping booking note (family)', 'ry-woocommerce-tools-pro');
                    $actions['ry_print_ecpay_cvs_hilife'] = __('Print ECPay shipping booking note (hilife)', 'ry-woocommerce-tools-pro');
                }
                break;
        }

        if (version_compare(RY_WT_VERSION, '1.4.0', '>=')) {
            $actions['ry_print_ecpay_home'] = __('Print ECPay shipping booking note (home)', 'ry-woocommerce-tools-pro');
        }

        return $actions;
    }

    public static function print_cvs_shipping_note($redirect_to, $action, $ids)
    {
        if (false !== strpos($action, 'ry_print_ecpay_')) {
            $redirect_to = esc_url_raw($redirect_to);
            $redirect_to = remove_query_arg(array('action', 'action2', 'tags_input', 'post_author', 'comment_status', 'ping_status', '_status', 'post', 'bulk_edit', 'post_view'), $redirect_to);
            $redirect_to = add_query_arg(
                [
                    'action' => 'RY_ECPay_Pro_Shipping_print',
                    'type' => strrchr($action, '_'),
                    'ids' => implode(',', $ids),
                    'redirectTo' => urlencode($redirect_to),
                ],
                admin_url('admin-ajax.php')
            );
            wp_redirect($redirect_to);
            exit();
        }

        return esc_url_raw($redirect_to);
    }

    public static function print_info()
    {
        $print_type = isset($_GET['type']) ? (string) $_GET['type'] : '';
        $order_ids = array_map('intval', explode(',', $_GET['ids']));
        $order_ids = array_filter($order_ids);
        $logistics_list = [];
        foreach ($order_ids as $order_id) {
            $order = wc_get_order($order_id);
            if ($order) {
                foreach ($order->get_items('shipping') as $item_id => $item) {
                    $shipping_method = RY_ECPay_Shipping::get_order_support_shipping($item);
                    if ($shipping_method === false) {
                        continue;
                    }

                    if (strpos($shipping_method, $print_type) === false) {
                        continue;
                    }

                    $shipping_list = $order->get_meta('_ecpay_shipping_info', true);
                    if (is_array($shipping_list)) {
                        foreach ($shipping_list as $info) {
                            $logistics_list[$info['ID']] = $order_id;
                        }
                    }
                }
            }
        }

        if (count($logistics_list)) {
            if ($print_type == '_home') {
                $print_info = RY_ECPay_Shipping_Api::get_print_info_form(array_keys($logistics_list));
            } else {
                switch (RY_WT::get_option('ecpay_shipping_cvs_type')) {
                    case 'B2C':
                        $print_info = RY_ECPay_Shipping_Api::get_print_info_form(array_keys($logistics_list));
                        break;
                    case 'C2C':
                        $iframe_size = '';
                        switch ($print_type) {
                            case '_family':
                                $iframe_size = 'width:320px;height:515px';
                                $td_col = 2;
                                break;
                            case '_hilife':
                                $iframe_size = 'width:990px;height:315px';
                                $td_col = 1;
                                break;
                            case '_711':
                                $iframe_size = 'width:850px;height:800px';
                                $td_col = 1;
                                break;
                        }

                        $print_info = '<!DOCTYPE html><html><head><meta charset="' . get_bloginfo('charset', 'display') . '"></head><body style="margin:0;padding:0"><table style="max-width:900px;width:100%;"><tbody><tr>';
                        $foreach_count = 0;
                        foreach ($logistics_list as $logistics_id => $order_id) {
                            $iframe_src = add_query_arg(
                                [
                                    'action' => 'RY_ECPay_Shipping_print',
                                    'orderid' => $order_id,
                                    'id' => $logistics_id,
                                    'only' => 1
                                ],
                                admin_url('admin-ajax.php')
                            );
                            if ($foreach_count > 0 && $foreach_count % $td_col == 0) {
                                $print_info .= '</tr><tr>';
                            }
                            $print_info .= '<td><iframe src="' . $iframe_src . '" style="border:0;' . $iframe_size . '"></iframe></td>';
                            $foreach_count += 1;
                        }
                        $print_info .= '</tr></tbody></table></body></html>';
                        break;
                }
            }

            echo($print_info);
            wp_die();
        } else {
            $redirect_to = esc_url_raw($_GET['redirectTo']);
            wp_redirect($redirect_to);
            exit();
        }
    }

    public static function add_wcdn_shipping_info($fields, $order)
    {
        foreach ($order->get_items('shipping') as $item_id => $item) {
            $shipping_method = RY_ECPay_Shipping::get_order_support_shipping($item);
            if ($shipping_method === false) {
                continue;
            }
            $shipping_list = $order->get_meta('_ecpay_shipping_info', true);
            if (is_array($shipping_list)) {
                $field_keys = array_keys($fields);
                $field_idx = array_search('order_number', $field_keys) + 1;
                $fields = array_slice($fields, 0, $field_idx)
                    + [
                        'ry_ecpay_shipping_id' => [
                            'label' => __('ECPay shipping ID', 'ry-woocommerce-tools'),
                            'value' => implode(', ', array_column($shipping_list, 'ID'))
                        ]
                    ]
                    + array_slice($fields, $field_idx);
            }
        }

        return $fields;
    }

    public static function use_pro_method($shipping_methods)
    {
        foreach ($shipping_methods as $method => $method_class) {
            if (substr($method, 0, 9) == 'ry_ecpay_') {
                if (substr($method_class, -4) != '_Pro') {
                    $shipping_methods[$method] = $method_class . '_Pro';
                }
            }
        }

        return $shipping_methods;
    }

    public static function hide_billing_info($fields)
    {
        if (is_checkout()) {
            $chosen_method = isset(WC()->session->chosen_shipping_methods) ? WC()->session->chosen_shipping_methods : [];
            $is_support = false;
            if (count($chosen_method)) {
                foreach (RY_ECPay_Shipping::$support_methods as $method => $method_class) {
                    if (strpos($chosen_method[0], $method) === 0) {
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
            if (RY_WTP::get_option('ecpay_cvs_billing_address', 'no') == 'yes') {
                $used_cvs = false;
                $shipping_method = isset($_POST['shipping_method']) ? wc_clean($_POST['shipping_method']) : [];
                foreach ($shipping_method as $method) {
                    $method = strstr($method, ':', true);
                    if (array_key_exists($method, RY_ECPay_Shipping::$support_methods)) {
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

    public static function get_code($order_id, $order)
    {
        $shipping_list = $order->get_meta('_ecpay_shipping_info', true);
        if (!is_array($shipping_list)) {
            $shipping_list = [];
        }
        if (count($shipping_list) == 0) {
            WC()->queue()->schedule_single(time() + 3, 'ry_wtp_get_ecpay_cvs_code', [$order_id], '');
        }
    }

    public static function shipping_transporting($ipn_info, $order)
    {
        $order->update_status('ry-transporting');
    }

    public static function add_choose_cvs_btn($order)
    {
        foreach ($order->get_items('shipping') as $item_id => $item) {
            $method_class = RY_ECPay_Shipping::get_order_support_shipping($item);
            if ($method_class !== false && strpos($method_class, 'cvs') !== false) {
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

    public static function shipping_choose_cvs()
    {
        wp_enqueue_script('ry-pro-shipping');
    }

    public static function shipping_choose_cvs_info($fragments)
    {
        if (isset($fragments['ecpay_shipping_info'])) {
            if ('yes' == RY_WTP::get_option('ecpay_cvs_billing_address', 'no')) {
                $chosen_method = isset(WC()->session->chosen_shipping_methods) ? WC()->session->chosen_shipping_methods : [];
                $fragments['hide_billing_address'] = strpos($chosen_method[0], '_cvs');
            }
        }

        return $fragments;
    }

    public static function shipping_info($order_id)
    {
        if (!$order = wc_get_order($order_id)) {
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

RY_WTP_ECPay_Shipping::init();
