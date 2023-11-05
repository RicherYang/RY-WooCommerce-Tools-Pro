<?php

use Automattic\WooCommerce\Blocks\Integrations\IntegrationRegistry;

final class RY_WTP_ECPay_Shipping_Block
{
    protected static $_instance = null;

    public static function instance(): RY_WTP_ECPay_Shipping_Block
    {
        if (null === self::$_instance) {
            self::$_instance = new self();
            self::$_instance->do_init();
        }

        return self::$_instance;
    }

    protected function do_init(): void
    {
        add_action('woocommerce_blocks_loaded', [$this, 'add_block_support']);

        add_action('woocommerce_store_api_cart_select_shipping_rate', [$this, 'set_change_address']);
    }

    public function add_block_support()
    {
        if (interface_exists('Automattic\WooCommerce\Blocks\Integrations\IntegrationInterface')) {
            include_once RY_WTP_PLUGIN_DIR . 'woocommerce/shipping/ecpay/blocks/ecpay-cvs-select-block.php';

            add_action('woocommerce_blocks_checkout_block_registration', [$this, 'register_block']);
        }
    }

    public function register_block(IntegrationRegistry $integration_registry)
    {
        $integration_registry->register(new RY_ECPay_Shipping_Cvs_Select_Block());
    }

    public function set_change_address()
    {
        $cvs_info = (array) WC()->session->get('ry-ecpay-cvs-info', []);
        if(isset($cvs_info['shipping_methods']) && $cvs_info['shipping_methods'] === WC()->session->get('chosen_shipping_methods')) {

        }
    }
}
