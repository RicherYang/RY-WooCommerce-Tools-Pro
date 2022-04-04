<?php
final class RY_WTP_admin
{
    private static $initiated = false;

    public static function init()
    {
        if (!self::$initiated) {
            self::$initiated = true;

            add_action('all_admin_notices', [__CLASS__, 'add_update_notice']);

            add_filter('woocommerce_get_sections_rytools', [__CLASS__, 'add_sections'], 11);
            add_filter('woocommerce_get_settings_rytools', [__CLASS__, 'add_setting'], 11, 2);
            add_action('ry_setting_section_ouput_tools', [__CLASS__, 'output_tools']);
            add_action('woocommerce_update_options_rytools_ry_key', [__CLASS__, 'activate_key']);

            add_filter('manage_shop_order_posts_columns', [__CLASS__, 'shop_order_columns'], 11);
            add_action('manage_shop_order_posts_custom_column', [__CLASS__, 'shop_order_column'], 11);

            wp_register_script('ry-pro-admin-shipping', RY_WTP_PLUGIN_URL . 'style/js/admin/ry_shipping.js', ['jquery'], RY_WTP_VERSION, true);
        }
    }

    public static function add_update_notice()
    {
        if (version_compare(RY_WT_VERSION, '1.9.0', '<')) {
            echo '<div class="notice notice-info is-dismissible"><p>'
                . '請更新 RY WooCommerce Tools 至版本 1.9.0 或更新的版本，以確保一切功能都可以正常運作。'
                . '</p></div>';
        }
    }

    public static function add_sections($sections)
    {
        unset($sections['pro_info']);
        unset($sections['ry_key']);
        $sections['ry_key'] = __('License key', 'ry-woocommerce-tools-pro');

        return $sections;
    }

    public static function add_setting($settings, $current_section)
    {
        if ($current_section == 'ry_key') {
            add_action('woocommerce_admin_field_rywct_version_info', [__CLASS__, 'show_version_info']);
            if (empty($settings)) {
                $settings = [];
            }
            $settings = array_merge($settings, include RY_WTP_PLUGIN_DIR . 'woocommerce/settings/settings-pro-version.php');

            $expire = RY_WTP_License::get_expire();
            if (!empty($expire)) {
                foreach ($settings as $key => $setting) {
                    if (isset($setting['id']) && $setting['id'] == RY_WTP::$option_prefix . 'license_key') {
                        $settings[$key]['desc'] = sprintf(
                            /* translators: %s: Expiration date of pro license */
                            __('License Expiration Date %s', 'ry-woocommerce-tools-pro'),
                            date_i18n(get_option('date_format'), $expire)
                        );
                        break;
                    }
                }
            }
        }
        return $settings;
    }

    public static function show_version_info()
    {
        $version = RY_WTP::get_option('version');
        $version_info = RY_WTP::get_transient('version_info');
        if (empty($version_info)) {
            $version_info = RY_WTP_LinkServer::check_version();
            if ($version_info) {
                RY_WTP::set_transient('version_info', $version_info, HOUR_IN_SECONDS);
            }
        }

        include RY_WTP_PLUGIN_DIR . 'woocommerce/admin/view/html-version-info.php';
    }

    public static function output_tools()
    {
        if (!empty($_POST['change_address'])) {
            if (is_plugin_active('ry-wc-city-select/ry-wc-city-select.php')) {
                self::change_user_address();
            }
        }

        include RY_WTP_PLUGIN_DIR . 'woocommerce/admin/view/html-setting-tools.php';
    }

    public static function activate_key()
    {
        if (!empty(RY_WTP_License::get_license_key())) {
            RY_WTP::delete_transient('version_info');
            $json = RY_WTP_LinkServer::activate_key();

            if ($json === false) {
                WC_Admin_Settings::add_error(__('RY WooCommerce Tools Pro', 'ry-woocommerce-tools-pro') . ': '
                    . __('Connect license server failed!', 'ry-woocommerce-tools-pro'));
            } else {
                if (is_array($json)) {
                    if (empty($json['data'])) {
                        RY_WTP_License::delete_license();
                        WC_Admin_Settings::add_error(__('RY WooCommerce Tools Pro', 'ry-woocommerce-tools-pro') . ': '
                            . sprintf(
                                /* translators: %s: Error message */
                                __('Verification error: %s', 'ry-woocommerce-tools-pro'),
                                __($json['error'], 'ry-woocommerce-tools-pro')
                            ));

                        /* Error message list. For make .pot */
                        __('Unknown key', 'ry-woocommerce-tools-pro');
                        __('Locked key', 'ry-woocommerce-tools-pro');
                        __('Unknown target url', 'ry-woocommerce-tools-pro');
                        __('Used key', 'ry-woocommerce-tools-pro');
                        __('Is tried', 'ry-woocommerce-tools-pro');
                    } else {
                        RY_WTP_License::set_license_data($json['data']);
                        return true;
                    }
                } else {
                    WC_Admin_Settings::add_error(__('RY WooCommerce Tools Pro', 'ry-woocommerce-tools-pro') . ': '
                    . __('Connect license server failed!', 'ry-woocommerce-tools-pro'));
                }
            }
        }

        RY_WTP_License::delete_license_key();
    }

    public static function shop_order_columns($columns)
    {
        $add_index = array_search('shipping_address', array_keys($columns)) + 1;
        $pre_array = array_splice($columns, 0, $add_index);
        $array = [
            'ry_shipping_no' => __('Shipping payment no', 'ry-woocommerce-tools')
        ];
        return array_merge($pre_array, $array, $columns);
    }

    public static function shop_order_column($column)
    {
        if ($column == 'ry_shipping_no') {
            global $the_order;
            $shipping_list = $the_order->get_meta('_ecpay_shipping_info', true);
            if (is_array($shipping_list)) {
                foreach ($shipping_list as $item) {
                    if ($item['LogisticsType'] == 'CVS') {
                        echo $item['PaymentNo'] . ' ' . $item['ValidationNo'] . '<br>';
                    }
                }
            }
        }
    }

    public static function change_user_address()
    {
        $states = WC()->countries->get_states('TW');
        $states_change = [];

        set_time_limit(60);
        foreach ($states as $code => $name) {
            $states_change[$name] = $code;
            if (strpos($name, '臺') !== false) {
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
}

RY_WTP_admin::init();
