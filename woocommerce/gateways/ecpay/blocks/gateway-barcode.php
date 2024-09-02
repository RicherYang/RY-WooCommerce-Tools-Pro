<?php

use Automattic\WooCommerce\Blocks\Payments\Integrations\AbstractPaymentMethodType;

final class RY_ECPay_Gateway_Barcode_Blocks_Support extends AbstractPaymentMethodType
{
    protected $name = 'ry_ecpay_barcode';

    private $gateway;

    public function initialize()
    {
        $this->settings = get_option('woocommerce_ry_ecpay_barcode_settings', []);
        $payment_gateways = WC()->payment_gateways->payment_gateways();
        $this->gateway = $payment_gateways[$this->name];
    }

    public function is_active()
    {
        return $this->gateway->is_available();
    }

    public function get_payment_method_script_handles()
    {
        $script_asset = include RY_WTP_PLUGIN_DIR . 'assets/blocks/gateways/ecpay/barcode.asset.php';

        wp_register_script('ry-ecpay-barcode-block', RY_WTP_PLUGIN_URL . 'assets/blocks/gateways/ecpay/barcode.js', $script_asset['dependencies'], $script_asset['version'], true);
        wp_set_script_translations('ry-ecpay-barcode-block', 'ry-woocommerce-tools-pro', RY_WTP_PLUGIN_LANGUAGES_DIR);

        return ['ry-ecpay-barcode-block'];
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
                'alt' => __('ECPay', 'ry-woocommerce-tools-pro'),
            ],
        ];
    }
}
