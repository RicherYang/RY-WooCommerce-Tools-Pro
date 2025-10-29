<p>
    <strong><?php esc_html_e('Credit trade status', 'ry-woocommerce-tools-pro'); ?></strong><br>
    <?php echo esc_html(rywtp_ecpay_info_to_name($info['RtnValue']['Status'])); ?>
</p>
<p>
    <strong><?php esc_html_e('Trade amount', 'ry-woocommerce-tools-pro'); ?></strong><br>
    <?php echo esc_html($info['RtnValue']['Amount']); ?>
</p>
<p>
    <strong><?php esc_html_e('Closed amount', 'ry-woocommerce-tools-pro'); ?></strong><br>
    <?php echo esc_html($info['RtnValue']['ClsAmt']); ?>
</p>
<p>
    <strong><?php esc_html_e('Trade details', 'ry-woocommerce-tools-pro'); ?></strong><br>
    <?php foreach ($info['CloseData'] as $history) {
        echo esc_html(sprintf(
            /* translators: %1$s: status %2$s: amount */
            __('Status: %1$s Amount: %2$s', 'ry-woocommerce-tools-pro'),
            rywtp_ecpay_info_to_name($history['Status']),
            $history['Amount']
        ));
        echo '<br>';
        echo esc_html(sprintf(
            /* translators: %s: date */
            __('Date: %s', 'ry-woocommerce-tools-pro'),
            $history['DateTime']
        ));
        echo '<br>';
    } ?>
</p>
<?php if ($info['RtnValue']['Status'] === 'Authorized') { ?>
<p>
    <button class="button refound-action" type="button" data-refound="cancel" data-orderid="<?php echo esc_attr($order->get_id()); ?>"><?php echo esc_html__('Cancel authorization', 'ry-woocommerce-tools-pro'); ?></button>
    &emsp;
    <button class="button refound-action" type="button" data-refound="closure" data-orderid="<?php echo esc_attr($order->get_id()); ?>"><?php echo esc_html__('Closure', 'ry-woocommerce-tools-pro'); ?></button>
</p>
<?php } ?>
<?php if ($info['RtnValue']['Status'] === 'Captured' || $info['RtnValue']['Status'] === 'To be captured') { ?>
<p>
    <input type="text" class="small-text" id="ry-refound-amount">
    <button class="button refound-action" type="button" data-refound="refound" data-orderid="<?php echo esc_attr($order->get_id()); ?>"><?php echo esc_html__('Refound', 'ry-woocommerce-tools-pro'); ?></button>
</p>
<?php } ?>
