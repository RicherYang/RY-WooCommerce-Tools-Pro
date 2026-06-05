<?php

defined('ABSPATH') or exit;

final class RY_WTP_WC_Admin_Meta_Box extends RY_WT_Meta_Box
{
    public static function add_meta_box($post_type, $data_object): void
    {
        if ('shop_order' === $post_type || 'woocommerce_page_wc-orders' === $post_type) {
            $order = self::get_order_object($data_object);
            $payment_method = $order->get_payment_method();
            $payment_gateways = WC()->payment_gateways()->payment_gateways();

            if (isset($payment_gateways[$payment_method])) {
                if ($payment_gateways[$payment_method]::SUPPORT_REFUNOD ?? false) {
                    add_meta_box('ry-gateway-refound', __('Refund through service provider', 'ry-woocommerce-tools-pro'), [__CLASS__, 'output'], $post_type, 'side', 'core');
                }
            }
        }
    }

    public static function output($data_object): void
    {
        $order = self::get_order_object($data_object);
        printf(
            '<button id="ry-show-refound-info" type="button" class="button" data-orderid="%1$s">%2$s</button>',
            esc_attr($order->get_id()),
            esc_html__('Get payment info', 'ry-woocommerce-tools-pro')
        );

        include RY_WTP_PLUGIN_DIR . 'woocommerce/admin/meta-boxes/view/refound-info-template.php';
    }
}
