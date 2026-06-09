<?php defined('ABSPATH') or exit; ?>

<p>
    <strong><?php esc_html_e('Credit trade status', 'ry-woocommerce-tools-pro'); ?></strong><br>
    <?php echo esc_html(rywtp_payuni_CloseStatus_name($info['CloseStatus'])); ?>
</p>
<p>
    <strong><?php esc_html_e('Trade amount', 'ry-woocommerce-tools-pro'); ?></strong><br>
    <?php echo esc_html($info['TradeAmt']); ?>
</p>
<p>
    <strong><?php esc_html_e('Received amount', 'ry-woocommerce-tools-pro'); ?></strong><br>
    <?php echo esc_html($info['RemainAmt']); ?>
</p>
<?php if ($info['CloseStatus'] === '1' || $info['CloseStatus'] === '9') { ?>
<p>
    <button class="button refund-action" type="button" data-refund="cancel" data-orderid="<?php echo esc_attr($order->get_id()); ?>"><?php echo esc_html__('Cancel authorization', 'ry-woocommerce-tools-pro'); ?></button>
    &emsp;
    <button class="button refund-action" type="button" data-refund="closure" data-orderid="<?php echo esc_attr($order->get_id()); ?>"><?php echo esc_html__('Closure', 'ry-woocommerce-tools-pro'); ?></button>
</p>
<?php } ?>
<?php if ($info['CloseStatus'] === '2') { ?>
<p>
    <input type="text" class="small-text" id="ry-refund-amount">
    <button class="button refund-action" type="button" data-refund="refund" data-orderid="<?php echo esc_attr($order->get_id()); ?>"><?php echo esc_html__('Refund', 'ry-woocommerce-tools-pro'); ?></button>
</p>
<?php } ?>
