<?php

if (class_exists('RY_Admin_License')) {
    return;
}

include_once __DIR__ . '/abstract-admin-page.php';

final class RY_Admin_License extends RY_Abstract_Admin_Page
{
    protected static $_instance = null;

    protected $license_list = [];

    public static function init_menu(): void
    {
        add_filter('ry-plugin/menu_list', [__CLASS__, 'add_menu'], 9999);
        add_action('admin_post_ry/admin-license', [__CLASS__, 'admin_action']);
        add_action('admin_post_ry/admin-license-log', [__CLASS__, 'admin_action']);
    }

    public static function add_menu($menu_list)
    {
        $menu_list[] = [
            'name' => '授權金鑰',
            'slug' => 'ry-license',
            'function' => [__CLASS__, 'pre_show_page'],
        ];

        return $menu_list;
    }

    protected function do_init(): void
    {
        $this->license_list = apply_filters('ry-plugin/license_list', []);
        foreach ($this->license_list as $key => $license) {
            if (!is_array($license)) {
                unset($this->license_list[$key]);
                continue;
            }
            if (!isset($license['name'], $license['license'], $license['version'], $license['basename'])) {
                unset($this->license_list[$key]);
                continue;
            }
            if (!$license['license'] instanceof RY_Abstract_License) {
                unset($this->license_list[$key]);
                continue;
            }
        }
        usort($this->license_list, function ($a, $b) {
            return strcmp($a['name'], $b['name']);
        });
    }

    public function output_page(): void
    {
        echo '<div class="wrap"><h1>授權金鑰</h1>';
        echo '<form method="post" action="admin-post.php">';
        echo '<input type="hidden" name="action" value="ry/admin-license">';
        wp_nonce_field('ry/admin-license', '_wpnonce', false);
        foreach ($this->license_list as $license) {
            $license['key'] = $license['license']->get_license_key();
            $license['expire'] = $license['license']->get_expire();
            $license['version_info'] = $license['license']->get_version_info();
            unset($license['license']);
            extract($license);
            $version_info = array_merge(array_flip(['version']), is_array($version_info) ? $version_info : []);

            include __DIR__ . '/html/license.php';
        }
        submit_button();
        echo '</form>';

        $license_logs = $this->license_list[array_key_first($this->license_list)]['license']->get_logs('ry-license');
        if (count($license_logs)) {
            echo '<form method="post" action="admin-post.php">';
            echo '<input type="hidden" name="action" value="ry/admin-license-log">';
            wp_nonce_field('ry/admin-license-log', '_wpnonce', false);
            include __DIR__ . '/html/license_log.php';
            submit_button('刪除紀錄');
            echo '</form>';
        }
        echo '</div>';
    }

    public function do_admin_action(string $action): void
    {
        static $error_msg = [];
        if (empty($error_msg)) {
            $error_msg = [
                'Unknown key' => '未知的金鑰',
                'Locked key' => '鎖定的金鑰',
                'Unknown target url' => '未知的目標網址',
                'Used key' => '已使用的金鑰',
                'Is tried' => '已使用過測試版',
            ];
        }

        if (in_array($action, ['ry/admin-license', 'ry/admin-license-log'], true) === false) {
            return;
        }

        if (!wp_verify_nonce($_POST['_wpnonce'] ?? '', $action)) { // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.MissingUnslash , WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
            wp_die('Invalid nonce');
        }

        if ($action === 'ry/admin-license') {
            foreach ($this->license_list as $license) {
                if (is_array($license) && isset($license['name'], $license['license'])) {
                    $key = sanitize_locale_name($_POST['key-' . hash('sha1', $license['basename'])] ?? '');
                    if (hash_equals($key, $license['license']->get_license_key())) {
                        $this->add_notice('success', $license['name'] . ': 金鑰未變更。');
                        continue;
                    }

                    $license['license']->add_log('info', 'ry-license', 'User #' . get_current_user_id() . ' change "' . $license['name'] . '" license key');
                    $license['license']->delete_license();
                    $license['license']->set_license_key($key);
                    if ($key !== '') {
                        $json = $license['license']->activate_key();

                        if (false === $json) {
                            $this->add_notice('error', $license['name'] . ': 無法與授權伺服器連線。');
                        } else {
                            if (is_array($json)) {
                                if (empty($json['data'])) {
                                    $this->add_notice('error', $license['name'] . ': 授權驗證失敗 ( ' . $error_msg[$json['error']] . ' )。');
                                } else {
                                    $license['license']->set_license_data($json['data']);
                                    $this->add_notice('success', $license['name'] . ': 授權驗證成功。');
                                }
                            } else {
                                $this->add_notice('error', $license['name'] . ': 與授權伺服器連線失敗。');
                            }
                        }
                    }
                }
            }
        }

        if ($action === 'ry/admin-license-log') {
            $this->license_list[array_key_first($this->license_list)]['license']->delete_log('ry-license');
            $this->add_notice('success', '授權日誌紀錄已刪除。');
        }

        wp_safe_redirect(admin_url('admin.php?page=ry-license'));
        exit;
    }

    protected function add_notice(string $status, string $message): void
    {
        $notice = get_transient('ry-notice');
        if (!is_array($notice)) {
            $notice = [];
        }
        if (!isset($notice[$status])) {
            $notice[$status] = [];
        }
        $notice[$status][] = $message;

        set_transient('ry-notice', $notice);
    }
}

RY_Admin_License::init_menu();
