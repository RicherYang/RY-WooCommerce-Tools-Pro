<?php
defined('RY_WTP_VERSION') or exit('No direct script access allowed');

final class RY_WTP_link_server
{
    private static $api_url = 'https://store.richer.tw/wp-admin/admin-ajax.php';
    private static $plugin_type = 'ry-woocommerce-tools-pro';

    public static function check_version()
    {
        $response = wp_remote_post(self::$api_url, [
            'timeout' => 10,
            'body' => [
                'action' => 'check_version',
                'type' => self::$plugin_type
            ]
        ]);

        if (!is_wp_error($response)) {
            if ($response['response']['code'] == '200' && isset($response['body'])) {
                return json_decode($response['body'], true);
            }
        }

        return false;
    }

    public static function activate_key()
    {
        $response = wp_remote_post(self::$api_url, [
            'timeout' => 10,
            'body' => [
                'action' => 'activate',
                'license_key' => RY_WTP::get_option('pro_Key'),
                'domain' => get_site_url(),
                'type' => self::$plugin_type
            ]
        ]);

        if (!is_wp_error($response)) {
            if ($response['response']['code'] == '200' && isset($response['body'])) {
                return json_decode($response['body'], true);
            }
        }

        return false;
    }

    public static function expire_data()
    {
        $response = wp_remote_post(self::$api_url, [
            'timeout' => 10,
            'body' => [
                'action' => 'expire_info',
                'domain' => get_site_url(),
                'type' => self::$plugin_type
            ]
        ]);

        if (!is_wp_error($response)) {
            if ($response['response']['code'] == '200' && isset($response['body'])) {
                return json_decode($response['body'], true);
            }
        }

        return false;
    }
}
