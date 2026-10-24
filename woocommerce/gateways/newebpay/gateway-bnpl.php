<?php

defined('ABSPATH') or exit;

class RY_NewebPay_Gateway_Bnpl extends RY_WT_WC_NewebPay_Payment_Gateway
{
    public const ID = 'ry_newebpay_bnpl';

    public const PAYMENT_TYPE = 'BNPL';

    public bool $support_aftee = true;

    public bool $support_oppay = true;

    protected int $check_min_amount = 15;

    public function __construct()
    {
        $this->id = self::ID;
        $this->has_fields = false;
        $this->order_button_text = __('Pay via BNPL', 'ry-woocommerce-tools-pro');
        $this->method_title = __('NewebPay BNPL', 'ry-woocommerce-tools-pro');

        $this->form_fields = include RY_WTP_PLUGIN_DIR . 'woocommerce/gateways/newebpay/includes/settings/bnpl.php';

        $this->form_fields['aftee'] = [
            'title' => __('Aftee', 'ry-woocommerce-tools-pro'),
            'label' => __('Support Aftee', 'ry-woocommerce-tools-pro'),
            'type' => 'checkbox',
            'default' => 'yes',
        ];
        $this->form_fields['oppay'] = [
            'title' => __('Oppay', 'ry-woocommerce-tools-pro'),
            'label' => __('Support Oppay', 'ry-woocommerce-tools-pro'),
            'type' => 'checkbox',
            'default' => 'yes',
        ];

        parent::__construct();

        $this->support_aftee = wc_string_to_bool($this->settings['aftee'] ?? 'yes');
        $this->support_oppay = wc_string_to_bool($this->settings['oppay'] ?? 'yes');
    }
}
