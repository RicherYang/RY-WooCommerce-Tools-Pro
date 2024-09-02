<?php

final class RY_WTP_WC_SmilePay_Gateway
{
    protected static $_instance = null;

    public static function instance(): RY_WTP_WC_SmilePay_Gateway
    {
        if (null === self::$_instance) {
            self::$_instance = new self();
            self::$_instance->do_init();
        }

        return self::$_instance;
    }

    protected function do_init(): void
    {
        include_once RY_WTP_PLUGIN_DIR . 'woocommerce/gateways/smilepay/gateway-block.php';
        RY_WTP_WC_SmilePay_Gateway_Block::instance();

        if (is_admin()) {
            include_once RY_WTP_PLUGIN_DIR . 'woocommerce/gateways/smilepay/includes/admin.php';
            RY_WTP_WC_SmilePay_Gateway_Admin::instance();
        }

        if ('yes' === RY_WTP::get_option('smilepay_email_payment_info', 'yes')) {
            add_action('woocommerce_email_after_order_table', [$this, 'add_payment_info'], 10, 4);
        }
    }

    public function add_payment_info($order, $sent_to_admin, $plain_text, $email)
    {
        if ($email->id == 'customer_on_hold_order') {
            switch ($order->get_payment_method()) {
                case 'ry_smilepay_atm':
                    $template_file = 'emails/email-order-smilepay-payment-info-atm.php';
                    break;
                case 'ry_smilepay_barcode':
                    $template_file = 'emails/email-order-smilepay-payment-info-barcode.php';
                    break;
                case 'ry_smilepay_cvs_711':
                    $template_file = 'emails/email-order-smilepay-payment-info-cvs-711.php';
                    break;
                case 'ry_smilepay_cvs_fami':
                    $template_file = 'emails/email-order-smilepay-payment-info-cvs-fami.php';
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
