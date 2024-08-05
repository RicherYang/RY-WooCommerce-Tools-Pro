<?php

final class RY_WTP_Update
{
    public static function update()
    {
        $now_version = RY_WTP::get_option('version');

        if ($now_version == RY_WTP_VERSION) {
            return;
        }

        if (version_compare($now_version, '3.0.0', '<')) {
            wp_unschedule_hook(RY_WTP::OPTION_PREFIX . 'check_update');

            RY_WTP::update_option('version', '3.0.0');
        }

        if (version_compare($now_version, '3.4.6', '<')) {
            RY_WTP::update_option('version', '3.4.6', true);
        }
    }
}
