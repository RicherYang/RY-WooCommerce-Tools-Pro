<?php

class RY_SmilePay_Shipping_CVS_Fami_Pro extends RY_SmilePay_Shipping_CVS_Fami
{
    public function __construct($instance_id = 0)
    {
        $this->instance_form_fields = include RY_WT_PLUGIN_DIR . 'woocommerce/shipping/smilepay/includes/settings-smilepay-shipping-base.php';

        $this->instance_form_fields['cost_requires']['options']['min_amount_except_discount'] = __('A minimum order amount ( except discount and tex )', 'ry-woocommerce-tools-pro');
        $this->instance_form_fields['cost_requires']['options']['min_amount_except_discount_or_coupon'] = __('A minimum order amount OR a coupon ( except discount and tex )', 'ry-woocommerce-tools-pro');
        $this->instance_form_fields['cost_requires']['options']['min_amount_except_discount_and_coupon'] = __('A minimum order amount AND a coupon ( except discount and tex )', 'ry-woocommerce-tools-pro');

        parent::__construct($instance_id);
    }
}
