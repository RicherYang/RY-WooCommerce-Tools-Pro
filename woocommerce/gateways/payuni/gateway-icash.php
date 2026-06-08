<?php

defined('ABSPATH') or exit;

class RY_PAYUNi_Gateway_Icash extends RY_WT_WC_PAYUNi_Payment_Gateway
{
    public const PAYMENT_TYPE = 'ICash';

    public const bool SUPPORT_REFUND = true;

    protected int $check_min_amount = 50;

    protected int $check_max_amount = 300000;

    public function __construct()
    {
        $this->id = 'ry_payuni_icash';
        $this->has_fields = false;
        $this->order_button_text = __('Pay via ICash', 'ry-woocommerce-tools-pro');
        $this->method_title = __('PAYUNi ICash', 'ry-woocommerce-tools-pro');
        $this->method_description = '';
        $this->process_payment_note = __('Pay via PAYUNi ICash', 'ry-woocommerce-tools-pro');

        $this->form_fields = include RY_WTP_PLUGIN_DIR . 'woocommerce/gateways/payuni/includes/settings/icash.php';

        parent::__construct();
    }
}
