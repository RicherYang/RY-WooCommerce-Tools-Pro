<?php

defined('ABSPATH') or exit;

class RY_NewebPay_Gateway_Linepay extends RY_WT_WC_NewebPay_Payment_Gateway
{
    public const ID = 'ry_newebpay_linepay';

    public const PAYMENT_TYPE = 'LINEPAY';

    public function __construct()
    {
        $this->id = self::ID;
        $this->has_fields = false;
        $this->order_button_text = __('Pay via LinePay', 'ry-woocommerce-tools-pro');
        $this->method_title = __('NewebPay LinePay', 'ry-woocommerce-tools-pro');

        $this->form_fields = include RY_WTP_PLUGIN_DIR . 'woocommerce/gateways/newebpay/includes/settings/digital.php';

        parent::__construct();
    }
}
