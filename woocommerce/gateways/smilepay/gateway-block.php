<?php

use Automattic\WooCommerce\Blocks\Payments\PaymentMethodRegistry;

final class RY_WTP_WC_SmilePay_Gateway_Block
{
    protected static $_instance = null;

    public static function instance(): RY_WTP_WC_SmilePay_Gateway_Block
    {
        if (null === self::$_instance) {
            self::$_instance = new self();
            self::$_instance->do_init();
        }

        return self::$_instance;
    }

    protected function do_init(): void
    {
        $this->add_block_support();
    }

    public function add_block_support()
    {
        if (class_exists('Automattic\WooCommerce\Blocks\Payments\Integrations\AbstractPaymentMethodType')) {
            include_once RY_WTP_PLUGIN_DIR . 'woocommerce/gateways/smilepay/blocks/gateway-atm.php';
            include_once RY_WTP_PLUGIN_DIR . 'woocommerce/gateways/smilepay/blocks/gateway-barcode.php';
            include_once RY_WTP_PLUGIN_DIR . 'woocommerce/gateways/smilepay/blocks/gateway-credit.php';
            include_once RY_WTP_PLUGIN_DIR . 'woocommerce/gateways/smilepay/blocks/gateway-cvs-711.php';
            include_once RY_WTP_PLUGIN_DIR . 'woocommerce/gateways/smilepay/blocks/gateway-cvs-fami.php';
            include_once RY_WTP_PLUGIN_DIR . 'woocommerce/gateways/smilepay/blocks/gateway-webatm.php';

            add_action('woocommerce_blocks_payment_method_type_registration', [$this, 'register_block']);
        }
    }

    public function register_block(PaymentMethodRegistry $payment_method_registry)
    {
        $payment_method_registry->register(new RY_SmilePay_Gateway_Atm_Blocks_Support());
        $payment_method_registry->register(new RY_SmilePay_Gateway_Barcode_Blocks_Support());
        $payment_method_registry->register(new RY_SmilePay_Gateway_Credit_Blocks_Support());
        $payment_method_registry->register(new RY_SmilePay_Gateway_Cvs_711_Blocks_Support());
        $payment_method_registry->register(new RY_SmilePay_Gateway_Cvs_Fami_Blocks_Support());
        $payment_method_registry->register(new RY_SmilePay_Gateway_Webatm_Blocks_Support());
    }
}
