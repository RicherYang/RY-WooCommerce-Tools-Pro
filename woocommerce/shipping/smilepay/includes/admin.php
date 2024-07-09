<?php

use Automattic\WooCommerce\Utilities\OrderUtil;

final class RY_WTP_SmilePay_Shipping_Admin
{
    protected static $_instance = null;

    public static function instance(): RY_WTP_SmilePay_Shipping_Admin
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

        if (class_exists('Automattic\WooCommerce\Utilities\OrderUtil') && OrderUtil::custom_orders_table_usage_is_enabled()) {
            if('edit' !== ($_GET['action'] ?? '')) {
                add_filter('bulk_actions-woocommerce_page_wc-orders', [$this, 'shop_order_list_action']);
                add_filter('handle_bulk_actions-woocommerce_page_wc-orders', [$this, 'print_shipping_note'], 10, 3);
            }
        } else {
            add_filter('bulk_actions-edit-shop_order', [$this, 'shop_order_list_action']);
            add_filter('handle_bulk_actions-edit-shop_order', [$this, 'print_shipping_note'], 10, 3);
        }

        // Support plugin (WooCommerce Print Invoice & Delivery Note)
        add_filter('wcdn_order_info_fields', [$this, 'add_wcdn_shipping_info'], 10, 2);
    }

    public function add_setting($settings, $current_section, $checkout_with_block)
    {
        if ($current_section == 'smilepay_shipping') {
            wp_enqueue_script('ry-wtp-admin-shipping');

            $setting_idx = array_search(RY_WT::OPTION_PREFIX . 'smilepay_shipping_auto_get_no', array_column($settings, 'id'));
            array_splice($settings, $setting_idx + 1, 0, [
                [
                    'title' => __('Auto get with scheduler action', 'ry-woocommerce-tools-pro'),
                    'id' => RY_WTP::OPTION_PREFIX . 'smilepay_shipping_auto_with_scheduler',
                    'type' => 'checkbox',
                    'default' => 'no',
                    'desc' => __('Get shipping payment no use scheduler action.', 'ry-woocommerce-tools-pro'),
                ],
            ]);

            if(!$checkout_with_block) {
                $setting_idx = array_search(RY_WT::OPTION_PREFIX . 'smilepay_shipping_log_status_change', array_column($settings, 'id'));
                array_splice($settings, $setting_idx, 0, [
                    [
                        'title' => __('cvs remove billing address', 'ry-woocommerce-tools-pro'),
                        'id' => RY_WTP::OPTION_PREFIX . 'smilepay_cvs_billing_address',
                        'type' => 'checkbox',
                        'default' => 'no',
                        'desc' => __('Remove billing address when shipping mode is cvs.', 'ry-woocommerce-tools-pro')
                            . '<p class="description" style="margin-bottom:2px">' . __('The billing address still will show in order details.', 'ry-woocommerce-tools-pro') . '</p>',
                    ],
                ]);
            }
        }
        return $settings;
    }

    public function shop_order_list_action($actions)
    {
        $actions['ry_print_smilepay_cvs_711'] = __('Print smilepay shipping booking note (711)', 'ry-woocommerce-tools-pro');
        $actions['ry_print_smilepay_cvs_fami'] = __('Print SmilePay shipping booking note (family)', 'ry-woocommerce-tools-pro');

        return $actions;
    }

    public function print_shipping_note($redirect_to, $action, $ids)
    {
        if (false !== strpos($action, 'ry_print_smilepay_')) {
            $redirect_to = add_query_arg(
                [
                    'orderid' => implode(',', $ids),
                    'type' => substr($action, 18),
                ],
                admin_url('admin-post.php?action=ry-print-smilepay-shipping'),
            );
            wp_redirect($redirect_to);
            exit();
        }

        return esc_url_raw($redirect_to);
    }

    public function add_wcdn_shipping_info($fields, $order)
    {
        foreach ($order->get_items('shipping') as $item) {
            $shipping_method = RY_WT_WC_SmilePay_Shipping::instance()->get_order_support_shipping($item);
            if (false === $shipping_method) {
                continue;
            }
            $shipping_list = $order->get_meta('_smilepay_shipping_info', true);
            if (is_array($shipping_list)) {
                $field_keys = array_keys($fields);
                $field_idx = array_search('order_number', $field_keys) + 1;
                $fields = array_slice($fields, 0, $field_idx)
                    + [
                        'ry_smilepay_shipping_id' => [
                            'label' => __('SmilePay shipping ID', 'ry-woocommerce-tools'),
                            'value' => implode(', ', array_column($shipping_list, 'ID')),
                        ],
                    ]
                    + array_slice($fields, $field_idx);
            }
        }

        return $fields;
    }
}
