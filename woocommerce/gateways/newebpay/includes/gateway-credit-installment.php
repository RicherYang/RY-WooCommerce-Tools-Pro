<?php

class RY_NewebPay_Gateway_Credit_Installment_Base extends RY_WT_WC_NewebPay_Payment_Gateway
{
    public const Payment_Type = 'InstFlag';

    public $number_of_periods = '';

    public function __construct()
    {
        $this->has_fields = false;
        $this->method_description = '';
        $this->process_payment_note = __('Pay via NewebPay Credit(installment)', 'ry-woocommerce-tools'); // phpcs:ignore WordPress.WP.I18n.TextDomainMismatch

        $this->form_fields = include RY_WT_PLUGIN_DIR . 'woocommerce/gateways/newebpay/includes/settings/credit-installment.php';
        unset($this->form_fields['number_of_periods']);
        $this->init_settings();

        $this->title = $this->settings['title'] ?: $this->method_title;
        $this->description = $this->settings['description'];
        $this->min_amount = (int) $this->settings['min_amount'];
        $this->max_amount = (int) $this->settings['max_amount'];

        parent::__construct();
    }
}
