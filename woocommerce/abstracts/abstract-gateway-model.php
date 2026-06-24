<?php

defined('ABSPATH') or exit;

abstract class RY_WTP_Gateway_Model
{
    public function add_payment_info($order, $sent_to_admin, $plain_text, $email)
    {
        static $added = false;

        if ($added) {
            return;
        }
        if ($email->id !== 'customer_on_hold_order') {
            return;
        }

        $payment_method = $order->get_payment_method();
        $payment_gateways = WC()->payment_gateways()->payment_gateways();
        if (!isset($payment_gateways[$payment_method])) {
            return;
        }

        $gateway = $payment_gateways[$payment_method];
        if (defined(get_class($gateway) . '::INFO_TEMPLATE') && $gateway::INFO_TEMPLATE) {
            $args = [
                'order' => $order,
                'sent_to_admin' => $sent_to_admin,
                'plain_text' => $plain_text,
                'email' => $email,
            ];
            if ($plain_text) {
                if (file_exists(RY_WTP_PLUGIN_DIR . 'templates/emails/plain/email-' . $gateway::INFO_TEMPLATE)) {
                    $added = true;
                    wc_get_template('emails/plain/email-' . $gateway::INFO_TEMPLATE, $args, '', RY_WTP_PLUGIN_DIR . 'templates/');
                }
            } else {
                if (file_exists(RY_WTP_PLUGIN_DIR . 'templates/emails/email-' . $gateway::INFO_TEMPLATE)) {
                    $added = true;
                    wc_get_template('emails/email-' . $gateway::INFO_TEMPLATE, $args, '', RY_WTP_PLUGIN_DIR . 'templates/');
                }
            }
        }
    }
}
