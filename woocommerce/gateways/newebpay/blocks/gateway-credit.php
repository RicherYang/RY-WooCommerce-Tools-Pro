<?php

use Automattic\WooCommerce\Blocks\Payments\Integrations\AbstractPaymentMethodType;

final class RY_NewebPay_Gateway_Credit_Blocks_Support extends AbstractPaymentMethodType
{
    private $gateway;

    protected $name = 'ry_newebpay_credit';

    public function initialize()
    {
        $this->settings = get_option('woocommerce_ry_newebpay_credit_settings', []);
        $payment_gateways = WC()->payment_gateways->payment_gateways();
        $this->gateway = $payment_gateways[$this->name];
    }

    public function is_active()
    {
        return $this->gateway->is_available();
    }

    public function get_payment_method_script_handles()
    {
        $script_asset = include RY_WTP_PLUGIN_DIR . 'assets/js/blocks/gateways/newebpay/credit.asset.php';

        wp_register_script('ry-newebpay-credit-block', RY_WTP_PLUGIN_URL . 'assets/js/blocks/gateways/newebpay/credit.js', $script_asset['dependencies'], $script_asset['version'], true);
        wp_set_script_translations('ry-newebpay-credit-block', 'ry-woocommerce-tools-pro', RY_WTP_PLUGIN_LANGUAGES_DIR);

        return ['ry-newebpay-credit-block'];
    }

    public function get_payment_method_data()
    {
        return [
            'title' => $this->get_setting('title'),
            'button_title' => $this->gateway->order_button_text,
            'description' => $this->get_setting('description'),
            'supports' => array_filter($this->gateway->supports, [$this->gateway, 'supports']),
            'icons' => [
                'id' => $this->name,
                'src' => $this->gateway->get_icon_url(),
                'alt' => __('NewebPay', 'ry-woocommerce-tools-pro')
            ]
        ];
    }
}
