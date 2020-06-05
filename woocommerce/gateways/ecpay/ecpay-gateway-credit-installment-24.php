<?php
defined('RY_WT_VERSION') or exit('No direct script access allowed');

class RY_ECPay_Gateway_Credit_Installment_24 extends RY_ECPay_Gateway_Credit_Installment_Base
{
    public function __construct()
    {
        $this->id = 'ry_ecpay_credit_installment_24';
        $this->order_button_text = __('Pay via Credit(24 installment)', 'ry-woocommerce-tools-pro');
        $this->method_title = __('ECPay Credit(24 installment)', 'ry-woocommerce-tools-pro');

        $this->number_of_periods = 24;

        parent::__construct();
    }

    public function process_payment($order_id)
    {
        $order = wc_get_order($order_id);
        $order->add_order_note(__('Pay via ECPay Credit(24 installment)', 'ry-woocommerce-tools-pro'));
        $order->save();
        wc_reduce_stock_levels($order_id);

        return [
            'result'   => 'success',
            'redirect' => $order->get_checkout_payment_url(true),
        ];
    }
}
