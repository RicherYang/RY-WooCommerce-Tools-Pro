<?php

defined('ABSPATH') or exit;

final class RY_ECPay_Gateway_Credit_Installment_24_Blocks_Support extends RY_WTP_AbstractPaymentMethodType
{
    protected $name = 'ry_ecpay_credit_installment_24';

    public function initialize()
    {
        $this->settings = get_option('woocommerce_ry_ecpay_credit_installment_24_settings', []);
    }

    public function is_active()
    {
        if ($this->get_gateway() === null) {
            return filter_var($this->get_setting('enabled', false), FILTER_VALIDATE_BOOLEAN);
        }
        return $this->get_gateway()->is_available();
    }

    public function get_payment_method_script_handles()
    {
        $script_asset = include RY_WTP_PLUGIN_DIR . 'assets/blocks/gateways/ecpay/credit-installment-24.asset.php';

        wp_register_script('ry-ecpay-credit-installment-24-block', RY_WTP_PLUGIN_URL . 'assets/blocks/gateways/ecpay/credit-installment-24.js', $script_asset['dependencies'], $script_asset['version'], true);
        wp_set_script_translations('ry-ecpay-credit-installment-24-block', 'ry-woocommerce-tools-pro', RY_WTP_PLUGIN_LANGUAGES_DIR);

        return ['ry-ecpay-credit-installment-24-block'];
    }

    public function get_payment_method_data()
    {
        return [
            'title' => $this->get_setting('title'),
            'button_title' => $this->get_gateway()->order_button_text,
            'description' => $this->get_setting('description'),
            'supports' => array_filter($this->get_gateway()->supports, [$this->get_gateway(), 'supports']),
            'icons' => [
                'id' => $this->name,
                'src' => $this->get_gateway()->get_icon_url(),
                'alt' => __('ECPay', 'ry-woocommerce-tools-pro'),
            ],
        ];
    }
}
