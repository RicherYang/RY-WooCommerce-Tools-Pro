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
            add_filter('woocommerce_get_settings_rytools', [$this, 'add_setting'], 11, 2);
        }

        if ('yes' === RY_WTP::get_option('smilepay_email_payment_info', 'yes')) {
            add_action('woocommerce_email_after_order_table', [$this, 'add_payment_info'], 10, 4);
        }
    }

    public function add_setting($settings, $current_section)
    {
        if ($current_section == 'smilepay_gateway') {
            $setting_idx = array_search('api_options', array_column($settings, 'id'));
            array_splice($settings, $setting_idx, 0, [
                [
                    'title' => __('Gateway options', 'ry-woocommerce-tools-pro'),
                    'id' => 'gateway_options',
                    'type' => 'title'
                ],
                [
                    'title' => __('Show payment info in email', 'ry-woocommerce-tools-pro'),
                    'id' => RY_WTP::OPTION_PREFIX . 'smilepay_email_payment_info',
                    'type' => 'checkbox',
                    'default' => 'yes',
                    'desc' => sprintf(
                        /* translators: %s: email title */
                        __('Add payment info in "%s" email.', 'ry-woocommerce-tools-pro'),
                        __('Order on-hold', 'woocommerce')
                    )
                ],
                [
                    'id' => 'gateway_options',
                    'type' => 'sectionend'
                ]
            ]);
        }
        return $settings;
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
                        array(
                            'order' => $order,
                            'sent_to_admin' => $sent_to_admin,
                            'plain_text' => $plain_text,
                            'email' => $email,
                        ),
                        '',
                        RY_WTP_PLUGIN_DIR . 'templates/'
                    );
                } else {
                    wc_get_template(
                        $template_file,
                        array(
                            'order' => $order,
                            'sent_to_admin' => $sent_to_admin,
                            'plain_text' => $plain_text,
                            'email' => $email,
                        ),
                        '',
                        RY_WTP_PLUGIN_DIR . 'templates/'
                    );
                }
            }
        }
    }
}
