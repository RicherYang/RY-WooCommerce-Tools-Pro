<?php

defined('ABSPATH') or exit;

class RY_PAYUNi_Gateway_Credit_Installment_9 extends RY_PAYUNi_Gateway_Credit_Installment_Base
{
    public function __construct()
    {
        $this->id = 'ry_payuni_credit_installment_9';
        $this->order_button_text = __('Pay via Credit(9 installment)', 'ry-woocommerce-tools-pro');
        $this->method_title = __('PAYUNi Credit(9 installment)', 'ry-woocommerce-tools-pro');

        $this->number_of_periods = 9;

        parent::__construct();
    }

    public function process_payment($order_ID)
    {
        $order = wc_get_order($order_ID);
        $order->add_order_note(__('Pay via PAYUNi Credit(9 installment)', 'ry-woocommerce-tools-pro'));
        $order->save();
        wc_maybe_reduce_stock_levels($order_ID);
        wc_release_stock_for_order($order);

        return [
            'result' => 'success',
            'redirect' => $order->get_checkout_payment_url(true),
        ];
    }
}
