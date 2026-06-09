<?php defined('ABSPATH') or exit; ?>

<p>
    <strong><?php esc_html_e('Trade amount', 'ry-woocommerce-tools-pro'); ?></strong><br>
    <?php echo esc_html($info['TradeAmt']); ?>
</p>
<p>
    <input type="text" class="small-text" id="ry-refund-amount">
    <button class="button refund-action" type="button" data-refund="refund" data-orderid="<?php echo esc_attr($order->get_id()); ?>"><?php echo esc_html__('Refund', 'ry-woocommerce-tools-pro'); ?></button>
</p>
