<?php

if (!class_exists('RY_Abstract_Link_Server', false)) {
    abstract class RY_Abstract_Link_Server
    {
        protected $api_url = 'https://ry-plugin.com/wp-json/ry/v2/';

        protected $plugin_slug;

        protected function get_base_info(): array
        {
            return [];
        }

        public function check_version(): bool|array
        {
            @set_time_limit(30); // phpcs:ignore Squiz.PHP.DiscouragedFunctions.Discouraged

            $response = wp_remote_get($this->api_url . 'products/' . $this->plugin_slug, [
                'timeout' => 20,
                'httpversion' => '1.1',
            ]);

            return $this->decode_response($response);
        }

        public function get_info(): bool|array
        {
            @set_time_limit(30); // phpcs:ignore Squiz.PHP.DiscouragedFunctions.Discouraged

            $response = wp_remote_get($this->api_url . 'products/info/' . $this->plugin_slug, [
                'timeout' => 20,
                'httpversion' => '1.1',
            ]);

            return $this->decode_response($response);
        }

        public function expire_data(): bool|array
        {
            @set_time_limit(30); // phpcs:ignore Squiz.PHP.DiscouragedFunctions.Discouraged

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

        public function activate_key(string $key): bool|array
        {
            @set_time_limit(30); // phpcs:ignore Squiz.PHP.DiscouragedFunctions.Discouraged

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

        protected function decode_response($response): bool|array
        {
            if (is_wp_error($response)) {
                return false;
            }

            if (wp_remote_retrieve_response_code($response) != '200') {
                return false;
            }

            $data = json_decode(wp_remote_retrieve_body($response), true);
            if (empty($data)) {
                return false;
            }

            return $data;
        }
    }
}
