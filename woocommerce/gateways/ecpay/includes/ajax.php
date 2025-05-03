<?php

final class RY_WTP_WC_ECPay_Gateway_Admin_ajax
{
    protected static $_instance = null;

    public static function instance(): RY_WTP_WC_ECPay_Gateway_Admin_ajax
    {
        if (null === self::$_instance) {
            self::$_instance = new self();
            self::$_instance->do_init();
        }
        return self::$_instance;
    }

    protected function do_init(): void
    {
        add_action('wp_ajax_RY_payment_info', [$this, 'get_payment_info']);
    }

    public function get_payment_info()
    {
        check_ajax_referer('get-payment-info');

        $order_ID = (int) wp_unslash($_POST['orderid'] ?? ''); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized

        $order = wc_get_order($order_ID);
        if (!empty($order)) {
            $payment_method = $order->get_payment_method();
            if (str_starts_with($payment_method, 'ry_ecpay_')) {
                $data = [
                    'order_number' => $order->get_order_number(),
                ];

                $info = RY_WT_WC_ECPay_Gateway_Api::instance()->get_info($order);
                if (empty($info)) {
                    $data['info_html'] = esc_html__('Can not get info from ECPay.', 'ry-woocommerce-tools-pro');
                } elseif (!in_array($info['TradeStatus'], ['0', '1', '10200095'])) {
                    $data['info_html'] = esc_html__('Unknow trade info.', 'ry-woocommerce-tools-pro');
                } else {
                    ob_start();
                    include RY_WTP_PLUGIN_DIR . 'woocommerce/gateways/ecpay/includes/view/payment-info.php';
                    $data['info_html'] = ob_get_clean();
                }

                wp_send_json_success($data);
            }
        }
    }
}
