<?php

include_once RY_WTP_PLUGIN_DIR . 'includes/ry-global/abstract-link-server.php';

final class RY_WTP_LinkServer extends RY_Abstract_Link_Server
{
    protected static $_instance = null;

    protected $plugin_slug = 'ry-woocommerce-tools-pro';

    public static function instance(): RY_WTP_LinkServer
    {
        if (null === self::$_instance) {
            self::$_instance = new self();
        }

        return self::$_instance;
    }

    protected function get_base_info(): array
    {
        return [
            'plugin' => RY_WTP_VERSION,
            'wp' => get_bloginfo('version'),
            'wc' => WC_VERSION,
        ];
    }

    protected function get_user_agent()
    {
        return sprintf(
            'RY_WTP %s (WordPress/%s WooCommerce/%s)',
            RY_WTP_VERSION,
            get_bloginfo('version'),
            WC_VERSION,
        );
    }
}
