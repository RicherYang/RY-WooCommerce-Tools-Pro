<?php

defined('ABSPATH') or exit;

final class RY_PAYUNi_Gateway_Credit_Installment_9_Blocks_Support extends RY_WTP_AbstractPaymentMethodType
{
    protected $name = RY_PAYUNi_Gateway_Credit_Installment_9::ID;

    public function initialize()
    {
        $this->settings = get_option('woocommerce_ry_payuni_credit_installment_9_settings', []);
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
        $script_asset = include RY_WTP_PLUGIN_DIR . 'assets/blocks/gateways/payuni/credit-installment-9.asset.php';

        wp_register_script('ry-payuni-credit-installment-9-block', RY_WTP_PLUGIN_URL . 'assets/blocks/gateways/payuni/credit-installment-9.js', $script_asset['dependencies'], $script_asset['version'], true);
        wp_localize_script('ry-payuni-credit-installment-9-block', 'RyPayuniCreditInstallment9BlockParams', [
            'defaultTitle' => __('PAYUNi Credit (9 installment)', 'ry-woocommerce-tools-pro'),
        ]);
        wp_set_script_translations('ry-payuni-credit-installment-9-block', 'ry-woocommerce-tools-pro', RY_WTP_PLUGIN_LANGUAGES_DIR);

        return ['ry-payuni-credit-installment-9-block'];
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
                'alt' => __('PAYUNi', 'ry-woocommerce-tools-pro'),
            ],
        ];
    }
}
