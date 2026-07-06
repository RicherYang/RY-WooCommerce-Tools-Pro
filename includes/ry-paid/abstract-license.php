<?php

defined('ABSPATH') or exit;

if (!class_exists('RY_Abstract_License', false)) {
    abstract class RY_Abstract_License
    {
        public static string $main_class;

        protected bool $activated = false;

        abstract public function activate_key();

        public function get_license_key(): string
        {
            return static::$main_class::get_option('license_key', '');
        }

        public function set_license_key(string $key): void
        {
            static::$main_class::update_option('license_key', $key, false);
        }

        protected function get_license_data()
        {
            $data = static::$main_class::get_option('license_data', '');
            if ($data === '') {
                return $data;
            }

            return @unserialize(base64_decode($data, true));
        }

        public function set_license_data(array $data): void
        {
            unset($data['type']);
            $data['url'] = $this->get_site_url();
            $data = base64_encode(serialize($data));
            static::$main_class::update_option('license_data', $data, true);
        }

        public function delete_license(): void
        {
            static::$main_class::update_option('license_key', '', false);
            static::$main_class::update_option('license_data', '', false);
            static::$main_class::delete_transient('version_info');
            static::$main_class::delete_transient('expire_link_error');

            wp_unschedule_hook(static::$main_class::OPTION_PREFIX . 'check_expire');
        }

        public function check_expire_cron(): void
        {
            if (!wp_next_scheduled(static::$main_class::OPTION_PREFIX . 'check_expire')) {
                wp_schedule_event(time() + HOUR_IN_SECONDS, 'daily', static::$main_class::OPTION_PREFIX . 'check_expire');
            }
        }

        public function is_activated(): bool
        {
            return $this->activated;
        }

        public function get_expire(): string
        {
            $license_data = $this->get_license_data();
            if (is_array($license_data) && isset($license_data['expire'])) {
                return $license_data['expire'];
            }

            return '';
        }

        protected function get_site_url(): string
        {
            $url = str_replace(['http://', 'https://'], '', get_option('siteurl'));
            return rtrim($url, '/');
        }

        protected function valid_key(): void
        {
            $license_data = $this->get_license_data();
            if (!is_array($license_data)) {
                if ($license_data !== '') {
                    $this->valid_error(static::$main_class::PLUGIN_NAME . ': Data unknown. ' . var_export($license_data, true));
                }
                return;
            }

            if (!isset($license_data['secret'], $license_data['expire'], $license_data['url'])) {
                $this->valid_error(static::$main_class::PLUGIN_NAME . ': Data error. ' . var_export($license_data, true));
                return;
            }

            if (!hash_equals($license_data['secret'], hash('crc32', $license_data['expire']))) {
                $this->valid_error(static::$main_class::PLUGIN_NAME . ': Secret check error.');
                return;
            }

            if ($license_data['expire'] <= time() - DAY_IN_SECONDS) {
                $this->valid_error(static::$main_class::PLUGIN_NAME . ': Expire. ' . $license_data['expire'] . ' @ ' . time());
                return;
            }

            if ($this->get_site_url() !== $license_data['url']) {
                $this->valid_error(static::$main_class::PLUGIN_NAME . ': Site change. ' . $license_data['url'] . ' => ' . $this->get_site_url());
                return;
            }

            $this->activated = true;
        }

        protected function valid_error(string $message): void
        {
            $this->delete_license();
            RY_Logs::log('ry-license', 'error', $message);
        }
    }
}
