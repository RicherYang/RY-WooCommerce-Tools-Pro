<?php

class RY_ECPay_Gateway_Credit_Installment_Base extends RY_WT_WC_ECPay_Payment_Gateway
{
    public const Payment_Type = 'Credit';

    public $number_of_periods = '';

    public function __construct()
    {
        $this->has_fields = false;
        $this->method_description = '';
        $this->process_payment_note = __('Pay via ECPay Credit(installment)', 'ry-woocommerce-tools'); // phpcs:ignore WordPress.WP.I18n.TextDomainMismatch

        $this->form_fields = include RY_WT_PLUGIN_DIR . 'woocommerce/gateways/ecpay/includes/settings/credit-installment.php';
        unset($this->form_fields['number_of_periods']);

        parent::__construct();
    }
}
