<?php

class RY_ECPay_Gateway_Credit_Installment_18 extends RY_ECPay_Gateway_Credit_Installment_Base
{
    public function __construct()
    {
        $this->id = 'ry_ecpay_credit_installment_18';
        $this->order_button_text = __('Pay via Credit(18 installment)', 'ry-woocommerce-tools-pro');
        $this->method_title = __('ECPay Credit(18 installment)', 'ry-woocommerce-tools-pro');

        $this->number_of_periods = 18;

        parent::__construct();
    }

    public function process_payment($order_ID)
    {
        $order = wc_get_order($order_ID);
        $order->add_order_note(__('Pay via ECPay Credit(18 installment)', 'ry-woocommerce-tools-pro'));
        $order->save();
        wc_maybe_reduce_stock_levels($order_ID);
        wc_release_stock_for_order($order);

        return [
            'result' => 'success',
            'redirect' => $order->get_checkout_payment_url(true),
        ];
    }
}
