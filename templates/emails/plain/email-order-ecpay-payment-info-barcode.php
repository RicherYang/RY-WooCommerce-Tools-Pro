<?php

/**
 * This template can be overridden by copying it to yourtheme/woocommerce/emails/plain/emails-order-ecpay-payment-info-barcode.php
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
if ($order->get_payment_method() != 'ry_ecpay_barcode') {
    return;
}

if ($order->get_meta('_ecpay_payment_type') != 'BARCODE') {
    return;
}

echo "\n==========\n\n";

echo wp_kses_post(__('Payment details', 'ry-woocommerce-tools')) . "\n";

echo wp_kses_post(__('Barcode 1', 'ry-woocommerce-tools') . "\t " . $order->get_meta('_ecpay_barcode_Barcode1')) . "\n";
echo wp_kses_post(__('Barcode 2', 'ry-woocommerce-tools') . "\t " . $order->get_meta('_ecpay_barcode_Barcode2')) . "\n";
echo wp_kses_post(__('Barcode 3', 'ry-woocommerce-tools') . "\t " . $order->get_meta('_ecpay_barcode_Barcode3')) . "\n";
$expireDate = wc_string_to_datetime($order->get_meta('_ecpay_barcode_ExpireDate'));
$expireDate = sprintf(
    /* translators: %1$s: date %2$s: time */
    _x('%1$s %2$s', 'Datetime', 'ry-woocommerce-tools'),
    $expireDate->date_i18n(wc_date_format()),
    $expireDate->date_i18n(wc_time_format()),
);
echo wp_kses_post(__('Payment deadline', 'ry-woocommerce-tools') . "\t " . $expireDate) . "\n";
