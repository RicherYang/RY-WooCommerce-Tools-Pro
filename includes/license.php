<?php

include_once RY_WTP_PLUGIN_DIR . 'includes/ry-global/abstract-license.php';

final class RY_WTP_License extends RY_Abstract_License
{
    public static $main_class = RY_WTP::class;

    protected static $_instance = null;

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
        $this->valid_key();
    }

    public function activate_key()
    {
        return RY_WTP_LinkServer::instance()->activate_key($this->get_license_key());
    }

    public function get_version_info()
    {
        $version_info = RY_WTP::get_transient('version_info');
        if (empty($version_info)) {
            $version_info = RY_WTP_LinkServer::instance()->check_version();
            if ($version_info) {
                RY_WTP::set_transient('version_info', $version_info, HOUR_IN_SECONDS);
            }
        }

        return $version_info;
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
            } else {
                if ($link_error <= 0) {
                    $link_error = 0;
                }
                $link_error += 1;
                RY_WTP::set_transient('expire_link_error', $link_error);
            }
        } else {
            $this->delete_license();
        }
    }
}
