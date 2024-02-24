<?php

final class RY_WTP_Cron
{
    public static function add_action()
    {
        add_action(RY_WTP::OPTION_PREFIX . 'check_expire', [__CLASS__, 'check_expire']);
    }

    public static function check_expire()
    {
        RY_WTP_License::instance()->check_expire();
    }
}
