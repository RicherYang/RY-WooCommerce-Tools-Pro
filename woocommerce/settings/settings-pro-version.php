<?php
return [
    [
        'title' => __('RY WooCommerce Tools Pro', 'ry-woocommerce-tools-pro'),
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
