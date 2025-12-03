</div>
<div>
<?php
woocommerce_wp_text_input([
    'id' => "variable_ry_shipping_amount{$loop}",
    'name' => "variable_ry_shipping_amount[{$loop}]",
    'wrapper_class' => 'form-field form-row form-row-first hide_if_variation_virtual',
    'label' => __('Shipping declare amount', 'ry-woocommerce-tools-pro') . ' (' . get_woocommerce_currency_symbol() . ')',
    'value' => $variation_object->get_meta('_ry_shipping_amount', true),
    'placeholder' => $product_object->get_meta('_ry_shipping_amount', true),
    'data_type' => 'price',
]);

woocommerce_wp_select([
    'id' => "variable_ry_shipping_temp{$loop}",
    'name' => "variable_ry_shipping_temp[{$loop}]",
    'wrapper_class' => 'form-field form-row form-row-last hide_if_variation_virtual',
    'label' => __('Transport temperature', 'ry-woocommerce-tools-pro'),
    'value' => $variation_object->get_meta('_ry_shipping_temp', true),
    'options' => [
        '0' => _x('Same as parent', 'Transport temp', 'ry-woocommerce-tools-pro'),
        '1' => _x('Normal temperature', 'Transport temp', 'ry-woocommerce-tools'), // phpcs:ignore WordPress.WP.I18n.TextDomainMismatch
        '2' => _x('Refrigerated', 'Transport temp', 'ry-woocommerce-tools'), // phpcs:ignore WordPress.WP.I18n.TextDomainMismatch
        '3' => _x('Frozen', 'Transport temp', 'ry-woocommerce-tools'), // phpcs:ignore WordPress.WP.I18n.TextDomainMismatch
    ],
]);

rywtp_multiselect([
    'id' => "variable_ry_shipping_skip_shipping{$loop}",
    'name' => "variable_ry_shipping_skip_shipping[{$loop}][]",
    'wrapper_class' => 'form-field form-row form-row-full hide_if_variation_virtual',
    'label' => __('Skip shipping method', 'ry-woocommerce-tools-pro'),
    'value' => (array) $product_object->get_meta('_ry_shipping_skip_shipping', true),
    'options' => $this->get_options_shipping_methods(),
    'custom_attributes' => [
        'data-placeholder' => __('Select shipping methods to skip', 'ry-woocommerce-tools-pro'),
    ],
]);
?>
