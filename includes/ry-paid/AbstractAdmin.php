<?php

namespace RY\Paid\V20260729;

defined('ABSPATH') or exit;

use RY\General\V20260810\AbstractAdmin as GeneralAbstractAdmin;
use RY\Paid\V20260729\Page\License as PageLicense;

abstract class AbstractAdmin extends GeneralAbstractAdmin
{
    public function __construct()
    {
        PageLicense::init_menu();
        add_action('all_admin_notices', [$this, 'show_not_activated']);

        parent::__construct();
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
