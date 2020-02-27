<?php
defined('RY_WT_VERSION') OR exit('No direct script access allowed');

class RY_NewebPay_Gateway_Credit_Installment_12 extends RY_NewebPay_Gateway_Credit_Installment_Base {

	public function __construct() {
		$this->id = 'ry_newebpay_credit_installment_12';
		$this->order_button_text = __('Pay via Credit(12 installment)', 'ry-woocommerce-tools-pro');
		$this->method_title = __('NewebPay Credit(12 installment)', 'ry-woocommerce-tools-pro');

		$this->number_of_periods = 12;

		parent::__construct();
	}

	public function process_payment($order_id) {
		$order = wc_get_order($order_id);
		$order->add_order_note(__('Pay via NewebPay Credit(12 installment)', 'ry-woocommerce-tools-pro'));
		$order->save();
		wc_reduce_stock_levels($order_id);

		return [
			'result'   => 'success',
			'redirect' => $order->get_checkout_payment_url(true),
		];
	}
}
