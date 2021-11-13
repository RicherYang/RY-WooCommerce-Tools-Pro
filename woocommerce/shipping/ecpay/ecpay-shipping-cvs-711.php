<?php
class RY_ECPay_Shipping_CVS_711_Pro extends RY_ECPay_Shipping_CVS_711
{
    public function __construct($instance_id = 0)
    {
        $this->instance_form_fields = include RY_WT_PLUGIN_DIR . 'woocommerce/shipping/ecpay/includes/settings-ecpay-shipping-base.php';

        $field_idx = array_search('cost', array_keys($this->instance_form_fields)) + 1;
        $this->instance_form_fields = array_slice($this->instance_form_fields, 0, $field_idx)
            + [
                'cost_offisland' => [
                    'title' => __('Off island plus cost', 'ry-woocommerce-tools-pro'),
                    'type' => 'number',
                    'default' => 40,
                    'min' => 0,
                    'step' => 1,
                    'description' => __('The total cost is cost plus off island cost.', 'ry-woocommerce-tools-pro'),
                    'desc_tip' => true
                ]
            ]
            + array_slice($this->instance_form_fields, $field_idx);

        $this->instance_form_fields['cost_requires']['options']['min_amount_except_discount'] = __('A minimum order amount ( except discount and tex )', 'ry-woocommerce-tools-pro');
        $this->instance_form_fields['cost_requires']['options']['min_amount_except_discount_or_coupon'] = __('A minimum order amount OR a coupon ( except discount and tex )', 'ry-woocommerce-tools-pro');
        $this->instance_form_fields['cost_requires']['options']['min_amount_except_discount_and_coupon'] = __('A minimum order amount AND a coupon ( except discount and tex )', 'ry-woocommerce-tools-pro');

        parent::__construct($instance_id);

        $this->cost_offisland = $this->get_option('cost_offisland');
    }
}
