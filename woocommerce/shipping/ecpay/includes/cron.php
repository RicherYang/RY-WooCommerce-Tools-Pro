<?php

final class RY_WTP_ECPay_Shipping_Cron
{
    public static function add_action()
    {
        add_action('ry_wtp_get_ecpay_code', [RY_WT_WC_ECPay_Shipping_Api::instance(), 'get_code'], 10, 2); // keep for old cron hook
        add_action('ry_wtp_get_ecpay_shipping_code', [RY_WT_WC_ECPay_Shipping_Api::instance(), 'get_code'], 10, 2);

        add_action('ry_wtp_get_ecpay_shipping_no', [__CLASS__, 'get_shipping_no'], 10, 2);
    }

    public static function get_shipping_no($order_ID, $shipping_ID)
    {
        $order = wc_get_order($order_ID);
        if (!$order) {
            return;
        }

        $shipping_list = $order->get_meta('_ecpay_shipping_info', true);
        if (!is_array($shipping_list)) {
            return;
        }
        if (!isset($shipping_list[$shipping_ID])) {
            return;
        }
        if (!empty($shipping_list[$shipping_ID]['PaymentNo'])) {
            return;
        }

        $info = RY_WT_WC_ECPay_Shipping_Api::instance()->get_info($shipping_ID);
        if (empty($info)) {
            return;
        }

        $order = wc_get_order($order_ID);
        $shipping_list = $order->get_meta('_ecpay_shipping_info', true);
        $shipping_list[$shipping_ID]['PaymentNo'] = $info['ShipmentNo'];
        $order->update_meta_data('_ecpay_shipping_info', $shipping_list);
        $order->save();
    }
}
