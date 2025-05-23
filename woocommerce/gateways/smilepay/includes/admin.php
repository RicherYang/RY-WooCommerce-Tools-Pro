<?php

final class RY_WTP_WC_SmilePay_Gateway_Admin
{
    protected static $_instance = null;

    public static function instance(): RY_WTP_WC_SmilePay_Gateway_Admin
    {
        if (null === self::$_instance) {
            self::$_instance = new self();
            self::$_instance->do_init();
        }

        return self::$_instance;
    }

    protected function do_init(): void
    {
        add_filter('woocommerce_get_settings_rytools', [$this, 'add_setting'], 11, 2);
    }

    public function add_setting($settings, $current_section)
    {
        if ($current_section == 'smilepay_gateway') {
            $setting_idx = array_search('api_options', array_column($settings, 'id'));
            array_splice($settings, $setting_idx, 0, [
                [
                    'title' => __('Gateway options', 'ry-woocommerce-tools-pro'),
                    'id' => 'gateway_options',
                    'type' => 'title',
                ],
                [
                    'title' => __('Show payment info in email', 'ry-woocommerce-tools-pro'),
                    'id' => RY_WTP::OPTION_PREFIX . 'smilepay_email_payment_info',
                    'type' => 'checkbox',
                    'default' => 'yes',
                    'desc' => sprintf(
                        /* translators: %s: email title */
                        __('Add payment info in "%s" email.', 'ry-woocommerce-tools-pro'),
                        __('Order on-hold', 'ry-woocommerce-tools-pro'),
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
