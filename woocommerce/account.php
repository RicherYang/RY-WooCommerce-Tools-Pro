<?php

defined('ABSPATH') or exit;

final class RY_WTP_WC_Account
{
    private static ?self $_instance = null;

    public static function instance(): RY_WTP_WC_Account
    {
        if (null === self::$_instance) {
            self::$_instance = new self();
            self::$_instance->do_init();
        }

        return self::$_instance;
    }

    protected function do_init(): void
    {
        add_action('login_init', [$this, 'check_action_limit']);

        if ('yes' === RY_WTP::get_option('simple_captcha', 'no')) {
            add_action('woocommerce_register_form', [$this, 'add_register_field']);
            add_filter('woocommerce_registration_errors', [$this, 'check_registration_info'], 10, 3);
        }
    }

    public function check_action_limit()
    {
        if ('yes' === RY_WTP::get_option('register_from_woocommerce', 'no')) {
            add_filter('register_url', [$this, 'return_wc_account']);

            add_action('login_form_login', [$this, 'redirect_wc_account']);
            add_action('login_form_register', [$this, 'redirect_wc_account']);
            add_action('login_form_lostpassword', [$this, 'redirect_wc_account_lostpassword']);
            add_action('login_form_retrievepassword', [$this, 'redirect_wc_account_lostpassword']);
        }
    }

    public function return_wc_account()
    {
        return wc_get_page_permalink('myaccount');
    }

    public function redirect_wc_account(): void
    {
        wp_safe_redirect(wc_get_page_permalink('myaccount'));
        exit;
    }

    public function redirect_wc_account_lostpassword(): void
    {
        wp_safe_redirect(wc_get_account_endpoint_url('lost-password'));
        exit;
    }

    public function add_register_field()
    {
        $test_1 = random_int(1, 9);
        $test_2 = random_int(1, 9);
        $show_text = [
            1 => __('one', 'ry-woocommerce-tools-pro'),
            2 => __('two', 'ry-woocommerce-tools-pro'),
            3 => __('three', 'ry-woocommerce-tools-pro'),
            4 => __('four', 'ry-woocommerce-tools-pro'),
            5 => __('five', 'ry-woocommerce-tools-pro'),
            6 => __('six', 'ry-woocommerce-tools-pro'),
            7 => __('seven', 'ry-woocommerce-tools-pro'),
            8 => __('eight', 'ry-woocommerce-tools-pro'),
            9 => __('nine', 'ry-woocommerce-tools-pro'),
        ];
        $hold_string = sprintf(
            /* translators: %1$s: first number, %2$s: second number, such as one, two, three. */
            __('Please use numbers to enter the result of %1$s + %2$s.', 'ry-woocommerce-tools-pro'),
            $show_text[$test_1],
            $show_text[$test_2]
        );
        echo '<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
				<label for="ry_bot_check">' . esc_html__('bot check', 'ry-woocommerce-tools-pro') . '</label>
				<input type="text" class="woocommerce-Input woocommerce-Input--text input-text" name="ry-wtp-check" id="ry_bot_check" placeholder="' . esc_attr($hold_string) . '" />
			</p>';
        wp_nonce_field('ry-wtp-botcheck_' . ($test_1 + $test_2), 'ry-wtp-botcheck-nonce');
    }

    public function check_registration_info($errors, $username, $email)
    {
        if ($errors->has_errors()) {
            return $errors;
        }

        $check = intval($_POST['ry-wtp-check'] ?? '');
        $nonce_value = wp_unslash($_POST['ry-wtp-botcheck-nonce'] ?? '');
        if (!wp_verify_nonce($nonce_value, 'ry-wtp-botcheck_' . $check)) {
            $errors->add('botcheck', __('Bot check failed. Please make sure you enter the correct number.', 'ry-woocommerce-tools-pro'));
        }

        return $errors;
    }
}

RY_WTP_WC_Account::instance();
