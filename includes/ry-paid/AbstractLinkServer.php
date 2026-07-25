<?php

namespace RY\Paid\V20260724;

defined('ABSPATH') or exit;

use RY\General\V20260724\AbstractLinkServer as GeneralAbstractLinkServer;

abstract class AbstractLinkServer extends GeneralAbstractLinkServer
{
    public function check_version()
    {
        @set_time_limit(30);

        $response = wp_remote_get($this->api_url . 'products/' . $this->plugin_slug, [
            'timeout' => 20,
            'httpversion' => '1.1',
        ]);

        return $this->decode_response($response);
    }

    public function get_info()
    {
        @set_time_limit(30);

        $response = wp_remote_get($this->api_url . 'products/info/' . $this->plugin_slug, [
            'timeout' => 20,
            'httpversion' => '1.1',
        ]);

        return $this->decode_response($response);
    }

    public function expire_data()
    {
        @set_time_limit(30);

        $response = wp_remote_post($this->api_url . 'license/expire/' . $this->plugin_slug, [
            'timeout' => 20,
            'httpversion' => '1.1',
            'headers' => [
                'Content-Type' => 'application/json;charset=' . get_bloginfo('charset'),
            ],
            'body' => wp_json_encode([
                'domain' => get_option('siteurl'),
                'base_info' => $this->get_base_info(),
            ]),
        ]);

        return $this->decode_response($response);
    }

    public function activate_key(string $key)
    {
        @set_time_limit(30);

        $response = wp_remote_post($this->api_url . 'license/activate/' . $this->plugin_slug, [
            'timeout' => 20,
            'httpversion' => '1.1',
            'headers' => [
                'Content-Type' => 'application/json;charset=' . get_bloginfo('charset'),
            ],
            'body' => wp_json_encode([
                'license_key' => $key,
                'domain' => get_option('siteurl'),
                'base_info' => $this->get_base_info(),
            ]),
        ]);

        return $this->decode_response($response);
    }
}
