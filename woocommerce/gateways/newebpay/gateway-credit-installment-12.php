<?php

defined('ABSPATH') or exit;

class RY_NewebPay_Gateway_Credit_Installment_12 extends RY_NewebPay_Gateway_Credit_Installment_Base
{
    public const ID = 'ry_newebpay_credit_installment_12';

    public function __construct()
    {
        $this->id = self::ID;
        $this->order_button_text = __('Pay via Credit (12 installment)', 'ry-woocommerce-tools-pro');
        $this->method_title = __('NewebPay Credit (12 installment)', 'ry-woocommerce-tools-pro');
        $this->process_payment_note = __('Pay via NewebPay Credit (12 installment)', 'ry-woocommerce-tools-pro');

        $this->number_of_periods = [12];

        parent::__construct();
    }
}
