<?php

defined('ABSPATH') or exit;

class RY_NewebPay_Gateway_Digital extends RY_WT_WC_NewebPay_Payment_Gateway
{
    public const ID = 'ry_newebpay_digital';

    public const PAYMENT_TYPE = 'Digital';

    public bool $support_esun = true;

    public bool $support_line = true;

    public bool $support_taiwan = true;

    public function __construct()
    {
        $this->id = self::ID;
        $this->has_fields = false;
        $this->order_button_text = __('Pay via Digital Payment', 'ry-woocommerce-tools-pro');
        $this->method_title = __('NewebPay Digital Payment', 'ry-woocommerce-tools-pro');

        $this->form_fields = include RY_WTP_PLUGIN_DIR . 'woocommerce/gateways/newebpay/includes/settings/digital.php';

        $this->form_fields['esun'] = [
            'title' => __('Esun Wallet', 'ry-woocommerce-tools-pro'),
            'label' => __('Support Esun Wallet', 'ry-woocommerce-tools-pro'),
            'type' => 'checkbox',
            'default' => 'yes',
        ];
        $this->form_fields['line'] = [
            'title' => __('LinePay', 'ry-woocommerce-tools-pro'),
            'label' => __('Support LinePay', 'ry-woocommerce-tools-pro'),
            'type' => 'checkbox',
            'default' => 'yes',
        ];
        $this->form_fields['taiwan'] = [
            'title' => __('Taiwan Pay', 'ry-woocommerce-tools-pro'),
            'label' => __('Support Taiwan Pay', 'ry-woocommerce-tools-pro'),
            'type' => 'checkbox',
            'default' => 'yes',
        ];

        parent::__construct();

        $this->support_esun = wc_string_to_bool($this->settings['esun'] ?? 'no');
        $this->support_line = wc_string_to_bool($this->settings['line'] ?? 'no');
        $this->support_taiwan = wc_string_to_bool($this->settings['taiwan'] ?? 'no');
    }
}
