<?php

use Automattic\WooCommerce\Blocks\Payments\PaymentMethodRegistry;

final class RY_WTP_WC_ECPay_Gateway_Block
{
    protected static $_instance = null;

    public static function instance(): RY_WTP_WC_ECPay_Gateway_Block
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
            include_once RY_WTP_PLUGIN_DIR . 'woocommerce/gateways/ecpay/blocks/gateway-atm.php';
            include_once RY_WTP_PLUGIN_DIR . 'woocommerce/gateways/ecpay/blocks/gateway-barcode.php';
            include_once RY_WTP_PLUGIN_DIR . 'woocommerce/gateways/ecpay/blocks/gateway-bnpl.php';
            include_once RY_WTP_PLUGIN_DIR . 'woocommerce/gateways/ecpay/blocks/gateway-credit-installment.php';
            include_once RY_WTP_PLUGIN_DIR . 'woocommerce/gateways/ecpay/blocks/gateway-credit.php';
            include_once RY_WTP_PLUGIN_DIR . 'woocommerce/gateways/ecpay/blocks/gateway-cvs.php';
            include_once RY_WTP_PLUGIN_DIR . 'woocommerce/gateways/ecpay/blocks/gateway-twqr.php';
            include_once RY_WTP_PLUGIN_DIR . 'woocommerce/gateways/ecpay/blocks/gateway-webatm.php';
            if ('yes' === RY_WTP::get_option('ecpay_credit_installment', 'no')) {
                include_once RY_WTP_PLUGIN_DIR . 'woocommerce/gateways/ecpay/blocks/gateway-credit-installment-3.php';
                include_once RY_WTP_PLUGIN_DIR . 'woocommerce/gateways/ecpay/blocks/gateway-credit-installment-6.php';
                include_once RY_WTP_PLUGIN_DIR . 'woocommerce/gateways/ecpay/blocks/gateway-credit-installment-12.php';
                include_once RY_WTP_PLUGIN_DIR . 'woocommerce/gateways/ecpay/blocks/gateway-credit-installment-18.php';
                include_once RY_WTP_PLUGIN_DIR . 'woocommerce/gateways/ecpay/blocks/gateway-credit-installment-24.php';
            }

            add_action('woocommerce_blocks_payment_method_type_registration', [$this, 'register_block']);
        }
    }

    public function register_block(PaymentMethodRegistry $payment_method_registry)
    {
        $payment_method_registry->register(new RY_ECPay_Gateway_Atm_Blocks_Support());
        $payment_method_registry->register(new RY_ECPay_Gateway_Barcode_Blocks_Support());
        $payment_method_registry->register(new RY_ECPay_Gateway_Bnpl_Blocks_Support());
        $payment_method_registry->register(new RY_ECPay_Gateway_Credit_Installment_Blocks_Support());
        $payment_method_registry->register(new RY_ECPay_Gateway_Credit_Blocks_Support());
        $payment_method_registry->register(new RY_ECPay_Gateway_Cvs_Blocks_Support());
        $payment_method_registry->register(new RY_ECPay_Gateway_Twqr_Blocks_Support());
        $payment_method_registry->register(new RY_ECPay_Gateway_Webatm_Blocks_Support());
        if ('yes' === RY_WTP::get_option('ecpay_credit_installment', 'no')) {
            $payment_method_registry->register(new RY_ECPay_Gateway_Credit_Installment_3_Blocks_Support());
            $payment_method_registry->register(new RY_ECPay_Gateway_Credit_Installment_6_Blocks_Support());
            $payment_method_registry->register(new RY_ECPay_Gateway_Credit_Installment_12_Blocks_Support());
            $payment_method_registry->register(new RY_ECPay_Gateway_Credit_Installment_18_Blocks_Support());
            $payment_method_registry->register(new RY_ECPay_Gateway_Credit_Installment_24_Blocks_Support());
        }
    }
}
