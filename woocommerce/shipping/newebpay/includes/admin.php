<?php

final class RY_WTP_NewebPay_Shipping_Admin
{
    protected static $_instance = null;

    public static function instance(): RY_WTP_NewebPay_Shipping_Admin
    {
        if (null === self::$_instance) {
            self::$_instance = new self();
            self::$_instance->do_init();
        }
        return self::$_instance;
    }

    protected function do_init(): void
    {
        add_filter('woocommerce_get_settings_rytools', [$this, 'add_setting'], 11, 3);
    }

    public function add_setting($settings, $current_section, $checkout_with_block)
    {
        if ($current_section == 'newebpay_shipping') {
            if (!$checkout_with_block) {
                $setting_idx = array_search('base_options', array_column($settings, 'id'));
                array_splice($settings, $setting_idx + 1, 0, [
                    [
                        'title' => __('cvs remove billing address', 'ry-woocommerce-tools-pro'),
                        'id' => RY_WTP::OPTION_PREFIX . 'newebpay_cvs_billing_address',
                        'type' => 'checkbox',
                        'default' => 'no',
                        'desc' => __('Remove billing address when shipping mode is cvs.', 'ry-woocommerce-tools-pro')
                            . '<p class="description" style="margin-bottom:2px">' . __('The billing address still will show in order details.', 'ry-woocommerce-tools-pro') . '</p>',
                    ],
                ]);
            }
        }
        return $settings;
    }
}
