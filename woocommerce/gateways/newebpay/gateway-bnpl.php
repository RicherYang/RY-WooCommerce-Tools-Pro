<?php

defined('ABSPATH') or exit;

class RY_NewebPay_Gateway_Bnpl extends RY_WT_WC_NewebPay_Payment_Gateway
{
    public const ID = 'ry_newebpay_bnpl';

    public const PAYMENT_TYPE = 'BNPL';

    protected int $check_min_amount = 15;

    protected int $check_max_amount = 49999;

    public function __construct()
    {
        $this->id = self::ID;
        $this->has_fields = false;
        $this->order_button_text = __('Pay via BNPL', 'ry-woocommerce-tools-pro');
        $this->method_title = __('NewebPay BNPL', 'ry-woocommerce-tools-pro');

        $this->form_fields = include RY_WTP_PLUGIN_DIR . 'woocommerce/gateways/newebpay/includes/settings/bnpl.php';

        parent::__construct();
    }
}
