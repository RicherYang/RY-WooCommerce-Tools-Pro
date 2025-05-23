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
        include_once RY_WTP_PLUGIN_DIR . 'woocommerce/shipping/ecpay/includes/ajax.php';
        RY_WTP_ECPay_Shipping_Admin_Ajax::instance();

        add_filter('woocommerce_get_settings_rytools', [$this, 'add_setting'], 11, 3);

        add_action('admin_notices', [$this, 'bulk_action_notices']);
        if (class_exists('Automattic\WooCommerce\Utilities\OrderUtil') && OrderUtil::custom_orders_table_usage_is_enabled()) {
            if ('edit' !== ($_GET['action'] ?? '')) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended , WordPress.Security.ValidatedSanitizedInput.MissingUnslash , WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
                add_filter('bulk_actions-woocommerce_page_wc-orders', [$this, 'shop_order_list_action']);
                add_filter('handle_bulk_actions-woocommerce_page_wc-orders', [$this, 'do_shop_order_action'], 10, 3);
            }
        } else {
            add_filter('bulk_actions-edit-shop_order', [$this, 'shop_order_list_action']);
            add_filter('handle_bulk_actions-edit-shop_order', [$this, 'do_shop_order_action'], 10, 3);
        }

        add_action('woocommerce_admin_order_data_after_shipping_address', [$this, 'add_choose_cvs_btn']);
        add_action('ry_shipping_info_list-column_action', [$this, 'add_info_btn'], 10, 2);

        // Support plugin (WooCommerce Print Invoice & Delivery Note)
        add_filter('wcdn_order_info_fields', [$this, 'add_wcdn_shipping_info'], 10, 2);
    }

    public function add_setting($settings, $current_section, $checkout_with_block)
    {
        if ($current_section == 'ecpay_shipping') {
            wp_enqueue_script('ry-wtp-admin-setting');

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
        $bulk_action = wp_unslash($_GET['bulk_action'] ?? ''); // phpcs:ignore WordPress.Security.NonceVerification.Recommended , WordPress.Security.ValidatedSanitizedInput.InputNotSanitized

        if ('ry_get_ecpay_no' === $bulk_action) {
            $number = intval($_GET['ry_geted'] ?? ''); // phpcs:ignore WordPress.Security.NonceVerification.Recommended

            /* translators: %s: count */
            $message = sprintf(_n('%s order get shipping no.', '%s order get shipping no.', $number, 'ry-woocommerce-tools-pro'), number_format_i18n($number));
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
                    if ($shipping_method) {
                        $geted += 1;
                        $ry_shipping_api->get_code($order_ID, 'cod' === $order->get_payment_method());
                    }
                }
            }

            $redirect_to = add_query_arg([
                'bulk_action' => 'ry_get_ecpay_no',
                'ry_geted' => $geted,
                'ids' => implode(',', $ids),
            ], $redirect_to);
        }

        if (str_starts_with($action, 'ry_print_ecpay_')) {
            $redirect_to = add_query_arg([
                'action' => 'ry-print-ecpay-shipping',
                'orderid' => implode(',', $ids),
                'type' => substr($action, 15),
                '_wpnonce' => wp_create_nonce('ry-print-shipping'),
            ], admin_url('admin-post.php'));
            wp_redirect($redirect_to);
            exit();
        }

        return $redirect_to;
    }

    public function add_choose_cvs_btn($order)
    {
        foreach ($order->get_items('shipping') as $shipping_item) {
            $shipping_method = RY_WT_WC_ECPay_Shipping::instance()->get_order_support_shipping($shipping_item);
            if ($shipping_method) {
                if (str_contains($shipping_method, '_cvs')) {
                    list($MerchantID, $HashKey, $HashIV, $cvs_type) = RY_WT_WC_ECPay_Shipping::instance()->get_api_info();

                    $choosed_cvs = '';
                    if (isset($_POST['MerchantID']) && $_POST['MerchantID'] === $MerchantID) { // phpcs:ignore WordPress.Security.NonceVerification.Missing
                        $choosed_cvs = [
                            'CVSStoreID' => sanitize_text_field(wp_unslash($_POST['CVSStoreID'] ?? '')), // phpcs:ignore WordPress.Security.NonceVerification.Missing
                            'CVSStoreName' => sanitize_text_field(wp_unslash($_POST['CVSStoreName'] ?? '')), // phpcs:ignore WordPress.Security.NonceVerification.Missing
                            'CVSAddress' => sanitize_text_field(wp_unslash($_POST['CVSAddress'] ?? '')), // phpcs:ignore WordPress.Security.NonceVerification.Missing
                            'CVSTelephone' => sanitize_text_field(wp_unslash($_POST['CVSTelephone'] ?? '')), // phpcs:ignore WordPress.Security.NonceVerification.Missing
                        ];
                    }

                    $method_class = RY_WT_WC_ECPay_Shipping::$support_methods[$shipping_method];
                    wp_localize_script('ry-wtp-admin-order', 'RyInfo', [
                        'ecpay' => [
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
                        ],
                        '_nonce' => [
                            'payment' => wp_create_nonce('get-payment-info'),
                            'shipping' => wp_create_nonce('get-shipping-info'),
                        ],
                    ]);

                    wp_enqueue_script('ry-wtp-admin-order');

                    include RY_WTP_PLUGIN_DIR . 'woocommerce/admin/meta-boxes/view/choose_cvs_btn.php';
                    break;
                }
            }
        }
    }

    public function add_info_btn($order, $item)
    {
        add_action('admin_footer', [$this, 'shipping_info_template']);

        echo '<button type="button" class="button ry-show-shipping-info" data-orderid="' . esc_attr($order->get_id()) . '" data-id="' . esc_attr($item['ID']) . '">' . esc_html__('Get info', 'ry-woocommerce-tools-pro') . '</button>';
    }

    public function shipping_info_template()
    {
        include RY_WTP_PLUGIN_DIR . 'woocommerce/shipping/ecpay/includes/view/shipping-info-template.php';
    }

    public function add_wcdn_shipping_info($fields, $order)
    {
        foreach ($order->get_items('shipping') as $shipping_item) {
            $shipping_method = RY_WT_WC_ECPay_Shipping::instance()->get_order_support_shipping($shipping_item);
            if ($shipping_method) {
                $shipping_list = $order->get_meta('_ecpay_shipping_info', true);
                if (is_array($shipping_list)) {
                    $field_keys = array_keys($fields);
                    $field_idx = array_search('order_number', $field_keys) + 1;
                    $fields = array_slice($fields, 0, $field_idx)
                        + [
                            'ry_ecpay_shipping_id' => [
                                'label' => __('ECPay shipping ID', 'ry-woocommerce-tools-pro'),
                                'value' => implode(', ', array_column($shipping_list, 'ID')),
                            ],
                        ]
                        + array_slice($fields, $field_idx);
                    break;
                }
            }
        }

        return $fields;
    }
}
