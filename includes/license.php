<?php

final class RY_WTP_License extends RY_WT_Model
{
    protected static $_instance = null;

    protected $model_type = 'plguin_license';

    private $activated = false;

    public static function instance(): RY_WTP_License
    {
        if (null === self::$_instance) {
            self::$_instance = new self();
            self::$_instance->do_init();
        }

        return self::$_instance;
    }

    protected function do_init(): void
    {
        $this->log_enabled = true;

        $this->valid_key();
    }

    protected function valid_key(): void
    {
        $license_data = $this->get_license_data();
        if (!is_array($license_data)) {
            if (false !== $license_data) {
                $this->add_valid_error_log('WTP data error. ' . var_export($license_data, true)); // phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_var_export
                return;
            }
            $this->delete_license();
            $this->delete_license_key();
            return;
        }

        if (!isset($license_data['secret'], $license_data['expire'], $license_data['url'])) {
            $this->add_valid_error_log('WTP data error. ' . var_export($license_data, true)); // phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_var_export
            return;
        }

        if (!hash_equals($license_data['secret'], hash('crc32', $license_data['expire']))) {
            $this->add_valid_error_log('WTP secret check error.');
            return;
        }

        if ($license_data['expire'] <= time() - DAY_IN_SECONDS) {
            $this->add_valid_error_log('WTP expire. ' . $license_data['expire'] . ' @ ' . time());
            return;
        }

        if ($this->get_site_url() !== $license_data['url']) {
            $this->add_valid_error_log('WTP site change. ' . $license_data['url'] . ' => ' . $this->get_site_url());
            return;
        }

        $this->activated = true;
        if (! wp_next_scheduled(RY_WTP::OPTION_PREFIX . 'check_expire')) {
            wp_schedule_event(time() + HOUR_IN_SECONDS, 'daily', RY_WTP::OPTION_PREFIX . 'check_expire');
        }
    }

    public function is_activated(): bool
    {
        return $this->activated;
    }

    public function check_expire(): void
    {
        $json = RY_WTP_LinkServer::instance()->expire_data();
        if (is_array($json) && isset($json['data'])) {
            $this->set_license_data($json['data']);
            RY_WTP::delete_transient('expire_link_error');
        } elseif (false === $json) {
            $link_error = (int) RY_WTP::get_transient('expire_link_error');
            if ($link_error > 3) {
                $this->delete_license();
                $this->delete_license_key();
            } else {
                if ($link_error <= 0) {
                    $link_error = 0;
                }
                $link_error += 1;
                RY_WTP::set_transient('expire_link_error', $link_error);
            }
        } else {
            $this->delete_license();
            $this->delete_license_key();
        }
    }

    public function get_expire(): string
    {
        $license_data = $this->get_license_data();
        if (is_array($license_data) && isset($license_data['expire'])) {
            return $license_data['expire'];
        }
        return '';
    }

    public function get_license_key(): string
    {
        $license_key = RY_WTP::get_option('license_key', '');
        if (empty($license_key)) {
            $license_key = RY_WTP::get_option('pro_Key', '');
        }
        return $license_key;
    }

    public function set_license_data($data): void
    {
        unset($data['type']);
        $data['url'] = $this->get_site_url();
        $data = base64_encode(serialize($data));
        RY_WTP::update_option('license_data', $data);

        RY_WTP::delete_option('pro_Data');
        RY_WTP::delete_option('pro_Key');
    }

    public function delete_license(): void
    {
        RY_WTP::delete_option('license_data');
        RY_WTP::delete_transient('version_info');
        RY_WTP::delete_transient('expire_link_error');

        wp_unschedule_hook(RY_WTP::OPTION_PREFIX . 'check_expire');
    }

    public function delete_license_key(): void
    {
        RY_WTP::delete_option('license_key');
        RY_WTP::delete_option('pro_Data');
        RY_WTP::delete_option('pro_Key');
    }

    protected function get_license_data()
    {
        $license_data = RY_WTP::get_option('license_data');
        if (empty($license_data)) {
            $license_data = RY_WTP::get_option('pro_Data');
            if (!empty($license_data)) {
                unset($license_data['type']);
                $license_data['url'] = $this->get_site_url();
                $license_data = base64_encode(serialize($license_data));
                RY_WTP::update_option('license_data', $license_data);
            }
        } else {
            $license_data = maybe_unserialize(base64_decode($license_data));
        }

        $license_key = RY_WTP::get_option('license_key', '');
        if (empty($license_key)) {
            $license_key = RY_WTP::get_option('pro_Key', '');
            if (!empty($license_key)) {
                RY_WTP::update_option('license_key', $license_key);
            }
        }

        return $license_data;
    }

    private function get_site_url()
    {
        $url = str_replace(['http://', 'https://'], '', get_option('siteurl'));
        return rtrim($url, '/');
    }

    private function add_valid_error_log($message)
    {
        $this->delete_license();
        $this->delete_license_key();

        $this->log($message, WC_Log_Levels::ERROR);
        RY_WTP::update_option('license_auto_deactivate_date', current_time('Y-m-d'));
        RY_WTP_LicenseAutoDeactivate::add_note();
    }
}
