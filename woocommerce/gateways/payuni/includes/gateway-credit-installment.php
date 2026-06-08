<?php

defined('ABSPATH') or exit;

class RY_PAYUNi_Gateway_Credit_Installment_Base extends RY_WT_WC_PAYUNi_Payment_Gateway
{
    public const PAYMENT_TYPE = 'CreditInst';

    public const bool SUPPORT_REFUND = true;

    public $number_of_periods = '';

    public function __construct()
    {
        $this->has_fields = false;
        $this->method_description = '';
        $this->process_payment_note = __('Pay via PAYUNi Credit(installment)', 'ry-woocommerce-tools'); // phpcs:ignore WordPress.WP.I18n.TextDomainMismatch

        $this->form_fields = include RY_WT_PLUGIN_DIR . 'woocommerce/gateways/payuni/includes/settings/credit-installment.php';
        unset($this->form_fields['number_of_periods']);

        parent::__construct();
    }
}
