<?php

class RY_ECPay_Shipping_CVS_711_Pro extends RY_ECPay_Shipping_CVS_711
{
    public function __construct($instance_ID = 0)
    {
        $this->instance_form_fields = include RY_WT_PLUGIN_DIR . 'woocommerce/shipping/ecpay/includes/settings/cvs.php';

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
                    'desc_tip' => true,
                ],
            ]
            + array_slice($this->instance_form_fields, $field_idx);

        list($MerchantID, $HashKey, $HashIV, $cvs_type) = RY_WT_WC_ECPay_Shipping::instance()->get_api_info();
        if('B2C' === $cvs_type) {
            $field_idx = array_search('cost', array_keys($this->instance_form_fields)) + 1;
            $this->instance_form_fields = array_slice($this->instance_form_fields, 0, $field_idx)
            + [
                'cost_cool' => [
                    'title' => __('Cool plus cost', 'ry-woocommerce-tools-pro'),
                    'type' => 'number',
                    'default' => 115,
                    'min' => 0,
                    'step' => 1,
                    'description' => __('The total cost is cost plus cool cost.', 'ry-woocommerce-tools-pro'),
                    'desc_tip' => true,
                ],
            ]
            + array_slice($this->instance_form_fields, $field_idx);
        }

        $this->instance_form_fields['cost_requires']['options']['min_amount_except_discount'] = __('A minimum order amount ( except discount and tex )', 'ry-woocommerce-tools-pro');
        $this->instance_form_fields['cost_requires']['options']['min_amount_except_discount_or_coupon'] = __('A minimum order amount OR a coupon ( except discount and tex )', 'ry-woocommerce-tools-pro');
        $this->instance_form_fields['cost_requires']['options']['min_amount_except_discount_and_coupon'] = __('A minimum order amount AND a coupon ( except discount and tex )', 'ry-woocommerce-tools-pro');

        parent::__construct($instance_ID);

        $this->cost_cool = $this->get_option('cost_cool');
        $this->cost_offisland = $this->get_option('cost_offisland');
    }

    public static function get_support_temp()
    {
        list($MerchantID, $HashKey, $HashIV, $cvs_type) = RY_WT_WC_ECPay_Shipping::instance()->get_api_info();
        if('B2C' === $cvs_type) {
            return ['1', '3'];
        }

        return ['1'];
    }
}
