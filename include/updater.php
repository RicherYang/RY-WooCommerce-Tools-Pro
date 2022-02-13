<?php
final class RY_WTP_Updater
{
    private static $initiated = false;

    public static function init()
    {
        if (!self::$initiated) {
            self::$initiated = true;

            add_filter('pre_set_site_transient_update_plugins', [__CLASS__, 'transient_update_plugins']);
        }
    }

    public static function check_update()
    {
        $time = (int) get_site_transient(RY_WTP::$option_prefix . 'checktime');
        if (HOUR_IN_SECONDS < time() - $time) {
            $update_plugins = get_site_transient('update_plugins');
            set_site_transient('update_plugins', $update_plugins);
        }
    }

    public static function transient_update_plugins($transient)
    {
        $json = RY_WTP_LinkServer::check_version();

        if (is_array($json) && isset($json['new_version'])) {
            set_site_transient(RY_WTP::$option_prefix . 'checktime', time());

            if (version_compare(RY_WTP_VERSION, $json['new_version'], '<')) {
                unset($json['version']);
                unset($json['url']);
                $json['slug'] = 'ry-woocommerce-tools-pro';
                $json['plugin'] = RY_WTP_PLUGIN_BASENAME;

                if (empty($transient)) {
                    $transient = new stdClass;
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
}

RY_WTP_Updater::init();
