<?php

include_once RY_WTP_PLUGIN_DIR . 'includes/ry-global/abstract-admin.php';

final class RY_WTP_Admin extends RY_Abstract_Admin
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
        parent::do_init();

        $this->license = RY_WTP_License::instance();
        add_filter('ry-plugin/license_list', [$this, 'add_license']);

        if ($this->license->is_activated()) {
            $this->license->check_expire_cron();

            add_action('admin_notices', [$this, 'need_ry_woocommerce_tools']);
        }
    }

    public function add_license($license_list): array
    {
        $license_list[] = [
            'name' => $this->license::$main_class::PLUGIN_NAME,
            'license' => $this->license,
            'version' => RY_WTP_VERSION,
            'basename' => RY_WTP_PLUGIN_BASENAME,
        ];

        return $license_list;
    }

    public function need_ry_woocommerce_tools(): void
    {
        if (!defined('RY_WT_VERSION') || version_compare(RY_WT_VERSION, RY_WTP::MIN_TOOLS_VERSION, '<')) {
            $message = sprintf(
                /* translators: %1$s: Name of this plugin %2$s: Name of require plugin %3$s: min require version */
                __('<strong>%1$s</strong> is inactive. It require %2$s %3$s or newer.', 'ry-woocommerce-tools-pro'),
                $this->license::$main_class::PLUGIN_NAME,
                'RY Tools for WooCommerce',
                RY_WTP::MIN_TOOLS_VERSION,
            );
            printf('<div class="error"><p>%s</p></div>', wp_kses($message, ['strong' => []]));
        }

        if (defined('RY_WT::MIN_PRO_TOOLS_VERSION') && version_compare(RY_WTP_VERSION, RY_WT::MIN_PRO_TOOLS_VERSION, '<')) {
            $message = sprintf(
                /* translators: %1$s: Name of this plugin %2$s: Name of require plugin %3$s: min require version */
                __('<strong>%1$s</strong> is inactive. It require %2$s %3$s or newer.', 'ry-woocommerce-tools-pro'),
                $this->license::$main_class::PLUGIN_NAME,
                $this->license::$main_class::PLUGIN_NAME,
                RY_WT::MIN_PRO_TOOLS_VERSION,
            );
            printf('<div class="error"><p>%s</p></div>', wp_kses($message, ['strong' => []]));
        }
    }
}
