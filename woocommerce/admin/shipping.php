<?php

use Automattic\WooCommerce\Utilities\OrderUtil;

final class RY_WTP_WC_Admin_Shipping
{
    protected static $_instance = null;

    public static function instance(): RY_WTP_WC_Admin_Shipping
    {
        if (null === self::$_instance) {
            self::$_instance = new self();
            self::$_instance->do_init();
        }

        return self::$_instance;
    }

    protected function do_init(): void
    {
        if (class_exists('Automattic\WooCommerce\Utilities\OrderUtil') && OrderUtil::custom_orders_table_usage_is_enabled()) {
            if ('edit' !== ($_GET['action'] ?? '')) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended , WordPress.Security.ValidatedSanitizedInput.MissingUnslash , WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
                add_filter('manage_woocommerce_page_wc-orders_columns', [$this, 'shop_order_columns'], 11);
                add_action('manage_woocommerce_page_wc-orders_custom_column', [$this, 'shop_order_column'], 11, 2);

                add_filter('bulk_actions-woocommerce_page_wc-orders', [$this, 'shop_order_list_action']);
            }
        } else {
            add_filter('manage_shop_order_posts_columns', [$this, 'shop_order_columns'], 11);
            add_action('manage_shop_order_posts_custom_column', [$this, 'shop_order_column'], 11, 2);

            add_filter('bulk_actions-woocommerce_page_wc-orders', [$this, 'shop_order_list_action']);
        }

        add_action('woocommerce_product_options_dimensions', [$this, 'shipping_options'], 99);
        add_action('woocommerce_variation_options_dimensions', [$this, 'variation_shipping_options'], 99, 3);

        add_action('woocommerce_admin_process_product_object', [$this, 'save_shipping_options']);
        add_action('woocommerce_admin_process_variation_object', [$this, 'save_variation_shipping_options'], 20, 2);

