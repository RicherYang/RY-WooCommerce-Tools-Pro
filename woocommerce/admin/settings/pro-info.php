<?php

return [
    [
        'title' => 'RY Tools (Pro) for WooCommerce',
        'id' => 'pro_options',
        'type' => 'title',
    ],
    [
        'title' => __('License key', 'ry-woocommerce-tools-pro'),
        'id' => RY_WTP::OPTION_PREFIX . 'license_key',
        'type' => 'text',
        'default' => '',
    ],
    [
        'id' => 'ry_wtp_version_info',
        'type' => 'ry_wtp_version_info',
    ],
    [
        'id' => 'pro_options',
        'type' => 'sectionend',
    ],
];
