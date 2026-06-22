<?php

defined('ABSPATH') or exit;

class RY_PAYUNi_Gateway_Bnpl extends RY_WT_WC_PAYUNi_Payment_Gateway
{
    public const ID = 'ry_payuni_bnpl';

    public const PAYMENT_TYPE = 'BNPL';

    public const SUPPORT_REFUND = true;

    protected int $check_min_amount = 20;

    protected int $check_max_amount = 49999;

    public function __construct()
    {
        $this->id = self::ID;
        $this->has_fields = false;
        $this->order_button_text = __('Pay via BNPL', 'ry-woocommerce-tools-pro');
        $this->method_title = __('PAYUNi BNPL', 'ry-woocommerce-tools-pro');
        $this->method_description = '';
        $this->process_payment_note = __('Pay via PAYUNi BNPL', 'ry-woocommerce-tools-pro');

        $this->form_fields = include RY_WTP_PLUGIN_DIR . 'woocommerce/gateways/payuni/includes/settings/bnpl.php';

        parent::__construct();
    }
}
