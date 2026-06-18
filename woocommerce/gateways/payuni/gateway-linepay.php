<?php

defined('ABSPATH') or exit;

class RY_PAYUNi_Gateway_Linepay extends RY_WT_WC_PAYUNi_Payment_Gateway
{
    public const ID = 'ry_payuni_linepay';

    public const PAYMENT_TYPE = 'LinePay';

    public const bool SUPPORT_REFUND = true;

    public function __construct()
    {
        $this->id = self::ID;
        $this->has_fields = false;
        $this->order_button_text = __('Pay via LinePay', 'ry-woocommerce-tools-pro');
        $this->method_title = __('PAYUNi LinePay', 'ry-woocommerce-tools-pro');
        $this->method_description = '';
        $this->process_payment_note = __('Pay via PAYUNi LinePay', 'ry-woocommerce-tools-pro');

        $this->form_fields = include RY_WTP_PLUGIN_DIR . 'woocommerce/gateways/payuni/includes/settings/digital.php';

        parent::__construct();
    }
}
