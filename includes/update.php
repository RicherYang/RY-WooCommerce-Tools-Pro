<?php

defined('ABSPATH') or exit;

final class RY_WTP_Update
{
    public static function update()
    {
        $now_version = RY_WTP::get_option('version', '0.0.0');

        if (RY_WTP_VERSION === $now_version) {
            return;
        }

        if ($now_version === '0.0.0') {
            RY_WTP::update_option('version', RY_WTP_VERSION, true);
            return;
        }

        if (version_compare($now_version, '3.0.0', '<')) {
            wp_unschedule_hook(RY_WTP::OPTION_PREFIX . 'check_update');

            RY_WTP::update_option('version', '3.0.0', true);
        }

        if (version_compare($now_version, '3.8.0', '<')) {
            RY_WTP::update_option('ecpay_independent_digital', 'yes', true);

            $setting = RY_WTP::get_option('ecpay_credit_installment', false);
            if ($setting !== false) {
                RY_WTP::update_option('ecpay_independent_credit_installment', $setting, true);
                RY_WTP::delete_option('ecpay_credit_installment');
            }
            $setting = RY_WTP::get_option('ecpay_bnpl', false);
            if ($setting !== false) {
                RY_WTP::update_option('ecpay_independent_bnpl', $setting, true);
                RY_WTP::delete_option('ecpay_bnpl');
            }

            $setting = RY_WTP::get_option('newebpay_credit_installment', false);
            if ($setting !== false) {
                RY_WTP::update_option('newebpay_independent_credit_installment', $setting, true);
                RY_WTP::delete_option('newebpay_credit_installment');
            }
            $setting = RY_WTP::get_option('payuni_credit_installment', false);
            if ($setting !== false) {
                RY_WTP::update_option('payuni_independent_credit_installment', $setting, true);
                RY_WTP::delete_option('payuni_credit_installment');
            }

            RY_WTP::update_option('version', '3.8.0', true);
        }

        if (version_compare($now_version, '2026.7.18', '<')) {
            RY_WTP::update_option('version', '2026.7.18', true);
        }
    }
}
