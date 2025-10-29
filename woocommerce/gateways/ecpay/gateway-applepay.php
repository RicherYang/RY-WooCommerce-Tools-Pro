<?php

use Automattic\WooCommerce\Vendor\Detection\MobileDetect;

class RY_ECPay_Gateway_Applepay extends RY_WT_WC_ECPay_Payment_Gateway
{
    public const Payment_Type = 'ApplePay';

    protected $check_min_amount = 6;

    protected $check_max_amount = 199999;

    public function __construct()
    {
        $this->id = 'ry_ecpay_applepay';
        $this->has_fields = false;
        $this->order_button_text = __('Pay via Apple Pay', 'ry-woocommerce-tools-pro');
        $this->method_title = __('ECPay Apple Pay', 'ry-woocommerce-tools-pro');
        $this->method_description = '';
        $this->process_payment_note = __('Pay via ECPay Apple Pay', 'ry-woocommerce-tools-pro');

        $this->form_fields = include RY_WTP_PLUGIN_DIR . 'woocommerce/gateways/ecpay/includes/settings/applepay.php';

        parent::__construct();
    }
}
