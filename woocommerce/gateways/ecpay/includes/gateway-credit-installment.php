<?php

defined('ABSPATH') or exit;

class RY_ECPay_Gateway_Credit_Installment_Base extends RY_WT_WC_ECPay_Payment_Gateway
{
    public const PAYMENT_TYPE = 'Credit';

    public const SUPPORT_REFUND = true;

    public array $number_of_periods = [];

    public function __construct()
    {
        $this->has_fields = false;

        $this->form_fields = include RY_WT_PLUGIN_DIR . 'woocommerce/gateways/ecpay/includes/settings/credit-installment.php';
        unset($this->form_fields['number_of_periods']);

        parent::__construct();
    }
}
