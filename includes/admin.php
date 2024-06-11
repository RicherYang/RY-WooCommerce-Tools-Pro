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
        if (!defined('RY_WT_VERSION') || version_compare(RY_WT_VERSION, RY_WTP::MIN_TOOLS_VERSION, '<')) {
            $message = sprintf(
                /* translators: %s: Name of this plugin %2$s: min require version */
                __('<strong>%1$s</strong> is inactive. It require RY Tools for WooCommerce %2$s or newer.', 'ry-woocommerce-tools-pro'),
                'RY Tools (Pro) for WooCommerce',
                RY_WTP::MIN_TOOLS_VERSION,
            );
            printf('<div class="error"><p>%s</p></div>', wp_kses($message, ['strong' => []]));
        }
    }
}
