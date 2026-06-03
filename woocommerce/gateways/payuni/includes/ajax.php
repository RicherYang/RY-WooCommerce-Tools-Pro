<?php

defined('ABSPATH') or exit;

final class RY_WTP_WC_PAYUNi_Gateway_Admin_ajax
{
    protected static ?self $_instance = null;

    public static function instance(): RY_WTP_WC_PAYUNi_Gateway_Admin_ajax
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
        add_action('wp_ajax_RY_refound_info', [$this, 'get_refound_info']);
        add_action('wp_ajax_RY_refound_action', [$this, 'do_refound_action']);
    }

    public function get_payment_info()
    {
        check_ajax_referer('get-payment-info');

        $order_ID = intval($_POST['orderid'] ?? '');
        $order = wc_get_order($order_ID);
        if ($order) {
            $payment_method = $order->get_payment_method();
            if (str_starts_with($payment_method, 'ry_payuni_')) {
                $data = [
                    'order_number' => $order->get_order_number(),
                ];

                $info = RY_WT_WC_PAYUNi_Gateway_Api::instance()->get_info($order);
                if (empty($info)) {
                    $data['info_html'] = esc_html__('Can not get info from PAYUNi.', 'ry-woocommerce-tools-pro');
                } elseif ($info['Status'] != 'SUCCESS') {
                    $data['info_html'] = esc_html__('Unknow trade info.', 'ry-woocommerce-tools-pro');
                } else {
                    $info = $info['Result'][0];
                    ob_start();
                    include RY_WTP_PLUGIN_DIR . 'woocommerce/gateways/payuni/includes/view/payment-info.php';
                    $data['info_html'] = ob_get_clean();
                }

                wp_send_json_success($data);
            }
        }
    }

    public function get_refound_info()
    {
        check_ajax_referer('get-refound-info');

        $order_ID = intval($_POST['orderid'] ?? '');
        $order = wc_get_order($order_ID);
        if ($order) {
            $payment_method = $order->get_payment_method();
            if (str_starts_with($payment_method, 'ry_payuni_credit')) {
                $data = [];

                $info = RY_WT_WC_PAYUNi_Gateway_Api::instance()->get_info($order);
                if (empty($info) || $info['Status'] != 'SUCCESS') {
                    $data['info_html'] = __('Can not get payment detail.', 'ry-woocommerce-tools-pro');
                } else {
                    $info = $info['Result'][0];
                    ob_start();
                    include RY_WTP_PLUGIN_DIR . 'woocommerce/gateways/payuni/includes/view/refound-info.php';
                    $data['info_html'] = ob_get_clean();
                }

                wp_send_json_success($data);
            }
        }
    }

    public function do_refound_action()
    {
        check_ajax_referer('get-refound-info');

        $order_ID = intval($_POST['orderid'] ?? '');
        $order = wc_get_order($order_ID);
        if ($order) {
            $payment_method = $order->get_payment_method();
            if (str_starts_with($payment_method, 'ry_payuni_credit')) {
                $action_type = sanitize_key($_POST['refound'] ?? '');
                switch ($action_type) {
                    case 'cancel':
                        $result = RY_WT_WC_PAYUNi_Gateway_Api::instance()->credit_cancel($order);
                        break;
                    case 'closure':
                        $result = RY_WT_WC_PAYUNi_Gateway_Api::instance()->credit_close($order, 'C', ceil($order->get_total()));
                        break;
                    case 'refound':
                        $result = RY_WT_WC_PAYUNi_Gateway_Api::instance()->credit_close($order, 'R', intval($_POST['amount'] ?? 0));
                        break;
                }

                if (isset($result)) {
                    if ($result['Status'] === 'SUCCESS') {
                        switch ($action_type) {
                            case 'cancel':
                                $order->add_order_note(__('Cancel authorization completed', 'ry-woocommerce-tools-pro'));
                                break;
                            case 'closure':
                                $order->add_order_note(__('Closure completed', 'ry-woocommerce-tools-pro'));
                                break;
                            case 'refound':
                                /* translators: %d refound amount */
                                $order->add_order_note(sprintf(__('Refound %d completed', 'ry-woocommerce-tools-pro'), intval($_POST['amount'] ?? 0)));
                                break;
                        }
                    } else {
                        $order->add_order_note(sprintf(
                            /* translators: %1$s: status message, %2$d status code */
                            __('Refound action failed: %1$s (%2$d)', 'ry-woocommerce-tools-pro'),
                            $result['Message'],
                            $result['Status'],
                        ));
                    }
                }
            }
        }
    }
}
