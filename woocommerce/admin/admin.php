<?php

final class RY_WTP_WC_Admin
{
    protected static $_instance = null;

    public static function instance(): RY_WTP_WC_Admin
    {
        if (null === self::$_instance) {
            self::$_instance = new self();
            self::$_instance->do_init();
        }

        return self::$_instance;
    }

    protected function do_init(): void
    {
        add_action('woocommerce_settings_start', [$this, 'add_license_notice']);
        add_action('admin_enqueue_scripts', [$this, 'add_scripts']);

        add_filter('woocommerce_get_sections_rytools', [$this, 'add_sections'], 12);
        add_filter('woocommerce_get_settings_rytools', [$this, 'add_setting'], 11, 2);
        add_action('ry_setting_section_ouput_tools', [$this, 'output_tools']);

        add_action('woocommerce_update_options_rytools_ry_key', [$this, 'activate_key']);

        add_action('woocommerce_admin_order_data_after_payment_info', [$this, 'load_script']);
    }

    public function add_license_notice(): void
    {
        global $current_section, $current_tab;

        if ('rytools' === $current_tab && 'ry_key' === $current_section) {
            return;
        }

        if (!RY_WTP_License::instance()->is_activated()) {
            echo '<div class="notice notice-info"><p><strong>RY Tools (Pro) for WooCommerce</strong> ' . esc_html__('Your license is not active!', 'ry-woocommerce-tools-pro') . '</p></div>';
        }
    }

    public function add_scripts()
    {
        $asset_info = include RY_WTP_PLUGIN_DIR . 'assets/admin/order.asset.php';
        wp_register_script('ry-wtp-admin-order', RY_WTP_PLUGIN_URL . 'assets/admin/order.js', $asset_info['dependencies'], $asset_info['version'], true);

        $asset_info = include RY_WTP_PLUGIN_DIR . 'assets/admin/setting.asset.php';
        wp_register_script('ry-wtp-admin-setting', RY_WTP_PLUGIN_URL . 'assets/admin/setting.js', $asset_info['dependencies'], $asset_info['version'], true);
    }

    public function add_sections($sections)
    {
        unset($sections['pro_info']);
        unset($sections['ry_key']);
        $sections['ry_key'] = __('License key', 'ry-woocommerce-tools-pro');

        return $sections;
    }

    public function add_setting($settings, $current_section)
    {
        if ('ry_key' === $current_section) {
            add_action('woocommerce_admin_field_ry_wtp_version_info', [$this, 'show_version_info']);
            if (empty($settings)) {
                $settings = [];
            }
            $settings = array_merge($settings, include RY_WTP_PLUGIN_DIR . 'woocommerce/admin/settings/pro-info.php');

            $expire = RY_WTP_License::instance()->get_expire();
            if (!empty($expire)) {
                $setting_idx = array_search(RY_WTP::OPTION_PREFIX . 'license_key', array_column($settings, 'id'));
                $settings[$setting_idx]['desc'] = sprintf(
                    /* translators: %s: Expiration date of pro license */
                    __('License Expiration Date %s', 'ry-woocommerce-tools-pro'),
                    date_i18n(get_option('date_format'), $expire),
                );
            }
        }
        return $settings;
    }

    public function show_version_info()
    {
        $version = RY_WTP::get_option('version');
        $version_info = RY_WTP::get_transient('version_info');
        if (empty($version_info)) {
            $version_info = RY_WTP_LinkServer::instance()->check_version();
            if ($version_info) {
                RY_WTP::set_transient('version_info', $version_info, HOUR_IN_SECONDS);
            }
        }

        include RY_WTP_PLUGIN_DIR . 'woocommerce/admin/settings/html/version-info.php';
    }

    public function output_tools()
    {
        if (isset($_POST['change_address']) && 'change_address' === $_POST['change_address']) { // phpcs:ignore WordPress.Security.NonceVerification.Missing
            if (is_plugin_active('ry-wc-city-select/ry-wc-city-select.php')) {
                $this->change_user_address();
            }
        }

        include RY_WTP_PLUGIN_DIR . 'woocommerce/admin/settings/html/tools.php';
    }

    public function activate_key()
    {
        if (!empty(RY_WTP_License::instance()->get_license_key())) {
            RY_WTP::delete_transient('version_info');
            $json = RY_WTP_LinkServer::instance()->activate_key();

            if (false === $json) {
                WC_Admin_Settings::add_error('RY Tools (Pro) for WooCommerce: ' . __('Connect license server failed!', 'ry-woocommerce-tools-pro'));
            } else {
                if (is_array($json)) {
                    if (empty($json['data'])) {
                        RY_WTP_License::instance()->delete_license();
                        WC_Admin_Settings::add_error('RY Tools (Pro) for WooCommerce: '
                            . sprintf(
                                /* translators: %s: Error message */
                                __('Verification error: %s', 'ry-woocommerce-tools-pro'),
                                rywtp_link_error_to_msg($json['error']),
                            ));
                    } else {
                        RY_WTP_License::instance()->set_license_data($json['data']);
                        return true;
                    }
                } else {
                    WC_Admin_Settings::add_error('RY Tools (Pro) for WooCommerce: ' . __('Connect license server failed!', 'ry-woocommerce-tools-pro'));
                }
            }
        }

        RY_WTP_License::instance()->delete_license_key();
    }

    protected function change_user_address()
    {
        $states = WC()->countries->get_states('TW');
        $states_change = [];

        @set_time_limit(60); // phpcs:ignore Squiz.PHP.DiscouragedFunctions.Discouraged
        foreach ($states as $code => $name) {
            $states_change[$name] = $code;
            if (str_contains($name, '臺')) {
                $name = str_replace('臺', '台', $name);
                $states_change[$name] = $code;
            }
        }

        foreach (get_users() as $user) {
            $customer = new WC_Customer($user->ID);
            $state = $customer->get_billing_state();
            if (isset($states_change[$state])) {
                $customer->set_billing_state($states_change[$state]);
            }
            $state = $customer->get_shipping_state();
            if (isset($states_change[$state])) {
                $customer->set_shipping_state($states_change[$state]);
            }
            $customer->save_data();
        }
    }

    public function load_script()
    {
        wp_localize_script('ry-wtp-admin-order', 'RyInfo', [
            '_nonce' => [
                'payment' => wp_create_nonce('get-payment-info'),
                'shipping' => wp_create_nonce('get-shipping-info'),
            ],
        ]);

        wp_enqueue_script('ry-wtp-admin-order');
    }
}
