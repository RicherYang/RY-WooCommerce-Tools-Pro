<?php

defined('ABSPATH') or exit;

class RY_PAYUNi_Gateway_Credit_Installment_3 extends RY_PAYUNi_Gateway_Credit_Installment_Base
{
    public const ID = 'ry_payuni_credit_installment_3';

    public function __construct()
    {
        $this->id = self::ID;
        $this->order_button_text = __('Pay via Credit (3 installment)', 'ry-woocommerce-tools-pro');
        $this->method_title = __('PAYUNi Credit (3 installment)', 'ry-woocommerce-tools-pro');

        $this->number_of_periods = [3];

        parent::__construct();
    }
}
