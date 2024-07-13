<?php
/**
 * This template can be overridden by copying it to yourtheme/woocommerce/emails/email-order-newebpay-payment-info-atm.php
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
if ($order->get_payment_method() != 'ry_newebpay_atm') {
    return;
}

if ($order->get_meta('_newebpay_payment_type') != 'VACC') {
    return;
}

$text_align = is_rtl() ? 'right' : 'left';
?>
<h2>
    <?php esc_html_e('Payment details', 'ry-woocommerce-tools'); ?>
</h2>
<div style="margin-bottom: 40px;">
    <table class="td" cellspacing="0" cellpadding="6" style="width: 100%;" border="1">
        <tbody>
            <tr>
                <th class="td" scope="row" style="text-align:<?php echo esc_attr($text_align); ?>;">
                    <?php esc_html_e('Bank', 'ry-woocommerce-tools'); ?>
                </th>
                <td class="td" style="text-align:<?php echo esc_attr($text_align); ?>;">
                    <?php echo esc_html(rywt_bank_code_to_name($order->get_meta('_newebpay_atm_BankCode'))); ?>
                </td>
            </tr>
            <tr>
                <th class="td" scope="row" style="text-align:<?php echo esc_attr($text_align); ?>;">
                    <?php esc_html_e('Bank code', 'ry-woocommerce-tools'); ?>
                </th>
                <td class="td" style="text-align:<?php echo esc_attr($text_align); ?>;">
                    <?php echo esc_html($order->get_meta('_newebpay_atm_BankCode')); ?>
                </td>
            </tr>
            <tr>
                <th class="td" scope="row" style="text-align:<?php echo esc_attr($text_align); ?>;">
                    <?php esc_html_e('ATM Bank account', 'ry-woocommerce-tools'); ?>
                </th>
                <td class="td" style="text-align:<?php echo esc_attr($text_align); ?>;">
                    <?php echo wordwrap($order->get_meta('_newebpay_atm_vAccount'), 4, '<span> </span>', true); ?>
                </td>
            </tr>
            <tr>
                <th class="td" scope="row" style="text-align:<?php echo esc_attr($text_align); ?>;">
                    <?php esc_html_e('Payment deadline', 'ry-woocommerce-tools'); ?>
                </th>
                <?php $expireDate = wc_string_to_datetime($order->get_meta('_newebpay_atm_ExpireDate')); ?>
                <td class="td" style="text-align:<?php echo esc_attr($text_align); ?>;">
                    <?php echo esc_html($expireDate->date_i18n(wc_date_format())); ?>
                </td>
            </tr>
        </tbody>
    </table>
</div>
