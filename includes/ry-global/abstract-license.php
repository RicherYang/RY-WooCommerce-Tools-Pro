<?php

if (class_exists('RY_Abstract_License')) {
    return;
}

abstract class RY_Abstract_License
{
    public static $main_class;

    protected $activated = false;

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

    public function set_license_data($data): void
    {
        unset($data['type']);
        $data['url'] = $this->get_site_url();
        $data = base64_encode(serialize($data));
        static::$main_class::update_option('license_data', $data, true);
    }

    public function delete_license(): void
    {
        static::$main_class::update_option('license_data', '', false);
        static::$main_class::update_option('license_key', '', false);
        static::$main_class::delete_transient('version_info');
        static::$main_class::delete_transient('expire_link_error');

        wp_unschedule_hook(static::$main_class::OPTION_PREFIX . 'check_expire');
    }

    public function check_expire_cron(): void
    {
        if (! wp_next_scheduled(static::$main_class::OPTION_PREFIX . 'check_expire')) {
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

    protected function get_site_url()
    {
        $url = str_replace(['http://', 'https://'], '', get_option('siteurl'));
        return rtrim($url, '/');
    }

    protected function valid_key(): void
    {
        $license_data = $this->get_license_data();
        if (!is_array($license_data)) {
            if ($license_data === '') {
                $this->delete_license();
            } else {
                $this->valid_error(static::$main_class::PLUGIN_NAME . ': Data unknown. ' . var_export($license_data, true)); // phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_var_export
            }
            return;
        }

        if (!isset($license_data['secret'], $license_data['expire'], $license_data['url'])) {
            $this->valid_error(static::$main_class::PLUGIN_NAME . ': Data error. ' . var_export($license_data, true)); // phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_var_export
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

    protected function valid_error($message)
    {
        $this->delete_license();
        $this->add_log('error', 'ry-license', $message);
    }

    public function get_logs(string $handle): array
    {
        $logs = [];
        $log_path = $this->get_log_file_path($handle, false);
        if (! file_exists($log_path)) {
            return $logs;
        }

        $log_contents = explode("\n", file_get_contents($log_path));
        foreach ($log_contents as $log_content) {
            preg_match('/([0-9\-]{10}T[0-9:]{8}\+[0-9:]{5}) \[([A-Z]+)\] (.*)/', $log_content, $log_array);
            if (count($log_array) == 4) {
                $logs[] = [
                    'date' => date_i18n('Y-m-d H:i:s', $log_array[1]),
                    'type' => $log_array[2],
                    'message' => $log_array[3],
                ];
            }
        }
        return $logs;
    }

    public function delete_log(string $handle): void
    {
        $log_path = $this->get_log_file_path($handle, false);
        @unlink($log_path);
    }

    public function add_log(string $type, string $handle, string $message): void
    {
        $log_path = $this->get_log_file_path($handle, true);
        if (! file_exists($log_path)) {
            @file_put_contents($log_path, '');
        }

        $add_message = current_time('c') . ' [' . strtoupper($type) . '] ' . $message . "\n";
        @file_put_contents($log_path, $add_message, FILE_APPEND);
    }

    protected function get_log_file_path(string $handle, bool $create): string
    {
        $log_path = trailingslashit(WP_CONTENT_DIR . '/ry-logs');
        if ($create) {
            if (! file_exists($log_path)) {
                $result = wp_mkdir_p($log_path);
                if (true === $result) {
                    @file_put_contents($log_path . '.htaccess', 'deny from all');
                    @file_put_contents($log_path . 'index.html', '');
                }
            }
        }
        $hash_suffix = wp_hash($handle);

        return $log_path . sanitize_file_name(implode('-', [$handle, $hash_suffix]) . '.log');
    }
}
