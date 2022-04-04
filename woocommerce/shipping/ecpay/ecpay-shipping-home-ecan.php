<?php
class RY_ECPay_Shipping_Home_Ecan_Pro extends RY_ECPay_Shipping_Home_Ecan
{
    public static $support_temp = ['1'];

    public function __construct($instance_id = 0)
    {
        $this->instance_form_fields = include RY_WT_PLUGIN_DIR . 'woocommerce/shipping/ecpay/includes/settings-ecpay-shipping-base.php';

        $this->instance_form_fields['cost_requires']['options']['min_amount_except_discount'] = __('A minimum order amount ( except discount and tex )', 'ry-woocommerce-tools-pro');
        $this->instance_form_fields['cost_requires']['options']['min_amount_except_discount_or_coupon'] = __('A minimum order amount OR a coupon ( except discount and tex )', 'ry-woocommerce-tools-pro');
        $this->instance_form_fields['cost_requires']['options']['min_amount_except_discount_and_coupon'] = __('A minimum order amount AND a coupon ( except discount and tex )', 'ry-woocommerce-tools-pro');

        parent::__construct($instance_id);
    }
}
