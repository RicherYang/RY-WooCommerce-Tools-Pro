<?php

defined('ABSPATH') or exit;

use Automattic\WooCommerce\Blocks\Payments\PaymentMethodRegistry;

final class RY_WTP_WC_PAYUNi_Gateway_Block
{
    protected static ?self $_instance = null;

    public static function instance(): RY_WTP_WC_PAYUNi_Gateway_Block
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
            include_once RY_WTP_PLUGIN_DIR . 'woocommerce/abstracts/abstract-payment-method-type.php';

            include_once RY_WTP_PLUGIN_DIR . 'woocommerce/gateways/payuni/blocks/gateway-bnpl.php';
            include_once RY_WTP_PLUGIN_DIR . 'woocommerce/gateways/payuni/blocks/gateway-atm.php';
            include_once RY_WTP_PLUGIN_DIR . 'woocommerce/gateways/payuni/blocks/gateway-credit.php';
            include_once RY_WTP_PLUGIN_DIR . 'woocommerce/gateways/payuni/blocks/gateway-cvs.php';

            if ('yes' === RY_WTP::get_option('payuni_independent_credit_installment', 'no')) {
                include_once RY_WTP_PLUGIN_DIR . 'woocommerce/gateways/payuni/blocks/gateway-credit-installment-3.php';
                include_once RY_WTP_PLUGIN_DIR . 'woocommerce/gateways/payuni/blocks/gateway-credit-installment-6.php';
                include_once RY_WTP_PLUGIN_DIR . 'woocommerce/gateways/payuni/blocks/gateway-credit-installment-9.php';
                include_once RY_WTP_PLUGIN_DIR . 'woocommerce/gateways/payuni/blocks/gateway-credit-installment-12.php';
                include_once RY_WTP_PLUGIN_DIR . 'woocommerce/gateways/payuni/blocks/gateway-credit-installment-18.php';
                include_once RY_WTP_PLUGIN_DIR . 'woocommerce/gateways/payuni/blocks/gateway-credit-installment-24.php';
                include_once RY_WTP_PLUGIN_DIR . 'woocommerce/gateways/payuni/blocks/gateway-credit-installment-30.php';
            } else {
                include_once RY_WTP_PLUGIN_DIR . 'woocommerce/gateways/payuni/blocks/gateway-credit-installment.php';
            }

            if ('yes' === RY_WTP::get_option('payuni_independent_digital', 'no')) {
                include_once RY_WTP_PLUGIN_DIR . 'woocommerce/gateways/payuni/blocks/gateway-icash.php';
                include_once RY_WTP_PLUGIN_DIR . 'woocommerce/gateways/payuni/blocks/gateway-jkopay.php';
                include_once RY_WTP_PLUGIN_DIR . 'woocommerce/gateways/payuni/blocks/gateway-linepay.php';
            } else {
                include_once RY_WTP_PLUGIN_DIR . 'woocommerce/gateways/payuni/blocks/gateway-digital.php';
            }

            add_action('woocommerce_blocks_payment_method_type_registration', [$this, 'register_block']);
        }
    }

    public function register_block(PaymentMethodRegistry $payment_method_registry)
    {
        $payment_method_registry->register(new RY_PAYUNi_Gateway_Bnpl_Blocks_Support());
        $payment_method_registry->register(new RY_PAYUNi_Gateway_Atm_Blocks_Support());
        $payment_method_registry->register(new RY_PAYUNi_Gateway_Credit_Blocks_Support());
        $payment_method_registry->register(new RY_PAYUNi_Gateway_Cvs_Blocks_Support());

        if ('yes' === RY_WTP::get_option('payuni_independent_credit_installment', 'no')) {
            $payment_method_registry->register(new RY_PAYUNi_Gateway_Credit_Installment_3_Blocks_Support());
            $payment_method_registry->register(new RY_PAYUNi_Gateway_Credit_Installment_6_Blocks_Support());
            $payment_method_registry->register(new RY_PAYUNi_Gateway_Credit_Installment_9_Blocks_Support());
            $payment_method_registry->register(new RY_PAYUNi_Gateway_Credit_Installment_12_Blocks_Support());
            $payment_method_registry->register(new RY_PAYUNi_Gateway_Credit_Installment_18_Blocks_Support());
            $payment_method_registry->register(new RY_PAYUNi_Gateway_Credit_Installment_24_Blocks_Support());
            $payment_method_registry->register(new RY_PAYUNi_Gateway_Credit_Installment_30_Blocks_Support());
        } else {
            $payment_method_registry->register(new RY_PAYUNi_Gateway_Credit_Installment_Blocks_Support());
        }

        if ('yes' === RY_WTP::get_option('payuni_independent_digital', 'no')) {
            $payment_method_registry->register(new RY_PAYUNi_Gateway_Icash_Blocks_Support());
            $payment_method_registry->register(new RY_PAYUNi_Gateway_Jkopay_Blocks_Support());
            $payment_method_registry->register(new RY_PAYUNi_Gateway_Linepay_Blocks_Support());
        } else {
            $payment_method_registry->register(new RY_PAYUNi_Gateway_Digital_Blocks_Support());
        }
    }
}
