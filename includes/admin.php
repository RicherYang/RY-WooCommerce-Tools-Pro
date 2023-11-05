<?php

final class RY_WTP_Admin
{
    protected static $_instance = null;

    public static function instance(): RY_WTP_Admin
    {
        if (null === self::$_instance) {
            self::$_instance = new self();
            self::$_instance->do_init();
        }

        return self::$_instance;
    }

    protected function do_init(): void
    {
        add_action('admin_notices', [$this, 'need_ry_woocommerce_tools']);
    }

    public function need_ry_woocommerce_tools(): void
    {
        if (!defined('RY_WT_VERSION') || version_compare(RY_WT_VERSION, RY_WTP::$min_Tools_version, '<')) {
            $message = sprintf(
                /* translators: %s: Name of this plugin %2$s: min require version */
                __('<strong>%1$s</strong> is inactive. It require RY WooCommerce Tools %2$s or newer.', 'ry-woocommerce-tools-pro'),
                'RY WooCommerce Tools Pro',
                RY_WTP::$min_Tools_version
            );
            printf('<div class="error"><p>%s</p></div>', $message);
        }
    }
}
