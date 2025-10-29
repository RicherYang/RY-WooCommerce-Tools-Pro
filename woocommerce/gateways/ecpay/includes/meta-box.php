<?php

class RY_ECPay_Gateway_Meta_Box extends RY_WT_Meta_Box
{
    public static function add_meta_box($post_type, $data_object): void
    {
        if ('shop_order' === $post_type || 'woocommerce_page_wc-orders' === $post_type) {
            $order = self::get_order_object($data_object);
            $payment_method = $order->get_payment_method();

            if (str_starts_with($payment_method, 'ry_ecpay_credit')) {
                add_meta_box('ry-ecpay-gateway-refound', __('ECPay refound', 'ry-woocommerce-tools-pro'), [__CLASS__, 'output'], $post_type, 'side', 'core');
            }
        }
    }

    public static function output($data_object): void
    {
        $order = self::get_order_object($data_object);
        echo '<button id="ry-show-refound-info" type="button" class="button" data-orderid="' . esc_attr($order->get_id()) . '">' . esc_html__('Get credit payment info', 'ry-woocommerce-tools-pro') . '</button>';

        include RY_WTP_PLUGIN_DIR . 'woocommerce/admin/meta-boxes/view/ecpay-refound-info-template.php';
    }
}
