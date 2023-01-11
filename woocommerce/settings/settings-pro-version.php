<?php

return [
    [
        'title' => 'RY WooCommerce Tools Pro',
        'id' => 'pro_options',
        'type' => 'title'
    ],
    [
        'title' => __('License key', 'ry-woocommerce-tools-pro'),
        'id' => RY_WTP::$option_prefix . 'license_key',
        'type' => 'text',
        'default' => ''
    ],
    [
        'type' => 'rywct_version_info',
    ],
    [
        'id' => 'pro_options',
        'type' => 'sectionend'
    ]
];
