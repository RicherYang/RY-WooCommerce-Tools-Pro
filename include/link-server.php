<?php
final class RY_WTP_LinkServer
{
    protected static $log = false;

    private static $api_url = 'https://ry-plugin.com/wp-json/ry/v2/';
    private static $plugin_type = 'ry-woocommerce-tools-pro';

    public static function check_version()
    {
        $response = wp_remote_get(self::$api_url . 'products/' . self::$plugin_type, [
            'timeout' => 3,
            'httpversion' => '1.1',
            'user-agent' => self::get_user_agent()
        ]);

        return self::decode_response($response);
    }

    public static function get_info()
    {
        $response = wp_remote_get(self::$api_url . 'products/info/' . self::$plugin_type, [
            'timeout' => 3,
            'httpversion' => '1.1',
            'user-agent' => self::get_user_agent()
        ]);

        return self::decode_response($response);
    }

    public static function activate_key()
    {
        $response = wp_remote_post(self::$api_url . 'license/activate/' . self::$plugin_type, [
            'timeout' => 10,
            'httpversion' => '1.1',
            'user-agent' => self::get_user_agent(),
            'headers' => [
                'Content-Type' => 'application/json;charset=' . get_bloginfo('charset'),
            ],
            'body' => wp_json_encode([
                'license_key' => RY_WTP_License::get_license_key(),
                'domain' => get_option('siteurl')
            ])
        ]);

        return self::decode_response($response);
    }

    public static function expire_data()
    {
        $response = wp_remote_post(self::$api_url . 'license/expire/' . self::$plugin_type, [
            'timeout' => 10,
            'httpversion' => '1.1',
            'user-agent' => self::get_user_agent(),
            'headers' => [
                'Content-Type' => 'application/json;charset=' . get_bloginfo('charset'),
            ],
            'body' => wp_json_encode([
                'domain' => get_option('siteurl')
            ])
        ]);

        return self::decode_response($response);
    }

    protected static function decode_response($response)
    {
        if (is_wp_error($response)) {
            RY_WTP_License::log('Error: ' . implode("\n", $response->get_error_messages()), 'error');
            return false;
        }

        if (200 != wp_remote_retrieve_response_code($response)) {
            RY_WTP_License::log('HTTP ' . wp_remote_retrieve_response_code($response) . ' @ ' . $response['http_response']->get_response_object()->url, 'error');
            return false;
        }

        $body = wp_remote_retrieve_body($response);
        $data = @json_decode($body, true);
        if (empty($data)) {
            RY_WTP_License::log('Data decode error. ' . var_export($body, true), 'error');
        }
        return $data;
    }

    protected static function get_user_agent()
    {
        return sprintf('RY_WTP %s (WordPress/%s WooCommerce/%s)', RY_WTP_VERSION, get_bloginfo('version'), WC_VERSION);
    }
}
