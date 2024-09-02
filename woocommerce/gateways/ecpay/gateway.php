<?php

final class RY_WTP_WC_ECPay_Gateway
{
    protected static $_instance = null;

    public static function instance(): RY_WTP_WC_ECPay_Gateway
    {
        if (null === self::$_instance) {
            self::$_instance = new self();
            self::$_instance->do_init();
        }

        return self::$_instance;
    }

    protected function do_init(): void
    {
        include_once RY_WTP_PLUGIN_DIR . 'woocommerce/gateways/ecpay/includes/gateway-credit-installment.php';
        include_once RY_WTP_PLUGIN_DIR . 'woocommerce/gateways/ecpay/gateway-bnpl.php';
        include_once RY_WTP_PLUGIN_DIR . 'woocommerce/gateways/ecpay/gateway-credit-installment-3.php';
        include_once RY_WTP_PLUGIN_DIR . 'woocommerce/gateways/ecpay/gateway-credit-installment-6.php';
        include_once RY_WTP_PLUGIN_DIR . 'woocommerce/gateways/ecpay/gateway-credit-installment-12.php';
        include_once RY_WTP_PLUGIN_DIR . 'woocommerce/gateways/ecpay/gateway-credit-installment-18.php';
        include_once RY_WTP_PLUGIN_DIR . 'woocommerce/gateways/ecpay/gateway-credit-installment-24.php';

        include_once RY_WTP_PLUGIN_DIR . 'woocommerce/gateways/ecpay/gateway-block.php';
        RY_WTP_WC_ECPay_Gateway_Block::instance();

        if (is_admin()) {
            include_once RY_WTP_PLUGIN_DIR . 'woocommerce/gateways/ecpay/includes/admin.php';
            RY_WTP_WC_ECPay_Gateway_Admin::instance();
        }

        add_filter('woocommerce_payment_gateways', [$this, 'add_method']);

        if ('yes' === RY_WTP::get_option('ecpay_email_payment_info', 'yes')) {
            add_action('woocommerce_email_after_order_table', [$this, 'add_payment_info'], 10, 4);
        }
    }

    public function add_method($methods)
    {
        $methods[] = 'RY_ECPay_Gateway_Bnpl';

        if ('yes' === RY_WTP::get_option('ecpay_credit_installment', 'no')) {
            $methods[] = 'RY_ECPay_Gateway_Credit_Installment_3';
            $methods[] = 'RY_ECPay_Gateway_Credit_Installment_6';
            $methods[] = 'RY_ECPay_Gateway_Credit_Installment_12';
            $methods[] = 'RY_ECPay_Gateway_Credit_Installment_18';
            $methods[] = 'RY_ECPay_Gateway_Credit_Installment_24';
        }

        return $methods;
    }

    public function add_payment_info($order, $sent_to_admin, $plain_text, $email)
    {
        if ($email->id == 'customer_on_hold_order') {
            switch ($order->get_payment_method()) {
                case 'ry_ecpay_atm':
                    $template_file = 'emails/email-order-ecpay-payment-info-atm.php';
                    break;
                case 'ry_ecpay_barcode':
                    $template_file = 'emails/email-order-ecpay-payment-info-barcode.php';
                    break;
                case 'ry_ecpay_cvs':
                    $template_file = 'emails/email-order-ecpay-payment-info-cvs.php';
                    break;
            }

            if (isset($template_file)) {
                if ($plain_text) {
                    wc_get_template(
                        str_replace('emails/', 'emails/plain/', $template_file),
                        [
                            'order' => $order,
                            'sent_to_admin' => $sent_to_admin,
                            'plain_text' => $plain_text,
                            'email' => $email,
                        ],
                        '',
                        RY_WTP_PLUGIN_DIR . 'templates/',
                    );
                } else {
                    wc_get_template(
                        $template_file,
                        [
                            'order' => $order,
                            'sent_to_admin' => $sent_to_admin,
                            'plain_text' => $plain_text,
                            'email' => $email,
                        ],
                        '',
                        RY_WTP_PLUGIN_DIR . 'templates/',
                    );
                }
            }
        }
    }
}
