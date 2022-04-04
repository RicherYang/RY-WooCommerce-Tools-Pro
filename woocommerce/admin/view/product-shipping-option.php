</div>
<div class="options_group">
<?php
woocommerce_wp_select([
    'id' => '_ry_shipping_temp',
    'value' => $product_object->get_meta('_ry_shipping_temp', true),
    'wrapper_class' => 'show_if_simple show_if_variable',
    'label' => __('Transport temperature', 'ry-woocommerce-tools-pro'),
    'options' => [
        '1' => _x('Normal temperature', 'Transport temp', 'ry-woocommerce-tools'),
        '2' => _x('Refrigerated', 'Transport temp', 'ry-woocommerce-tools'),
        '3' => _x('Freezer', 'Transport temp', 'ry-woocommerce-tools'),
    ]
]);
