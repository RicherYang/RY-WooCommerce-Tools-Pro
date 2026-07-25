<?php

namespace RY\Paid\V20260724;

defined('ABSPATH') or exit;

use RY\General\V20260724\AbstractAdmin as GeneralAbstractAdmin;
use RY\Paid\V20260724\Page\License;

abstract class AbstractAdmin extends GeneralAbstractAdmin
{
    protected function do_init(): void
    {
        License::init_menu();
        add_action('all_admin_notices', [$this, 'show_not_activated']);

        parent::do_init();
    }

    public function show_not_activated(): void
    {
        if (!isset($this->license)) {
            return;
        }

        if ($this->license->is_activated()) {
            return;
        }

        echo '<div class="notice notice-info is-dismissible">';
        echo '<p>' . wp_kses(sprintf(
            /* translators: %1$s: Plugin name, %2$s: License URL */
            __('%1$s: Your <a href="%2$s">license</a> is not activated yet!', 'ry-woocommerce-tools-pro'),
            '<strong>' . esc_html($this->license::$main_class::PLUGIN_NAME) . '</strong>',
            esc_url(admin_url('admin.php?page=ry-license'))
        ), ['strong' => [], 'a' => ['href' => []]]) . '</p>';
        echo '</div>';
    }
}
