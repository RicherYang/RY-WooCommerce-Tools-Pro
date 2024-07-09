</div>
<div>
    <p class="form-row hide_if_variation_virtual form-row-full">
        <?php woocommerce_wp_text_input([
            'id' => "variable_ry_shipping_amount{$loop}",
            'name' => "variable_ry_shipping_amount[{$loop}]",
            'wrapper_class' => 'form-row form-row-first',
            'label' => __('Shipping declare amount', 'ry-woocommerce-tools-pro') . ' (' . get_woocommerce_currency_symbol() . ')',
            'value' => $variation_object->get_meta('_ry_shipping_amount', true),
            'placeholder' => $product_object->get_meta('_ry_shipping_amount', true),
            'data_type' => 'price',
        ]); ?>
        <?php woocommerce_wp_select([
            'id' => "variable_ry_shipping_temp{$loop}",
            'name' => "variable_ry_shipping_temp[{$loop}]",
            'wrapper_class' => 'form-row form-row-last',
            'label' => __('Transport temperature', 'ry-woocommerce-tools-pro'),
            'value' => $variation_object->get_meta('_ry_shipping_temp', true),
            'options' => [
                '0' => _x('Same as parent', 'Transport temp', 'ry-woocommerce-tools-pro'),
                '1' => _x('Normal temperature', 'Transport temp', 'ry-woocommerce-tools'),
                '2' => _x('Refrigerated', 'Transport temp', 'ry-woocommerce-tools'),
                '3' => _x('Frozen', 'Transport temp', 'ry-woocommerce-tools'),
            ],
        ]); ?>
    </p>