        add_action('ry_shipping_info-action', [$this, 'add_shipping_info_action'], 10, 2);
    }

    public function shop_order_columns($columns)
    {
        $pre_columns = [
            'ry_shipping_no' => __('Shipping payment no', 'ry-woocommerce-tools-pro'),
        ];
        $pre_idx = array_search('shipping_address', array_keys($columns)) + 1;
        $pre_array = array_splice($columns, 0, $pre_idx);
        return array_merge($pre_array, $pre_columns, $columns);
    }

    public function shop_order_column($column, $order)
    {
        if ('ry_shipping_no' == $column) {
            if (!is_object($order)) {
                global $the_order;
                $order = $the_order;
            }

            foreach (['_ecpay_shipping_info', '_newebpay_shipping_info', '_smilepay_shipping_info'] as $meta_key) {
                $shipping_list = $order->get_meta($meta_key, true);
                if (is_array($shipping_list)) {
                    foreach ($shipping_list as $item) {
                        if (!isset($item['LogisticsType'])) {
                            $item['LogisticsType'] = 'CVS';
                        }

                        if ('CVS' == $item['LogisticsType']) {
                            if (!empty($item['PaymentNo'])) {
                                echo esc_html($item['PaymentNo']) . '<span class="validationno">' . esc_html($item['ValidationNo'] ?? '') . '<br>';
                            }
                        }
                        if ('HOME' == $item['LogisticsType']) {
                            if (!empty($item['BookingNote'])) {
                                echo esc_html($item['BookingNote']) . '<br>';
                            }
                        }
                    }
                }
            }
        }
    }

    public function shop_order_list_action($actions)
    {
        $add_idx = count($actions);
        $loog_idx = 0;
        foreach ($actions as $key => $action) {
            $loog_idx += 1;
            if (str_starts_with($key, 'mark_')) {
                $add_idx = $loog_idx;
            }
        }

        return array_slice($actions, 0, $add_idx) + [
            'mark_ry-transporting' => __('Change status to transporting', 'ry-woocommerce-tools-pro'),
        ] + array_slice($actions, $add_idx);
    }

    public function shipping_options()
    {
        global $product_object;

        include RY_WTP_PLUGIN_DIR . 'woocommerce/admin/view/product-shipping-option.php';
    }

    public function variation_shipping_options($loop, $variation_data, $variation)
    {
        $variation_object = wc_get_product($variation);
        $product_object = wc_get_product($variation_object->get_parent_id());

        include RY_WTP_PLUGIN_DIR . 'woocommerce/admin/view/product-variation-shipping-option.php';
    }

    public function save_shipping_options($product)
    {
        $shipping_amount = sanitize_text_field(wp_unslash($_POST['ry_shipping_amount'] ?? '')); // phpcs:ignore WordPress.Security.NonceVerification.Missing
        $shipping_amount = ('' === $shipping_amount) ? '' : wc_format_decimal($shipping_amount);
        $product->update_meta_data('_ry_shipping_amount', $shipping_amount);

        $temp = (string) intval($_POST['ry_shipping_temp'] ?? ''); // phpcs:ignore WordPress.Security.NonceVerification.Missing
        if (!in_array($temp, ['1', '2', '3'], true)) {
            $temp = '1';
        }
        $product->update_meta_data('_ry_shipping_temp', $temp);

        $skip_shipping = (array) wp_unslash($_POST['ry_shipping_skip_shipping'] ?? ''); // phpcs:ignore WordPress.Security.NonceVerification.Missing , WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
        $skip_shipping = array_filter(array_map('sanitize_text_field', $skip_shipping));
        if (count($skip_shipping)) {
            $product->update_meta_data('_ry_shipping_skip_shipping', $skip_shipping);
        } else {
            $product->delete_meta_data('_ry_shipping_skip_shipping');
        }
    }

    public function save_variation_shipping_options($variation, $i)
    {
        $shipping_amount = sanitize_text_field(wp_unslash($_POST['variable_ry_shipping_amount'][$i] ?? '')); // phpcs:ignore WordPress.Security.NonceVerification.Missing
        $shipping_amount = ('' === $shipping_amount) ? '' : wc_format_decimal($shipping_amount);
        $variation->update_meta_data('_ry_shipping_amount', $shipping_amount);

        $temp = (string) intval($_POST['variable_ry_shipping_temp'][$i] ?? ''); // phpcs:ignore WordPress.Security.NonceVerification.Missing
        if (!in_array($temp, ['0', '1', '2', '3'], true)) {
            $temp = '0';
        }
        $variation->update_meta_data('_ry_shipping_temp', $temp);

        $skip_shipping = (array) wp_unslash($_POST['variable_ry_shipping_skip_shipping'][$i] ?? ''); // phpcs:ignore WordPress.Security.NonceVerification.Missing , WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
        $skip_shipping = array_filter(array_map('sanitize_text_field', $skip_shipping));
        if (count($skip_shipping)) {
            $variation->update_meta_data('_ry_shipping_skip_shipping', $skip_shipping);
        } else {
            $variation->delete_meta_data('_ry_shipping_skip_shipping');
        }
    }

    public function add_shipping_info_action($order, $type)
    {
        foreach ($order->get_items('shipping') as $shipping_item) {
            $method_ID = $shipping_item->get_method_id();
            if (class_exists('RY_WT_WC_ECPay_Shipping') && isset(RY_WT_WC_ECPay_Shipping::$support_methods[$method_ID])) {
                $support_temp = RY_WT_WC_ECPay_Shipping::$support_methods[$method_ID]::get_support_temp();
            }
            if (class_exists('RY_WT_WC_NewebPay_Shipping') && isset(RY_WT_WC_NewebPay_Shipping::$support_methods[$method_ID])) {
                $support_temp = RY_WT_WC_NewebPay_Shipping::$support_methods[$method_ID]::get_support_temp();
            }
            if (class_exists('RY_WT_WC_SmilePay_Shipping') && isset(RY_WT_WC_SmilePay_Shipping::$support_methods[$method_ID])) {
                $support_temp = RY_WT_WC_SmilePay_Shipping::$support_methods[$method_ID]::get_support_temp();
            }
        }

        if (isset($support_temp)) {
            if (in_array('1', $support_temp)) {
                echo '<button type="button" class="button ry-' . esc_attr($type) . '-shipping-info" data-orderid="' . esc_attr($order->get_id()) . '" data-temp="1">' . esc_html__('Get shipping no (normal temperature)', 'ry-woocommerce-tools-pro') . '</button>';
            }

            if (in_array('2', $support_temp)) {
                echo '<button type="button" class="button ry-' . esc_attr($type) . '-shipping-info" data-orderid="' . esc_attr($order->get_id()) . '" data-temp="2">' . esc_html__('Get shipping no (refrigerated)', 'ry-woocommerce-tools-pro') . '</button>';
            }

            if (in_array('3', $support_temp)) {
                echo '<button type="button" class="button ry-' . esc_attr($type) . '-shipping-info" data-orderid="' . esc_attr($order->get_id()) . '" data-temp="3">' . esc_html__('Get shipping no (frozen)', 'ry-woocommerce-tools-pro') . '</button>';
            }
        }
    }

    protected function get_options_shipping_methods()
    {
        static $shipping_methods = [];

        if (count($shipping_methods) == 0) {
            $data_store = WC_Data_Store::load('shipping-zone');
            $raw_zones = $data_store->get_zones();
            $zones = [];
            foreach ($raw_zones as $raw_zone) {
                $zones[] = new WC_Shipping_Zone($raw_zone);
            }
            $zones[] = new WC_Shipping_Zone(0);
            foreach (WC()->shipping()->load_shipping_methods() as $method) {
                $shipping_methods[$method->get_method_title()] = [];

                // Translators: %1$s shipping method name.
                $shipping_methods[$method->get_method_title()][$method->id] = sprintf(__('Any &quot;%1$s&quot; method', 'ry-woocommerce-tools-pro'), $method->get_method_title());

                foreach ($zones as $zone) {
                    $zone_title = $zone->get_id() ? $zone->get_zone_name() : __('Other locations', 'ry-woocommerce-tools-pro');

                    foreach ($zone->get_shipping_methods() as $shipping_method_instance_id => $shipping_method_instance) {
                        if ($shipping_method_instance->id !== $method->id) {
                            continue;
                        }

                        $shipping_methods[$method->get_method_title()][$shipping_method_instance->get_rate_id()] = $zone_title . ' &ndash; '
                            . $shipping_method_instance->get_title() . ' (#' . $shipping_method_instance_id . ')';
                    }
                }
            }
        }

        return $shipping_methods;
    }
}
