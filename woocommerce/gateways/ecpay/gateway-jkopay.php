<?php

defined('ABSPATH') or exit;

class RY_ECPay_Gateway_Jkopay extends RY_WT_WC_ECPay_Payment_Gateway
{
    public const ID = 'ry_ecpay_jkopay';

    public const PAYMENT_TYPE = 'DigitalPayment';

    public const SUB_PAYMENT_TYPE = 'Jkopay';

    public function __construct()
    {
        $this->id = self::ID;
        $this->has_fields = false;
        $this->order_button_text = __('Pay via JKOPay', 'ry-woocommerce-tools-pro');
        $this->method_title = __('ECPay JKOPay', 'ry-woocommerce-tools-pro');

        $this->form_fields = include RY_WTP_PLUGIN_DIR . 'woocommerce/gateways/ecpay/includes/settings/digital.php';

        parent::__construct();
    }
}
