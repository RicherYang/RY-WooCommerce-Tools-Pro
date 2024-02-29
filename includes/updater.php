<?php

final class RY_WTP_Updater
{
    protected static $_instance = null;

    public static function instance(): RY_WTP_Updater
    {
        if (null === self::$_instance) {
            self::$_instance = new self();
            self::$_instance->do_init();
        }

        return self::$_instance;
    }

    protected function do_init(): void
    {
        add_filter('update_plugins_ry-plugin.com', [$this, 'update_plugin'], 10, 4);

        add_filter('plugins_api', [$this, 'modify_plugin_details'], 10, 3);
    }

    public function update_plugin($update, $plugin_data, $plugin_file, $locales)
    {
        if('RY Tools (Pro) for WooCommerce' !== $plugin_data['Name']) {
            return $update;
        }

        $update = RY_WTP_LinkServer::instance()->check_version();
        if(is_array($update)) {
            $update['id'] = 'https://ry-plugin.com/ry-woocommerce-tools-pro';
            $update['url'] = 'https://ry-plugin.com/ry-woocommerce-tools-pro';
            $update['slug'] = 'ry-woocommerce-tools-pro';
        }

        return $update;
    }

    public function modify_plugin_details($result, $action, $args)
    {
        if ('plugin_information' !== $action) {
            return $result;
        }

        if ('ry-woocommerce-tools-pro' !== $args->slug) {
            return $result;
        }

        $response = RY_WTP_LinkServer::instance()->get_info();
        if (!empty($response)) {
            return (object) $response;
        }

        return $result;
    }
}
