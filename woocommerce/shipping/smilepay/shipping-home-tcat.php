<?php

class RY_SmilePay_Shipping_Home_Tcat_Pro extends RY_SmilePay_Shipping_Home_Tcat
{
    public function __construct($instance_ID = 0)
    {
        $this->instance_form_fields = include RY_WT_PLUGIN_DIR . 'woocommerce/shipping/smilepay/includes/settings/home.php';

        $field_idx = array_search('cost', array_keys($this->instance_form_fields)) + 1;
        $this->instance_form_fields = array_slice($this->instance_form_fields, 0, $field_idx)
            + [
                'cost_cool' => [
                    'title' => __('Cool plus cost', 'ry-woocommerce-tools-pro'),
                    'type' => 'number',
                    'default' => 30,
                    'min' => 0,
                    'step' => 1,
                    'description' => __('The total cost is cost plus cool cost.', 'ry-woocommerce-tools-pro'),
                    'desc_tip' => true,
                ],
            ]
            + array_slice($this->instance_form_fields, $field_idx);

        $this->instance_form_fields['cost_requires']['options']['min_amount_except_discount'] = __('A minimum order amount ( except discount and tex )', 'ry-woocommerce-tools-pro');
        $this->instance_form_fields['cost_requires']['options']['min_amount_except_discount_or_coupon'] = __('A minimum order amount OR a coupon ( except discount and tex )', 'ry-woocommerce-tools-pro');
        $this->instance_form_fields['cost_requires']['options']['min_amount_except_discount_and_coupon'] = __('A minimum order amount AND a coupon ( except discount and tex )', 'ry-woocommerce-tools-pro');

        parent::__construct($instance_ID);
    }

    public static function get_support_temp()
    {
        return ['1', '2', '3'];
    }
}
