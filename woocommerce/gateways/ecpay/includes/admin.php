<?php

final class RY_WTP_WC_ECPay_Gateway_Admin
{
    protected static $_instance = null;

    public static function instance(): RY_WTP_WC_ECPay_Gateway_Admin
    {
        if (null === self::$_instance) {
            self::$_instance = new self();
            self::$_instance->do_init();
        }
        return self::$_instance;
    }

    protected function do_init(): void
    {
        include_once RY_WTP_PLUGIN_DIR . 'woocommerce/gateways/ecpay/includes/ajax.php';

        RY_WTP_WC_ECPay_Gateway_Admin_ajax::instance();

        add_filter('woocommerce_get_settings_rytools', [$this, 'add_setting'], 11, 2);
    }

    public function add_setting($settings, $current_section)
    {
        if ($current_section == 'ecpay_gateway') {
            $setting_idx = array_search('api_options', array_column($settings, 'id'));
            array_splice($settings, $setting_idx, 0, [
                [
                    'title' => __('Gateway options', 'ry-woocommerce-tools-pro'),
                    'id' => 'gateway_options',
                    'type' => 'title',
                ],
                [
                    'title' => __('Credit installment', 'ry-woocommerce-tools-pro'),
                    'id' => RY_WTP::OPTION_PREFIX . 'ecpay_credit_installment',
                    'type' => 'checkbox',
                    'default' => 'no',
                    'desc' => __('Add each periods of credit installment as a payment gateway.', 'ry-woocommerce-tools-pro'),
                ],
                [
                    'title' => __('Show payment info in email', 'ry-woocommerce-tools-pro'),
                    'id' => RY_WTP::OPTION_PREFIX . 'ecpay_email_payment_info',
                    'type' => 'checkbox',
                    'default' => 'yes',
                    'desc' => sprintf(
                        /* translators: %s: email title */
                        __('Add payment info in "%s" email.', 'ry-woocommerce-tools-pro'),
                        __('Order on-hold', 'woocommerce'),
                    ),
                ],
                [
                    'id' => 'gateway_options',
                    'type' => 'sectionend',
                ],
            ]);
        }
        return $settings;
    }
}
