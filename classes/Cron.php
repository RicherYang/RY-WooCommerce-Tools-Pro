<?php

namespace RY\WooCommerce\Pro;

defined('ABSPATH') or exit;

final class Cron
{
    public static function add_action(): void
    {
        add_action(Main::get_prefix_name('check_expire'), [__CLASS__, 'check_expire']);
    }

    public static function check_expire(): void
    {
        License::instance()->check_expire();
    }
}
