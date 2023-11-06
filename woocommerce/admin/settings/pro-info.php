<?php

return [
    [
        'title' => 'RY WooCommerce Tools Pro',
        'id' => 'pro_options',
        'type' => 'title'
    ],
    [
        'title' => __('License key', 'ry-woocommerce-tools-pro'),
        'id' => RY_WTP::Option_Prefix . 'license_key',
        'type' => 'text',
        'default' => ''
    ],
    [
        'id' => 'ry_wtp_version_info',
        'type' => 'ry_wtp_version_info',
    ],
    [
        'id' => 'pro_options',
        'type' => 'sectionend'
    ]
];
