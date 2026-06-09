<?php

defined('ABSPATH') or exit;

class RY_ECPay_Gateway_Credit_Installment_18 extends RY_ECPay_Gateway_Credit_Installment_Base
{
    public const ID = 'ry_ecpay_credit_installment_18';

    public function __construct()
    {
        $this->id = self::ID;
        $this->order_button_text = __('Pay via Credit (18 installment)', 'ry-woocommerce-tools-pro');
        $this->method_title = __('ECPay Credit (18 installment)', 'ry-woocommerce-tools-pro');
        $this->process_payment_note = __('Pay via ECPay Credit (18 installment)', 'ry-woocommerce-tools-pro');

        $this->number_of_periods = 18;

        parent::__construct();
    }
}
