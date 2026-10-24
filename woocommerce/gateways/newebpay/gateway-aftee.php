<?php

defined('ABSPATH') or exit;

class RY_NewebPay_Gateway_Aftee extends RY_WT_WC_NewebPay_Payment_Gateway
{
    public const ID = 'ry_newebpay_aftee';

    public const PAYMENT_TYPE = 'AFTEE';

    protected int $check_min_amount = 15;

    public function __construct()
    {
        $this->id = self::ID;
        $this->has_fields = false;
        $this->order_button_text = __('Pay via AFTEE', 'ry-woocommerce-tools-pro');
        $this->method_title = __('NewebPay AFTEE', 'ry-woocommerce-tools-pro');

        $this->form_fields = include RY_WTP_PLUGIN_DIR . 'woocommerce/gateways/newebpay/includes/settings/bnpl.php';

        parent::__construct();
    }
}
