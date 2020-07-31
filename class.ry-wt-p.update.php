<?php
defined('RY_WTP_VERSION') or exit('No direct script access allowed');

final class RY_WTP_update
{
    public static function update()
    {
        $now_version = RY_WTP::get_option('version');

        if ($now_version === false) {
            $now_version = '0.0.0';
        }
        if ($now_version == RY_WTP_VERSION) {
            return;
        }

        if (version_compare($now_version, '1.2.2', '<')) {
            RY_WTP::update_option('version', '1.2.2');
        }
    }
}
