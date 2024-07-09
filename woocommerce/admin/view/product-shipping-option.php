</div>
<div class="options_group">
<?php
woocommerce_wp_text_input([
    'id' => 'ry_shipping_amount',
    'label' => __('Shipping declare amount', 'ry-woocommerce-tools-pro') . ' (' . get_woocommerce_currency_symbol() . ')',
    'value' => $product_object->get_meta('_ry_shipping_amount', true),
    'data_type' => 'price',
]);

woocommerce_wp_select([
    'id' => 'ry_shipping_temp',
    'label' => __('Transport temperature', 'ry-woocommerce-tools-pro'),
    'value' => $product_object->get_meta('_ry_shipping_temp', true),
    'options' => [
        '1' => _x('Normal temperature', 'Transport temp', 'ry-woocommerce-tools'),
        '2' => _x('Refrigerated', 'Transport temp', 'ry-woocommerce-tools'),
        '3' => _x('Frozen', 'Transport temp', 'ry-woocommerce-tools'),
    ],
]);
