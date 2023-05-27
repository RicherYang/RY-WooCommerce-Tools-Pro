<?php

final class RY_WTP_update
{
    public static function update()
    {
        $now_version = RY_WTP::get_option('version');

        if ($now_version == RY_WTP_VERSION) {
            return;
        }

        if (version_compare($now_version, '2.0.0', '<')) {
            RY_WTP::update_option('version', '2.0.0');
        }
    }
}
