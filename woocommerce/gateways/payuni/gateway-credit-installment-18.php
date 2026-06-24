<?php

defined('ABSPATH') or exit;

class RY_PAYUNi_Gateway_Credit_Installment_18 extends RY_PAYUNi_Gateway_Credit_Installment_Base
{
    public const ID = 'ry_payuni_credit_installment_18';

    public function __construct()
    {
        $this->id = self::ID;
        $this->order_button_text = __('Pay via Credit (18 installment)', 'ry-woocommerce-tools-pro');
        $this->method_title = __('PAYUNi Credit (18 installment)', 'ry-woocommerce-tools-pro');

        $this->number_of_periods = [18];

        parent::__construct();
    }
}
