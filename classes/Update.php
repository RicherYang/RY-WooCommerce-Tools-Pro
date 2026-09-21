<?php

namespace RY\WooCommerce\Pro;

defined('ABSPATH') or exit;

final class Update
{
    public static function update()
    {
        $now_version = Main::get_option('version', '0.0.0');

        if (RY_WTP_VERSION === $now_version) {
            return;
        }

        if ($now_version === '0.0.0') {
            Main::update_option('version', RY_WTP_VERSION, true);
            return;
        }

        if (version_compare($now_version, '3.0.0', '<')) {
            wp_unschedule_hook(Main::get_prefix_name('check_update'));

            Main::update_option('version', '3.0.0', true);
        }

        if (version_compare($now_version, '3.8.0', '<')) {
            Main::update_option('ecpay_independent_digital', 'yes', true);

            $setting = Main::get_option('ecpay_credit_installment', false);
            if ($setting !== false) {
                Main::update_option('ecpay_independent_credit_installment', $setting, true);
                Main::delete_option('ecpay_credit_installment');
            }
            $setting = Main::get_option('ecpay_bnpl', false);
            if ($setting !== false) {
                Main::update_option('ecpay_independent_bnpl', $setting, true);
                Main::delete_option('ecpay_bnpl');
            }

            $setting = Main::get_option('newebpay_credit_installment', false);
            if ($setting !== false) {
                Main::update_option('newebpay_independent_credit_installment', $setting, true);
                Main::delete_option('newebpay_credit_installment');
            }
            $setting = Main::get_option('payuni_credit_installment', false);
            if ($setting !== false) {
                Main::update_option('payuni_independent_credit_installment', $setting, true);
                Main::delete_option('payuni_credit_installment');
            }

            Main::update_option('version', '3.8.0', true);
        }

        if (version_compare($now_version, '2026.8.12', '<')) {
            Main::update_option('version', '2026.8.12', true);
        }
    }
}
