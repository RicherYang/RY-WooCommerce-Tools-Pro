<?php
class RY_NewebPay_Shipping_CVS_Pro extends RY_NewebPay_Shipping_CVS
{
    public function __construct($instance_id = 0)
    {
        $this->instance_form_fields = include RY_WT_PLUGIN_DIR . 'woocommerce/shipping/newebpay/includes/settings-newebpay-shipping-cvs.php';

        $this->instance_form_fields['cost_requires']['options']['min_amount_except_discount'] = __('A minimum order amount ( except discount and tex )', 'ry-woocommerce-tools-pro');
        $this->instance_form_fields['cost_requires']['options']['min_amount_except_discount_or_coupon'] = __('A minimum order amount OR a coupon ( except discount and tex )', 'ry-woocommerce-tools-pro');
        $this->instance_form_fields['cost_requires']['options']['min_amount_except_discount_and_coupon'] = __('A minimum order amount AND a coupon ( except discount and tex )', 'ry-woocommerce-tools-pro');

        parent::__construct($instance_id);
    }
}
