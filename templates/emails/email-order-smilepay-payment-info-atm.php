<?php
/**
 * This template can be overridden by copying it to yourtheme/woocommerce/emails/email-order-smilepay-payment-info-atm.php
 *
 * HOWEVER, on occasion RY WooCommerce Tools will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @version 1.2.0
 */
defined('ABSPATH') || exit;
if ($order->get_payment_method() != 'ry_smilepay_atm') {
    return;
}

if ($order->get_meta('_smilepay_payment_type') != '2') {
    return;
}

$text_align = is_rtl() ? 'right' : 'left';
?>
<h2>
	<?=__('Payment details', 'ry-woocommerce-tools') ?>
</h2>
<div style="margin-bottom: 40px;">
	<table class="td" cellspacing="0" cellpadding="6" border="1" style="width: 100%;">
		<tbody>
			<tr>
				<th class="td" scope="row" style="text-align:<?=esc_attr($text_align) ?>;">
					<?=__('Bank', 'ry-woocommerce-tools') ?>
				</th>
				<td class="td" style="text-align:<?=esc_attr($text_align) ?>;"><?=_x($order->get_meta('_smilepay_atm_BankCode'), 'Bank code', 'ry-woocommerce-tools') ?>
				</td>
			</tr>
			<tr>
				<th class="td" scope="row" style="text-align:<?=esc_attr($text_align) ?>;">
					<?=__('Bank code', 'ry-woocommerce-tools') ?>
				</th>
				<td class="td" style="text-align:<?=esc_attr($text_align) ?>;">
					<?=$order->get_meta('_smilepay_atm_BankCode') ?>
				</td>
			</tr>
			<tr>
				<th class="td" scope="row" style="text-align:<?=esc_attr($text_align) ?>;">
					<?=__('ATM Bank account', 'ry-woocommerce-tools') ?>
				</th>
				<td class="td" style="text-align:<?=esc_attr($text_align) ?>;">
					<?=wordwrap($order->get_meta('_smilepay_atm_vAccount'), 4, '<span> </span>', true) ?>
				</td>
			</tr>
			<tr>
				<th class="td" scope="row" style="text-align:<?=esc_attr($text_align) ?>;">
					<?=__('Payment deadline', 'ry-woocommerce-tools') ?>
				</th>
				<?php $expireDate = wc_string_to_datetime($order->get_meta('_smilepay_atm_ExpireDate')); ?>
				<?php $expireDate = $expireDate->date_i18n(wc_date_format()); ?>
				<td class="td" style="text-align:<?=esc_attr($text_align) ?>;">
					<?=$expireDate ?>
				</td>
			</tr>
		</tbody>
	</table>
</div>
