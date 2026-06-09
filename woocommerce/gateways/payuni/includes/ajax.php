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
        add_action('wp_ajax_RY_refund_info', [$this, 'get_refund_info']);
        add_action('wp_ajax_RY_refund_action', [$this, 'do_refund_action']);
    }

    public function get_payment_info()
    {
        check_ajax_referer('get-payment-info');

        $order = wc_get_order(intval($_POST['orderid'] ?? ''));
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

    public function get_refund_info()
    {
        check_ajax_referer('get-refund-info');

        $order = wc_get_order(intval($_POST['orderid'] ?? ''));
        if ($order) {
            $payment_method = $order->get_payment_method();
            if (str_starts_with($payment_method, 'ry_payuni_')) {
                $gateway_class_name = str_replace('ry_payuni_', 'RY_PAYUNi_Gateway_', $payment_method);
                if (class_exists($gateway_class_name) && $gateway_class_name::SUPPORT_REFUND) {
                    $data = [];

                    $info = RY_WT_WC_PAYUNi_Gateway_Api::instance()->get_info($order);
                    if (empty($info) || $info['Status'] != 'SUCCESS') {
                        $data['info_html'] = __('Can not get payment detail.', 'ry-woocommerce-tools-pro');
                    } else {
                        $info = $info['Result'][0];
                        ob_start();
                        if ($info['PaymentType'] === '1') {
                            include RY_WTP_PLUGIN_DIR . 'woocommerce/gateways/payuni/includes/view/refund-info-credit.php';
                        } else {
                            include RY_WTP_PLUGIN_DIR . 'woocommerce/gateways/payuni/includes/view/refund-info.php';
                        }
                        $data['info_html'] = ob_get_clean();
                    }

                    wp_send_json_success($data);
                }
            }
        }
    }

    public function do_refund_action()
    {
        check_ajax_referer('get-refund-info');

        $order = wc_get_order(intval($_POST['orderid'] ?? ''));
        if ($order) {
            $payment_method = $order->get_payment_method();
            if (str_starts_with($payment_method, 'ry_payuni_')) {
                $gateway_class_name = str_replace('ry_payuni_', 'RY_PAYUNi_Gateway_', $payment_method);
                if (class_exists($gateway_class_name) && $gateway_class_name::SUPPORT_REFUND) {
                    $action_type = sanitize_key($_POST['refund'] ?? '');
                    switch ($action_type) {
                        case 'cancel':
                            $result = RY_WT_WC_PAYUNi_Gateway_Api::instance()->credit_cancel($order);
                            break;
                        case 'closure':
                            $result = RY_WT_WC_PAYUNi_Gateway_Api::instance()->credit_close($order, 'C', ceil($order->get_total()));
                            break;
                        case 'refund':
                            $result = match ($gateway_class_name::PAYMENT_TYPE) {
                                'Credit' => RY_WT_WC_PAYUNi_Gateway_Api::instance()->credit_close($order, 'R', intval($_POST['amount'] ?? 0)),
                                'CreditInst' => RY_WT_WC_PAYUNi_Gateway_Api::instance()->credit_close($order, 'R', intval($_POST['amount'] ?? 0)),
                                'Aftee' => RY_WT_WC_PAYUNi_Gateway_Api::instance()->aftee_refund($order, intval($_POST['amount'] ?? 0)),
                                'ICash' => RY_WT_WC_PAYUNi_Gateway_Api::instance()->icash_refund($order, intval($_POST['amount'] ?? 0)),
                                'JKoPay' => RY_WT_WC_PAYUNi_Gateway_Api::instance()->jkopay_refund($order, intval($_POST['amount'] ?? 0)),
                                'LinePay' => RY_WT_WC_PAYUNi_Gateway_Api::instance()->linepay_refund($order, intval($_POST['amount'] ?? 0)),
                                default => null,
                            };

                            break;
                    }

                    if (isset($result) && is_array($result)) {
                        if ($result['Status'] === 'SUCCESS') {
                            switch ($action_type) {
                                case 'cancel':
                                    $order->add_order_note(__('Cancel authorization completed', 'ry-woocommerce-tools-pro'));
                                    break;
                                case 'closure':
                                    $order->add_order_note(__('Closure completed', 'ry-woocommerce-tools-pro'));
                                    break;
                                case 'refund':
                                    /* translators: %d refund amount */
                                    $order->add_order_note(sprintf(__('Refund %d completed', 'ry-woocommerce-tools-pro'), intval($_POST['amount'] ?? 0)));
                                    break;
                            }
                        } else {
                            $order->add_order_note(sprintf(
                                /* translators: %1$s: status message, %2$d status code */
                                __('Refund action failed: %1$s (%2$d)', 'ry-woocommerce-tools-pro'),
                                $result['Message'],
                                $result['Status'],
                            ));
                        }
                    }
                }
            }
        }
    }
}
