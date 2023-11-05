<?php

final class RY_WTP_LinkServer
{
    protected static $_instance = null;

    private $api_url = 'https://ry-plugin.com/wp-json/ry/v2/';
    private $plugin_type = 'ry-woocommerce-tools-pro';

    public static function instance(): RY_WTP_LinkServer
    {
        if (null === self::$_instance) {
            self::$_instance = new self();
        }

        return self::$_instance;
    }

    public function check_version()
    {
        $response = wp_remote_get($this->api_url . 'products/' . $this->plugin_type, [
            'timeout' => 3,
            'httpversion' => '1.1',
            'user-agent' => $this->get_user_agent()
        ]);

        return $this->decode_response($response);
    }

    public function get_info()
    {
        $response = wp_remote_get($this->api_url . 'products/info/' . $this->plugin_type, [
            'timeout' => 3,
            'httpversion' => '1.1',
            'user-agent' => $this->get_user_agent()
        ]);

        return $this->decode_response($response);
    }

    public function activate_key()
    {
        $response = wp_remote_post($this->api_url . 'license/activate/' . $this->plugin_type, [
            'timeout' => 10,
            'httpversion' => '1.1',
            'user-agent' => $this->get_user_agent(),
            'headers' => [
                'Content-Type' => 'application/json;charset=' . get_bloginfo('charset'),
            ],
            'body' => wp_json_encode([
                'license_key' => RY_WTP_License::instance()->get_license_key(),
                'domain' => get_option('siteurl')
            ])
        ]);

        return $this->decode_response($response);
    }

    public function expire_data()
    {
        $response = wp_remote_post($this->api_url . 'license/expire/' . $this->plugin_type, [
            'timeout' => 5,
            'httpversion' => '1.1',
            'user-agent' => $this->get_user_agent(),
            'headers' => [
                'Content-Type' => 'application/json;charset=' . get_bloginfo('charset'),
            ],
            'body' => wp_json_encode([
                'domain' => get_option('siteurl')
            ])
        ]);

        return $this->decode_response($response);
    }

    protected function get_user_agent()
    {
        return sprintf('RY_WTP %s (WordPress/%s WooCommerce/%s)', RY_WTP_VERSION, get_bloginfo('version'), WC_VERSION);
    }

    protected function decode_response($response)
    {
        if (is_wp_error($response)) {
            RY_WTP_License::instance()->log('Error: ' . implode("\n", $response->get_error_messages()), 'error');
            return false;
        }

        $response_code = wp_remote_retrieve_response_code($response);
        if (200 != $response_code) {
            RY_WTP_License::instance()->log('HTTP ' . $response_code . ' @ ' . $response['http_response']->get_response_object()->url, 'error');
            return false;
        }

        $body = wp_remote_retrieve_body($response);
        $data = @json_decode($body, true);
        if (empty($data)) {
            RY_WTP_License::instance()->log('Data decode error. ' . var_export($body, true), 'error');
        }
        return $data;
    }
}
