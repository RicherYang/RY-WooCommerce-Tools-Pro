<?php

defined('ABSPATH') or exit;

class RY_PAYUNi_Gateway_Aftee extends RY_WT_WC_PAYUNi_Payment_Gateway
{
    public const PAYMENT_TYPE = 'Aftee';

    public const bool SUPPORT_REFUNOD = true;

    protected int $check_min_amount = 19;

    protected int $check_max_amount = 50000;

    public function __construct()
    {
        $this->id = 'ry_payuni_aftee';
        $this->has_fields = false;
        $this->order_button_text = __('Pay via Aftee', 'ry-woocommerce-tools-pro');
        $this->method_title = __('PAYUNi Aftee', 'ry-woocommerce-tools-pro');
        $this->method_description = '';
        $this->process_payment_note = __('Pay via PAYUNi Aftee', 'ry-woocommerce-tools-pro');

        $this->form_fields = include RY_WTP_PLUGIN_DIR . 'woocommerce/gateways/payuni/includes/settings/aftee.php';

        parent::__construct();
    }
}
