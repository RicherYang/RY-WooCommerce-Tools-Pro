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
        add_action('admin_enqueue_scripts', [$this, 'add_scripts']);

        add_filter('woocommerce_get_sections_rytools', [$this, 'add_sections'], 12);
        add_action('ry_setting_section_ouput_tools', [$this, 'output_tools']);

        add_action('woocommerce_admin_order_data_after_payment_info', [$this, 'load_script']);
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

        return $sections;
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
