<?php

defined('ABSPATH') or exit;

class RY_ECPay_Gateway_Jkopay extends RY_WT_WC_ECPay_Payment_Gateway
{
    public const Payment_Type = 'DigitalPayment';

    public const Sub_Payment_Type = 'Jkopay';

    protected int $check_min_amount = 50;

    protected int $check_max_amount = 300000;

    public function __construct()
    {
        $this->id = 'ry_ecpay_jkopay';
        $this->has_fields = false;
        $this->order_button_text = __('Pay via JKOPay', 'ry-woocommerce-tools-pro');
        $this->method_title = __('ECPay JKOPay', 'ry-woocommerce-tools-pro');
        $this->method_description = '';
        $this->process_payment_note = __('Pay via ECPay JKOPay', 'ry-woocommerce-tools-pro');

        $this->form_fields = include RY_WTP_PLUGIN_DIR . 'woocommerce/gateways/ecpay/includes/settings/jkopay.php';

        parent::__construct();
    }
}
