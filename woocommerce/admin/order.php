<?php
final class RY_WTP_admin_Order
{
    protected static $_instance = null;

    public static function instance()
    {
        if (self::$_instance === null) {
            self::$_instance = new self();
            self::$_instance->do_init();
        }

        return self::$_instance;
    }

    protected function do_init()
    {
        add_filter('manage_shop_order_posts_columns', [$this, 'shop_order_columns'], 11);
        add_action('manage_shop_order_posts_custom_column', [$this, 'shop_order_column'], 11);
    }

    public function shop_order_columns($columns)
    {
        $add_index = array_search('shipping_address', array_keys($columns)) + 1;
        $pre_array = array_splice($columns, 0, $add_index);
        $array = [
            'ry_shipping_no' => __('Shipping payment no', 'ry-woocommerce-tools-pro')
        ];
        return array_merge($pre_array, $array, $columns);
    }

    public function shop_order_column($column)
    {
        global $the_order;

        if ($column != 'ry_shipping_no') {
            return ;
        }

        $shipping_list = $the_order->get_meta('_ecpay_shipping_info', true);
        if (is_array($shipping_list)) {
            foreach ($shipping_list as $item) {
                if ($item['LogisticsType'] == 'CVS') {
                    echo $item['PaymentNo'] . ' ' . $item['ValidationNo'] . '<br>';
                }
            }
        }
    }
}

RY_WTP_admin_Order::instance();
