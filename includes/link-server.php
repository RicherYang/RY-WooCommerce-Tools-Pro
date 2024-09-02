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
        wc_set_time_limit(30);

        $response = wp_remote_get($this->api_url . 'products/' . $this->plugin_type, [
            'timeout' => 15,
            'httpversion' => '1.1',
            'user-agent' => $this->get_user_agent(),
        ]);

        return $this->decode_response($response);
    }

    public function get_info()
    {
        wc_set_time_limit(30);

        $response = wp_remote_get($this->api_url . 'products/info/' . $this->plugin_type, [
            'timeout' => 15,
            'httpversion' => '1.1',
            'user-agent' => $this->get_user_agent(),
        ]);

        return $this->decode_response($response);
    }

    public function activate_key()
    {
        wc_set_time_limit(30);

        $response = wp_remote_post($this->api_url . 'license/activate/' . $this->plugin_type, [
            'timeout' => 15,
            'httpversion' => '1.1',
            'user-agent' => $this->get_user_agent(),
            'headers' => [
                'Content-Type' => 'application/json;charset=' . get_bloginfo('charset'),
            ],
            'body' => wp_json_encode([
                'license_key' => RY_WTP_License::instance()->get_license_key(),
                'domain' => get_option('siteurl'),
            ]),
        ]);

        return $this->decode_response($response);
    }

    public function expire_data()
    {
        wc_set_time_limit(30);

        $response = wp_remote_post($this->api_url . 'license/expire/' . $this->plugin_type, [
            'timeout' => 15,
            'httpversion' => '1.1',
            'user-agent' => $this->get_user_agent(),
            'headers' => [
                'Content-Type' => 'application/json;charset=' . get_bloginfo('charset'),
            ],
            'body' => wp_json_encode([
                'domain' => get_option('siteurl'),
            ]),
        ]);

        return $this->decode_response($response);
    }

    protected function get_user_agent()
    {
        return sprintf(
            'RY_WTP %s (WordPress/%s WooCommerce/%s)',
            RY_WTP_VERSION,
            get_bloginfo('version'),
            WC_VERSION,
        );
    }

    protected function decode_response($response)
    {
        if (is_wp_error($response)) {
            RY_WTP_License::instance()->log('POST failed', WC_Log_Levels::ERROR, ['info' => $response->get_error_messages()]);
            return false;
        }

        if (wp_remote_retrieve_response_code($response) != '200') {
            RY_WTP_License::instance()->log('POST HTTP status error', WC_Log_Levels::ERROR, ['code' => wp_remote_retrieve_response_code($response)]);
            return false;
        }

        $data = @json_decode(wp_remote_retrieve_body($response), true);
        if (empty($data)) {
            RY_WTP_License::instance()->log('POST result parse failed', WC_Log_Levels::ERROR, ['data' => wp_remote_retrieve_body($response)]);
            return false;
        }

        return $data;
    }
}
