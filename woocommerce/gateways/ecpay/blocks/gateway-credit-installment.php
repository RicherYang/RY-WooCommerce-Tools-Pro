<?php

use Automattic\WooCommerce\Blocks\Payments\PaymentContext;

final class RY_ECPay_Gateway_Credit_Installment_Blocks_Support extends RY_WTP_AbstractPaymentMethodType
{
    protected $name = 'ry_ecpay_credit_installment';

    public function __construct()
    {
        add_action('woocommerce_rest_checkout_process_payment_with_context', [$this, 'save_payment_data']);
    }

    public function save_payment_data(PaymentContext $context)
    {
        $data = $context->payment_data;
        $order = $context->order;
        if (! empty($data['number_of_period']) && $this->name === $context->payment_method) {
            $order->update_meta_data('_ecpay_payment_number_of_periods', (int) $data['number_of_period']);
            $order->save();
        }
    }

    public function initialize()
    {
        $this->settings = get_option('woocommerce_ry_ecpay_credit_installment_settings', []);
    }

    public function is_active()
    {
        return $this->get_gateway()->is_available();
    }

    public function get_payment_method_script_handles()
    {
        $script_asset = include RY_WTP_PLUGIN_DIR . 'assets/blocks/gateways/ecpay/credit-installment.asset.php';

        wp_register_script('ry-ecpay-credit-installment-block', RY_WTP_PLUGIN_URL . 'assets/blocks/gateways/ecpay/credit-installment.js', $script_asset['dependencies'], $script_asset['version'], true);
        wp_set_script_translations('ry-ecpay-credit-installment-block', 'ry-woocommerce-tools-pro', RY_WTP_PLUGIN_LANGUAGES_DIR);

        return ['ry-ecpay-credit-installment-block'];
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
            'number_of_periods' => $this->get_setting('number_of_periods'),
        ];
    }
}
