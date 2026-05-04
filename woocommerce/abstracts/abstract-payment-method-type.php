<?php

defined('ABSPATH') or exit;

use Automattic\WooCommerce\Blocks\Payments\Integrations\AbstractPaymentMethodType;

abstract class RY_WTP_AbstractPaymentMethodType extends AbstractPaymentMethodType
{
    private $gateway;

    protected function get_gateway()
    {
        if (null === $this->gateway) {
            $payment_gateways = WC()->payment_gateways->payment_gateways();
            $this->gateway = $payment_gateways[$this->name];
        }

        return $this->gateway;
    }
}
