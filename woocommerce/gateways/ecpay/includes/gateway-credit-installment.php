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
        $this->init_settings();

        $this->title = $this->get_option('title') ?: $this->method_title;
        $this->description = $this->get_option('description');
        $this->min_amount = (int) $this->get_option('min_amount', 0);
        $this->max_amount = (int) $this->get_option('max_amount', 0);

        parent::__construct();
    }
}
