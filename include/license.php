<?php
final class RY_WTP_License
{
    public static $log = false;

    public static function valid_key()
    {
        $license_data = self::get_license_data();
        if (is_array($license_data)) {
            if (isset($license_data['secret'], $license_data['expire'], $license_data['url'])) {
                if (hash_equals($license_data['secret'], hash('crc32', $license_data['expire']))) {
                    if ($license_data['expire'] > time() - DAY_IN_SECONDS) {
                        if (self::get_site_url() === $license_data['url']) {
                            return true;
                        } else {
                            self::log('WTP site change. ' . $license_data['url'] . ' => ' . self::get_site_url(), 'error');
                        }
                    } else {
                        self::log('WTP expire. ' . $license_data['expire'] . ' @ ' . time(), 'error');
                    }
                } else {
                    self::log('WTP secret check error.', 'error');
                }
            } else {
                self::log('WTP data error. ' . var_export($license_data, true), 'error');
            }
        } elseif ($license_data !== false) {
            self::log('WTP data error. ' . var_export($license_data, true), 'error');
        }

        self::delete_license();
        return false;
    }

    public static function check_expire()
    {
        $json = RY_WTP_LinkServer::expire_data();
        if (is_array($json) && isset($json['data'])) {
            self::set_license_data($json['data']);
            RY_WTP::delete_transient('expire_link_error');
        } elseif ($json === false) {
            wp_clear_scheduled_hook(RY_WTP::$option_prefix . 'check_expire');
            wp_schedule_event(time() + HOUR_IN_SECONDS, 'daily', RY_WTP::$option_prefix . 'check_expire');

            $link_error = (int) RY_WTP::get_transient('expire_link_error');
            if ($link_error > 3) {
                self::delete_license();
            } else {
                if ($link_error <= 0) {
                    $link_error = 0;
                }
                $link_error += 1;
                RY_WTP::set_transient('expire_link_error', $link_error);
            }
        } else {
            self::delete_license();
        }
    }

    public static function get_expire()
    {
        $license_data = self::get_license_data();
        if (is_array($license_data) && isset($license_data['expire'])) {
            return $license_data['expire'];
        }
        return '';
    }

    public static function get_license_key()
    {
        $license_key = RY_WTP::get_option('license_key');
        if (empty($license_key)) {
            $license_key = RY_WTP::get_option('pro_Key');
        }
        return $license_key;
    }

    public static function delete_license_key()
    {
        RY_WTP::delete_option('license_key');
        RY_WTP::delete_option('pro_Data');
        RY_WTP::delete_option('pro_Key');
    }

    public static function set_license_data($data)
    {
        unset($data['type']);
        $data['url'] = self::get_site_url();
        $data = base64_encode(serialize($data));
        RY_WTP::update_option('license_data', $data);

        RY_WTP::delete_option('pro_Data');
        RY_WTP::delete_option('pro_Key');
    }

    public static function delete_license()
    {
        RY_WTP::delete_option('license_data');
        RY_WTP::delete_transient('version_info');
        RY_WTP::delete_transient('expire_link_error');

        wp_unschedule_hook(RY_WTP::$option_prefix . 'check_expire');
        wp_unschedule_hook(RY_WTP::$option_prefix . 'check_update');
    }

    protected static function get_license_data()
    {
        $license_data = RY_WTP::get_option('license_data');
        if (empty($license_data)) {
            $license_data = RY_WTP::get_option('pro_Data');
            if (!empty($license_data)) {
                unset($license_data['type']);
                $license_data['url'] = self::get_site_url();
                $license_data = base64_encode(serialize($license_data));
                RY_WTP::update_option('license_data', $license_data);
            }
        } else {
            $license_data = maybe_unserialize(base64_decode($license_data));
        }

        $license_key = RY_WTP::get_option('license_key');
        if (empty($license_key)) {
            $license_key = RY_WTP::get_option('pro_Key');
            if (!empty($license_key)) {
                RY_WTP::update_option('license_key', $license_key);
            }
        }

        return $license_data;
    }

    private static function get_site_url()
    {
        $url = str_replace(['http://', 'https://'], '', get_option('siteurl'));
        return rtrim($url, '/');
    }

    public static function log($message, $level = 'info')
    {
        if (empty(self::$log)) {
            self::$log = wc_get_logger();
        }

        self::$log->log($level, $message, [
            'source' => 'ry_plguin_license',
            '_legacy' => true
        ]);
    }
}
