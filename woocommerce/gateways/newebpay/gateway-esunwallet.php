<?php

defined('ABSPATH') or exit;

class RY_NewebPay_Gateway_Esunwallet extends RY_WT_WC_NewebPay_Payment_Gateway
{
    public const ID = 'ry_newebpay_esunwallet';

    public const PAYMENT_TYPE = 'ESUNWALLET';

    public function __construct()
    {
        $this->id = self::ID;
        $this->has_fields = false;
        $this->order_button_text = __('Pay via Esun Wallet', 'ry-woocommerce-tools-pro');
        $this->method_title = __('NewebPay Esun Wallet', 'ry-woocommerce-tools-pro');

        $this->form_fields = include RY_WT_PLUGIN_DIR . 'woocommerce/gateways/newebpay/includes/settings/digital.php';

        parent::__construct();
    }
}
