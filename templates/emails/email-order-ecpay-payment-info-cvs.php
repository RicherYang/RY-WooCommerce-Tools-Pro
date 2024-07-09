<?php
/**
 * This template can be overridden by copying it to yourtheme/woocommerce/emails/email-order-ecpay-payment-info-cvs.php
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
if ($order->get_payment_method() != 'ry_ecpay_cvs') {
    return;
}

if ($order->get_meta('_ecpay_payment_type') != 'CVS') {
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
                    <?php esc_html_e('CVS code', 'ry-woocommerce-tools'); ?>
                </th>
                <td class="td" style="text-align:<?php echo esc_attr($text_align); ?>;">
                    <?php echo esc_html($order->get_meta('_ecpay_cvs_PaymentNo')); ?>
                </td>
            </tr>
            <tr>
                <th class="td" scope="row" style="text-align:<?php echo esc_attr($text_align); ?>;">
                    <?php esc_html_e('Payment deadline', 'ry-woocommerce-tools'); ?>
                </th>
                <?php $expireDate = wc_string_to_datetime($order->get_meta('_ecpay_cvs_ExpireDate')); ?>
                <td class="td" style="text-align:<?php echo esc_attr($text_align); ?>;">
                    <?php echo esc_html(sprintf(
                        /* translators: %1$s: date %2$s: time */
                        _x('%1$s %2$s', 'Datetime', 'ry-woocommerce-tools'),
                        $expireDate->date_i18n(wc_date_format()),
                        $expireDate->date_i18n(wc_time_format()),
                    )); ?>
                </td>
            </tr>
        </tbody>
    </table>
</div>
