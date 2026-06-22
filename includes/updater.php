<?php

defined('ABSPATH') or exit;

final class RY_WTP_Updater
{
    protected static ?self $_instance = null;

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
        add_filter('update_plugins_ry-plugin.com', [$this, 'update_plugin'], 10, 2);
        add_filter('pre_set_site_transient_update_plugins', [$this, 'check_basic_plugin']);

        add_filter('plugins_api', [$this, 'modify_plugin_details'], 10, 3);
    }

    public function update_plugin($update, $plugin_data)
    {
        if ('RY Tools (Pro) for WooCommerce' !== $plugin_data['Name']) {
            return $update;
        }

        return RY_WTP_LinkServer::instance()->check_version();
    }

    public function check_basic_plugin($updates)
    {
        if (defined('RY_WT_VERSION')) {
            if (isset($updates->response, $updates->response[RY_WTP_PLUGIN_BASENAME])) {
                return $this->get_official_info($updates);
            }

            if (version_compare(RY_WT_VERSION, RY_WTP::MIN_TOOLS_VERSION, '<')) {
                return $this->get_official_info($updates);
            }
        }

        return $updates;
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

    protected function get_official_info($updates)
    {
        if (isset($updates->response[RY_WT_PLUGIN_BASENAME])) {
            return $updates;
        }

        if (!isset($updates->no_update[RY_WT_PLUGIN_BASENAME])) {
            return $updates;
        }

        $raw_response = wp_remote_get('https://api.wordpress.org/plugins/info/1.0/ry-woocommerce-tools.json');
        if (!is_wp_error($raw_response) && 200 === wp_remote_retrieve_response_code($raw_response)) {
            $response = json_decode(wp_remote_retrieve_body($raw_response), true);
            if ($response && is_array($response)) {
                if (version_compare($response['version'], RY_WT_VERSION, '>')) {
                    $updates->response[RY_WT_PLUGIN_BASENAME] = $updates->no_update[RY_WT_PLUGIN_BASENAME];
                    unset($updates->no_update[RY_WT_PLUGIN_BASENAME]);
                    $updates->response[RY_WT_PLUGIN_BASENAME]->new_version = $response['version'];
                    $updates->response[RY_WT_PLUGIN_BASENAME]->tested = $response['tested'];
                    $updates->response[RY_WT_PLUGIN_BASENAME]->requires = $response['requires'];
                    $updates->response[RY_WT_PLUGIN_BASENAME]->requires_php = $response['requires_php'];
                    $updates->response[RY_WT_PLUGIN_BASENAME]->requires_plugins = $response['requires_plugins'];
                    $updates->response[RY_WT_PLUGIN_BASENAME]->package = $response['versions'][$response['version']];
                }
            }
        }

        return $updates;
    }
}
