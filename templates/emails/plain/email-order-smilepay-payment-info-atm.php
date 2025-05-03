<?php

/**
 * This template can be overridden by copying it to yourtheme/woocommerce/emails/plain/emails-order-smilepay-payment-info-atm.php
 *
 * HOWEVER, on occasion RY Tools (Pro) for WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @version 1.0.15
 */
defined('ABSPATH') || exit;
if ($order->get_payment_method() != 'ry_smilepay_atm') {
    return;
}

if ($order->get_meta('_smilepay_payment_type') != '2') {
    return;
}

echo "\n==========\n\n";

// phpcs:ignore WordPress.WP.I18n.TextDomainMismatch
echo wp_kses_post(__('Payment details', 'ry-woocommerce-tools')) . "\n";

// phpcs:ignore WordPress.WP.I18n.TextDomainMismatch
echo wp_kses_post(__('Bank', 'ry-woocommerce-tools') . "\t " . rywt_bank_code_to_name($order->get_meta('_smilepay_atm_BankCode'))) . "\n";
// phpcs:ignore WordPress.WP.I18n.TextDomainMismatch
echo wp_kses_post(__('Bank code', 'ry-woocommerce-tools') . "\t " . $order->get_meta('_smilepay_atm_BankCode')) . "\n";
// phpcs:ignore WordPress.WP.I18n.TextDomainMismatch
echo wp_kses_post(__('ATM Bank account', 'ry-woocommerce-tools') . "\t " . wordwrap($order->get_meta('_smilepay_atm_vAccount'), 4, '<span> </span>', true)) . "\n";
$expireDate = wc_string_to_datetime($order->get_meta('_smilepay_atm_ExpireDate'));
$expireDate = $expireDate->date_i18n(wc_date_format());
// phpcs:ignore WordPress.WP.I18n.TextDomainMismatch
echo wp_kses_post(__('Payment deadline', 'ry-woocommerce-tools') . "\t " . $expireDate) . "\n";
