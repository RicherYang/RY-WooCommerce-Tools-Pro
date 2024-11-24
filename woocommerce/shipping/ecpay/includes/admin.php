<?php

use Automattic\WooCommerce\Utilities\OrderUtil;

final class RY_WTP_ECPay_Shipping_Admin
{
    protected static $_instance = null;

    public static function instance(): RY_WTP_ECPay_Shipping_Admin
    {
        if (null === self::$_instance) {
            self::$_instance = new self();
            self::$_instance->do_init();
        }
        return self::$_instance;
    }

    protected function do_init(): void
    {
        add_filter('woocommerce_get_settings_rytools', [$this, 'add_setting'], 11, 3);

        add_action('admin_notices', [$this, 'bulk_action_notices']);
        if (class_exists('Automattic\WooCommerce\Utilities\OrderUtil') && OrderUtil::custom_orders_table_usage_is_enabled()) {
            if ('edit' !== ($_GET['action'] ?? '')) {
                add_filter('bulk_actions-woocommerce_page_wc-orders', [$this, 'shop_order_list_action']);
                add_filter('handle_bulk_actions-woocommerce_page_wc-orders', [$this, 'do_shop_order_action'], 10, 3);
            }
        } else {
            add_filter('bulk_actions-edit-shop_order', [$this, 'shop_order_list_action']);
            add_filter('handle_bulk_actions-edit-shop_order', [$this, 'do_shop_order_action'], 10, 3);
        }

        add_action('woocommerce_admin_order_data_after_shipping_address', [$this, 'add_choose_cvs_btn']);

        // Support plugin (WooCommerce Print Invoice & Delivery Note)
        add_filter('wcdn_order_info_fields', [$this, 'add_wcdn_shipping_info'], 10, 2);
    }

    public function add_setting($settings, $current_section, $checkout_with_block)
    {
        if ($current_section == 'ecpay_shipping') {
            wp_enqueue_script('ry-wtp-admin-shipping');

            $setting_idx = array_search(RY_WT::OPTION_PREFIX . 'ecpay_shipping_auto_get_no', array_column($settings, 'id'));
            array_splice($settings, $setting_idx + 1, 0, [
                [
                    'title' => __('Auto get with scheduler action', 'ry-woocommerce-tools-pro'),
                    'id' => RY_WTP::OPTION_PREFIX . 'ecpay_shipping_auto_with_scheduler',
                    'type' => 'checkbox',
                    'default' => 'no',
                    'desc' => __('Get shipping payment no use scheduler action.', 'ry-woocommerce-tools-pro'),
                ],
            ]);

            if (!$checkout_with_block) {
                $setting_idx = array_search(RY_WT::OPTION_PREFIX . 'ecpay_shipping_log_status_change', array_column($settings, 'id'));
                array_splice($settings, $setting_idx, 0, [
                    [
                        'title' => __('cvs remove billing address', 'ry-woocommerce-tools-pro'),
                        'id' => RY_WTP::OPTION_PREFIX . 'ecpay_cvs_billing_address',
                        'type' => 'checkbox',
                        'default' => 'no',
                        'desc' => __('Remove billing address when shipping mode is cvs.', 'ry-woocommerce-tools-pro')
                            . '<p class="description" style="margin-bottom:2px">' . __('The billing address still will show in order details.', 'ry-woocommerce-tools-pro') . '</p>',
                    ],
                ]);
            }

            $setting_idx = array_search(RY_WT::OPTION_PREFIX . 'ecpay_shipping_order_prefix', array_column($settings, 'id'));
            array_splice($settings, $setting_idx, 0, [
                [
                    'title' => __('Clean up receiver name', 'ry-woocommerce-tools-pro'),
                    'id' => RY_WT::OPTION_PREFIX . 'ecpay_shipping_cleanup_receiver_name',
                    'type' => 'checkbox',
                    'default' => 'no',
                    'desc' => __('Clean up receiver name to comply with ECPay request.', 'ry-woocommerce-tools-pro'),
                ],
            ]);

            $setting_idx = array_search(RY_WT::OPTION_PREFIX . 'ecpay_shipping_cvs_type', array_column($settings, 'id'));
            $settings[$setting_idx]['options']['B2C'] = _x('B2C', 'Cvs type', 'ry-woocommerce-tools-pro');

            $setting_idx = array_search(RY_WT::OPTION_PREFIX . 'ecpay_shipping_declare_over', array_column($settings, 'id'));
            $settings[$setting_idx]['options']['multi'] = __('multi package', 'ry-woocommerce-tools-pro');
        }
        return $settings;
    }

    public function bulk_action_notices()
    {
        if (empty($_GET['bulk_action'])) {
            return;
        }

        $bulk_action = wc_clean(wp_unslash($_GET['bulk_action']));
        $number = absint($_GET['ry_geted'] ?? 0);

        if ('ry_get_ecpay_no' === $bulk_action) {
            $message = sprintf(_n('%s order get shipping no.', '%s order get shipping no.', $number, 'woocommerce'), number_format_i18n($number));
            echo '<div class="updated"><p>' . esc_html($message) . '</p></div>';
        }
    }

