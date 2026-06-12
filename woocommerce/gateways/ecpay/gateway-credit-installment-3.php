<?php

defined('ABSPATH') or exit;

class RY_ECPay_Gateway_Credit_Installment_3 extends RY_ECPay_Gateway_Credit_Installment_Base
{
    public const ID = 'ry_ecpay_credit_installment_3';

    public function __construct()
    {
        $this->id = self::ID;
        $this->order_button_text = __('Pay via Credit (3 installment)', 'ry-woocommerce-tools-pro');
        $this->method_title = __('ECPay Credit (3 installment)', 'ry-woocommerce-tools-pro');
        $this->process_payment_note = __('Pay via ECPay Credit (3 installment)', 'ry-woocommerce-tools-pro');

        $this->number_of_periods = [3];

        parent::__construct();
    }
}
