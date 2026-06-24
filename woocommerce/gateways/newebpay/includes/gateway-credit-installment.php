<?php

defined('ABSPATH') or exit;

class RY_NewebPay_Gateway_Credit_Installment_Base extends RY_WT_WC_NewebPay_Payment_Gateway
{
    public const PAYMENT_TYPE = 'InstFlag';

    public array $number_of_periods = [];

    public function __construct()
    {
        $this->has_fields = false;

        $this->form_fields = include RY_WT_PLUGIN_DIR . 'woocommerce/gateways/newebpay/includes/settings/credit-installment.php';
        unset($this->form_fields['number_of_periods']);

        parent::__construct();
    }
}
