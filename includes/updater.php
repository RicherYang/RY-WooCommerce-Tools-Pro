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
        add_filter('pre_set_site_transient_update_plugins', [$this, 'transient_update_plugins']);

        add_filter('plugins_api', [$this, 'modify_plugin_details'], 10, 3);
    }

    public function transient_update_plugins($transient)
    {
        $json = RY_WTP_LinkServer::instance()->check_version();

        if (is_array($json) && isset($json['new_version'])) {
            if (version_compare(RY_WTP_VERSION, $json['new_version'], '<')) {
                unset($json['version']);
                unset($json['url']);
                $json['slug'] = 'ry-woocommerce-tools-pro';
                $json['plugin'] = RY_WTP_PLUGIN_BASENAME;

                if (empty($transient)) {
                    $transient = new stdClass();
                }
                $transient->last_checked = time();
                $transient->response[RY_WTP_PLUGIN_BASENAME] = (object) $json;
            } else {
                if (isset($transient->response)) {
                    unset($transient->response[RY_WTP_PLUGIN_BASENAME]);
                }
            }
        }

        return $transient;
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
