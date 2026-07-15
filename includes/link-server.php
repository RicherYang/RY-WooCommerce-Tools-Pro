<?php

defined('ABSPATH') or exit;

include_once RY_WTP_PLUGIN_DIR . 'includes/ry-paid/abstract-link-server.php';

final class RY_WTP_LinkServer extends RY_Abstract_Link_Server
{
    protected static ?self $_instance = null;

    protected string $plugin_slug = 'ry-woocommerce-tools-pro';

    public static function instance(): RY_WTP_LinkServer
    {
        if (null === self::$_instance) {
            self::$_instance = new self();
        }

        return self::$_instance;
    }

    protected function get_base_info(): array
    {
        $info = [
            'plugin' => RY_WTP_VERSION,
            'php' => PHP_VERSION,
            'wp' => get_bloginfo('version'),
        ];
        if (defined('WC_VERSION')) {
            $info['wc'] = WC_VERSION;
        }
        if (defined('TUTOR_VERSION')) {
            $info['tt'] = TUTOR_VERSION;
        }

        return $info;
    }
}
