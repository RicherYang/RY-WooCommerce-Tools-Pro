<?php

use Automattic\WooCommerce\Utilities\OrderUtil;

final class RY_WTP_WC_Admin_Order
{
    protected static $_instance = null;

    public static function instance(): RY_WTP_WC_Admin_Order
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
            if('edit' !== ($_GET['action'] ?? '')) {
                add_filter('manage_woocommerce_page_wc-orders_columns', [$this, 'shop_order_columns'], 11);
                add_action('manage_woocommerce_page_wc-orders_custom_column', [$this, 'shop_order_column'], 11, 2);
            }
        } else {
            add_filter('manage_shop_order_posts_columns', [$this, 'shop_order_columns'], 11);
            add_action('manage_shop_order_posts_custom_column', [$this, 'shop_order_column'], 11, 2);
        }
    }

    public function shop_order_columns($columns)
    {
        $add_index = array_search('shipping_address', array_keys($columns)) + 1;
        $pre_array = array_splice($columns, 0, $add_index);
        $array = [
            'ry_shipping_no' => __('Shipping payment no', 'ry-woocommerce-tools-pro'),
        ];

        return array_merge($pre_array, $array, $columns);
    }

    public function shop_order_column($column, $order)
    {
        if ('ry_shipping_no' == $column) {
            if(!is_object($order)) {
                global $the_order;
                $order = $the_order;
            }

            foreach(['_ecpay_shipping_info', '_newebpay_shipping_info', '_smilepay_shipping_info'] as $meta_key) {
                $shipping_list = $order->get_meta($meta_key, true);
                if (is_array($shipping_list)) {
                    foreach ($shipping_list as $item) {
                        if(!isset($item['LogisticsType'])) {
                            $item['LogisticsType'] = 'CVS';
                        }

                        if ('CVS' == $item['LogisticsType']) {
                            echo esc_html($item['PaymentNo']) . '<span class="validationno">' . esc_html($item['ValidationNo'] ?? '') . '<br>';
                        }
                        if ('HOME' == $item['LogisticsType']) {
                            echo esc_html($item['BookingNote']) . '<br>';
                        }
                    }
                }
            }
        }
    }
}