    public function shop_order_list_action($actions)
    {
        $actions['ry_get_ecpay_no'] = __('Get ECPay shipping no', 'ry-woocommerce-tools-pro');

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

        $actions['ry_print_ecpay_home_post'] = __('Print ECPay shipping booking note (post)', 'ry-woocommerce-tools-pro');
        $actions['ry_print_ecpay_home_tcat'] = __('Print ECPay shipping booking note (tcat)', 'ry-woocommerce-tools-pro');

        return $actions;
    }

    public function do_shop_order_action($redirect_to, $action, $ids)
    {
        if ('ry_get_ecpay_no' === $action) {
            $geted = 0;

            $ry_shipping = RY_WT_WC_ECPay_Shipping::instance();
            $ry_shipping_api = RY_WT_WC_ECPay_Shipping_Api::instance();

            foreach ($ids as $order_ID) {
                $order = wc_get_order($order_ID);
                foreach ($order->get_items('shipping') as $shipping_item) {
                    $shipping_method = $ry_shipping->get_order_support_shipping($shipping_item);
                    if (false != $shipping_method) {
                        $geted += 1;
                        $ry_shipping_api->get_code($order_ID, 'cod' === $order->get_payment_method());
                    }
                }
            }

            $redirect_to = add_query_arg(
                [
                    'bulk_action' => 'ry_get_ecpay_no',
                    'ry_geted' => $geted,
                    'ids' => implode(',', $ids),
                ],
                $redirect_to
            );
        }

        if (str_starts_with($action, 'ry_print_ecpay_')) {
            $redirect_to = add_query_arg(
                [
                    'orderid' => implode(',', $ids),
                    'type' => substr($action, 15),
                ],
                admin_url('admin-post.php?action=ry-print-ecpay-shipping'),
            );
            wp_redirect($redirect_to);
            exit();
        }

        return $redirect_to;
    }

    public function add_choose_cvs_btn($order)
    {
        foreach ($order->get_items('shipping') as $item) {
            $shipping_method = RY_WT_WC_ECPay_Shipping::instance()->get_order_support_shipping($item);
            if (false !== $shipping_method && str_contains($shipping_method, '_cvs')) {
                list($MerchantID, $HashKey, $HashIV, $cvs_type) = RY_WT_WC_ECPay_Shipping::instance()->get_api_info();

                $choosed_cvs = '';
                if (isset($_POST['MerchantID']) && $_POST['MerchantID'] == $MerchantID) {
                    $choosed_cvs = [
                        'CVSStoreID' => wc_clean(wp_unslash($_POST['CVSStoreID'])),
                        'CVSStoreName' => wc_clean(wp_unslash($_POST['CVSStoreName'])),
                        'CVSAddress' => wc_clean(wp_unslash($_POST['CVSAddress'])),
                        'CVSTelephone' => wc_clean(wp_unslash($_POST['CVSTelephone'])),
                    ];
                }

                $method_class = RY_WT_WC_ECPay_Shipping::$support_methods[$shipping_method];
                wp_localize_script('ry-wtp-admin-shipping', 'ECPayInfo', [
                    'postUrl' => RY_WT_WC_ECPay_Shipping_Api::instance()->get_map_post_url(),
                    'postData' => [
                        'MerchantID' => $MerchantID,
                        'LogisticsType' => $method_class::Shipping_Type,
                        'LogisticsSubType' => $method_class::Shipping_Sub_Type . (('C2C' === $cvs_type) ? 'C2C' : ''),
                        'IsCollection' => 'Y',
                        'ServerReplyURL' => esc_url(add_query_arg([
                            'ry-ecpay-map-redirect' => 'ry-ecpay-map-redirect',
                            'lang' => get_locale(),
                        ], WC()->api_request_url('ry_ecpay_map_callback'))),
                        'ExtraData' => 'ry' . $order->get_id(),
                    ],
                    'newStore' => $choosed_cvs,
                ]);

                wp_enqueue_script('ry-wtp-admin-shipping');

                include RY_WTP_PLUGIN_DIR . 'woocommerce/admin/meta-boxes/views/choose_cvs_btn.php';
                break;
            }
        }
    }

    public function add_wcdn_shipping_info($fields, $order)
    {
        foreach ($order->get_items('shipping') as $item) {
            $shipping_method = RY_WT_WC_ECPay_Shipping::instance()->get_order_support_shipping($item);
            if (false === $shipping_method) {
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
                            'value' => implode(', ', array_column($shipping_list, 'ID')),
                        ],
                    ]
                    + array_slice($fields, $field_idx);
            }
        }

        return $fields;
    }
}
