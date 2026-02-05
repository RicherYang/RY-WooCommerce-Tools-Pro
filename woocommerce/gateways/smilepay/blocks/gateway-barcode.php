<?php

final class RY_SmilePay_Gateway_Barcode_Blocks_Support extends RY_WTP_AbstractPaymentMethodType
{
    protected $name = 'ry_smilepay_barcode';

    public function initialize()
    {
        $this->settings = get_option('woocommerce_ry_smilepay_barcode_settings', []);
    }

    public function is_active()
    {
        return $this->get_gateway()->is_available();
    }

    public function get_payment_method_script_handles()
    {
        $script_asset = include RY_WTP_PLUGIN_DIR . 'assets/blocks/gateways/smilepay/barcode.asset.php';

        wp_register_script('ry-smilepay-barcode-block', RY_WTP_PLUGIN_URL . 'assets/blocks/gateways/smilepay/barcode.js', $script_asset['dependencies'], $script_asset['version'], true);
        wp_set_script_translations('ry-smilepay-barcode-block', 'ry-woocommerce-tools-pro', RY_WTP_PLUGIN_LANGUAGES_DIR);

        return ['ry-smilepay-barcode-block'];
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
                'alt' => __('SmilePay', 'ry-woocommerce-tools-pro'),
            ],
        ];
    }
}
