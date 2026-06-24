<?php

defined('ABSPATH') or exit;

class RY_PAYUNi_Gateway_Credit_Installment_12 extends RY_PAYUNi_Gateway_Credit_Installment_Base
{
    public const ID = 'ry_payuni_credit_installment_12';

    public function __construct()
    {
        $this->id = self::ID;
        $this->order_button_text = __('Pay via Credit (12 installment)', 'ry-woocommerce-tools-pro');
        $this->method_title = __('PAYUNi Credit (12 installment)', 'ry-woocommerce-tools-pro');

        $this->number_of_periods = [12];

        parent::__construct();
    }
}
