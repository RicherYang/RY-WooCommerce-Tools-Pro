<?php

defined('ABSPATH') or exit;

final class RY_NewebPay_Gateway_Credit_Blocks_Support extends RY_WTP_AbstractPaymentMethodType
{
    protected $name = 'ry_newebpay_credit';

    public function initialize()
    {
        $this->settings = get_option('woocommerce_ry_newebpay_credit_settings', []);
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
        $script_asset = include RY_WTP_PLUGIN_DIR . 'assets/blocks/gateways/newebpay/credit.asset.php';

        wp_register_script('ry-newebpay-credit-block', RY_WTP_PLUGIN_URL . 'assets/blocks/gateways/newebpay/credit.js', $script_asset['dependencies'], $script_asset['version'], true);
        wp_set_script_translations('ry-newebpay-credit-block', 'ry-woocommerce-tools-pro', RY_WTP_PLUGIN_LANGUAGES_DIR);

        return ['ry-newebpay-credit-block'];
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
                'alt' => __('NewebPay', 'ry-woocommerce-tools-pro'),
            ],
        ];
    }
}
