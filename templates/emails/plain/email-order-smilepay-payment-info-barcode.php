<?php

/**
 * This template can be overridden by copying it to yourtheme/woocommerce/emails/plain/emails-order-smilepay-payment-info-barcode.php
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
if ($order->get_payment_method() != 'ry_smilepay_barcode') {
    return;
}

if ($order->get_meta('_smilepay_payment_type') != '3') {
    return;
}

echo "\n==========\n\n";

// phpcs:ignore WordPress.WP.I18n.TextDomainMismatch
echo wp_kses_post(__('Payment details', 'ry-woocommerce-tools')) . "\n";

// phpcs:ignore WordPress.WP.I18n.TextDomainMismatch
echo wp_kses_post(__('Barcode 1', 'ry-woocommerce-tools') . "\t " . $order->get_meta('_smilepay_barcode_Barcode1')) . "\n";
// phpcs:ignore WordPress.WP.I18n.TextDomainMismatch
echo wp_kses_post(__('Barcode 2', 'ry-woocommerce-tools') . "\t " . $order->get_meta('_smilepay_barcode_Barcode2')) . "\n";
// phpcs:ignore WordPress.WP.I18n.TextDomainMismatch
echo wp_kses_post(__('Barcode 3', 'ry-woocommerce-tools') . "\t " . $order->get_meta('_smilepay_barcode_Barcode3')) . "\n";
$expireDate = wc_string_to_datetime($order->get_meta('_smilepay_barcode_ExpireDate'));
$expireDate = sprintf(
    /* translators: %1$s: date %2$s: time */
    _x('%1$s %2$s', 'Datetime', 'ry-woocommerce-tools'),// phpcs:ignore WordPress.WP.I18n.TextDomainMismatch
    $expireDate->date_i18n(wc_date_format()),
    $expireDate->date_i18n(wc_time_format()),
);
// phpcs:ignore WordPress.WP.I18n.TextDomainMismatch
echo wp_kses_post(__('Payment deadline', 'ry-woocommerce-tools') . "\t " . $expireDate) . "\n";
