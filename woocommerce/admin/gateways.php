<?php

final class RY_WTP_WC_Admin_Gateways
{
    protected static $_instance = null;

    public static function instance(): RY_WTP_WC_Admin_Gateways
    {
        if (null === self::$_instance) {
            self::$_instance = new self();
            self::$_instance->do_init();
        }

        return self::$_instance;
    }

    protected function do_init(): void
    {
        add_filter('ry_admin_payment_info', [$this, 'show_payment_info'], 99, 2);

        add_action('wp_ajax_RY_payment_info', [$this, 'get_payment_info']);
    }

    public function show_payment_info($html, $order)
    {
        $payment_method = $order->get_payment_method();
        if (!str_starts_with($payment_method, 'ry_ecpay_')) {
            return $html;
        }

        add_action('admin_footer', [$this, 'payment_info_template']);
        wp_localize_script('ry-wtp-admin-order', 'RyInfo', [
            '_nonce' => [
                'get' => wp_create_nonce('get-payment-info'),
            ],
        ]);

        wp_enqueue_script('ry-wtp-admin-order');

        if (empty($html)) {
            $html = '<tr><td>';
        } else {
            $html = '<tr><td><table>' . $html . '</table></td><td>';
        }

        $html .= '<button id="ry_show_payment_info" type="button" class="button" data-orderid="' . esc_attr($order->get_id()) . '">' . esc_html__('Get info', 'ry-woocommerce-tools-pro') . '</button>
            </td></tr>';

        return $html;
    }

    public function get_payment_info()
    {
        check_ajax_referer('get-payment-info');

        $order_ID = (int) wp_unslash($_POST['orderid'] ?? 0);

        $order = wc_get_order($order_ID);
        if (!empty($order)) {
            $data = [
                'order_number' => $order->get_order_number(),
            ];
            $payment_method = $order->get_payment_method();
            if (str_starts_with($payment_method, 'ry_ecpay_')) {
                $info = RY_WT_WC_ECPay_Gateway_Api::instance()->get_info($order);
                ob_start();
                include RY_WTP_PLUGIN_DIR . 'woocommerce/admin/view/payment-info-ecpay.php';
                $data['info_html'] = ob_get_clean();
                $data['info'] = $info;
            }

            wp_send_json_success($data);
        }

        wp_die();
    }

    public function payment_info_template()
    {
        include RY_WTP_PLUGIN_DIR . 'woocommerce/admin/view/payment-info-template.php';
    }
}
