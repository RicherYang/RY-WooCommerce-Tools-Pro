<div class="wc-order-preview-addresses">
    <div class="wc-order-preview-address">
        <h2><?php esc_html_e('Order info', 'ry-woocommerce-tools-pro'); ?></h2>
        <strong><?php esc_html_e('Trade no', 'ry-woocommerce-tools-pro'); ?></strong>
        <?php echo esc_html($info['MerchantTradeNo']); ?>
        <strong><?php esc_html_e('ECPay trade no', 'ry-woocommerce-tools-pro'); ?></strong>
        <?php echo esc_html($info['AllPayLogisticsID']); ?>
        <strong><?php esc_html_e('ECPay trade date', 'ry-woocommerce-tools-pro'); ?></strong>
        <?php echo esc_html($info['TradeDate']); ?>
        <strong><?php esc_html_e('ECPay trade status', 'ry-woocommerce-tools-pro'); ?></strong>
        <?php echo esc_html(rywt_ecpay_info_to_name('status-' . $info['LogisticsStatus'])); ?>

        <strong><?php esc_html_e('Sander name', 'ry-woocommerce-tools-pro'); ?></strong>
        <?php echo esc_html($info['SenderName']); ?>
        <strong><?php esc_html_e('Sander phone', 'ry-woocommerce-tools-pro'); ?></strong>
        <?php echo esc_html($info['SenderPhone']); ?>
        <strong><?php esc_html_e('Sander Cellphone', 'ry-woocommerce-tools-pro'); ?></strong>
        <?php echo esc_html($info['SenderCellPhone']); ?>
    </div>

    <div class="wc-order-preview-address">
        <h2><?php esc_html_e('Shipping info', 'ry-woocommerce-tools-pro'); ?></h2>
        <strong><?php esc_html_e('Shipping type', 'ry-woocommerce-tools-pro'); ?></strong>
        <?php echo esc_html(rywt_ecpay_info_to_name('shipping-' . $info['LogisticsType'])); ?>
        <strong><?php esc_html_e('Goods name', 'ry-woocommerce-tools-pro'); ?></strong>
        <?php echo esc_html($info['GoodsName']); ?>
        <strong><?php esc_html_e('Declare amount', 'ry-woocommerce-tools-pro'); ?></strong>
        <?php echo esc_html($info['GoodsAmount']); ?>
        <strong><?php esc_html_e('Shipping fee', 'ry-woocommerce-tools-pro'); ?></strong>
        <?php echo esc_html($info['HandlingCharge']); ?>
        <strong><?php esc_html_e('Shipping fee date', 'ry-woocommerce-tools-pro'); ?></strong>
        <?php echo esc_html($info['ShipChargeDate']); ?>

        <?php if ($info['CollectionAmount'] > 0) { ?>
            <strong><?php esc_html_e('Collection amount', 'ry-woocommerce-tools-pro'); ?></strong>
            <?php echo esc_html($info['CollectionAmount']); ?>
            <strong><?php esc_html_e('Collection charge fee', 'ry-woocommerce-tools-pro'); ?></strong>
            <?php echo esc_html($info['CollectionChargeFee']); ?>
            <strong><?php esc_html_e('Collection allocate amount', 'ry-woocommerce-tools-pro'); ?></strong>
            <?php echo esc_html($info['CollectionAllocateAmount']); ?>
            <strong><?php esc_html_e('Collection allocate date', 'ry-woocommerce-tools-pro'); ?></strong>
            <?php echo esc_html($info['CollectionAllocateDate']); ?>
        <?php } ?>

        <?php if (str_starts_with($info['LogisticsType'], 'CVS_')) { ?>
        <strong><?php esc_html_e('Shipping no', 'ry-woocommerce-tools-pro'); ?></strong>
        <?php if (str_ends_with($info['LogisticsType'], 'C2C')) { ?>
        <?php echo esc_html($info['CVSPaymentNo'] . $info['CVSValidationNo']); ?>
        <?php } else { ?>
        <?php echo esc_html($info['ShipmentNo']); ?>
        <?php } ?>
        <?php } ?>
        <?php if (str_starts_with($info['LogisticsType'], 'HOME_')) { ?>
        <strong><?php esc_html_e('Shipping no', 'ry-woocommerce-tools-pro'); ?></strong>
        <?php echo esc_html($info['BookingNote']); ?>
        <?php } ?>

        <?php if ($info['LogisticsType'] == 'HOME_POST') { ?>
        <strong><?php esc_html_e('Declare weight', 'ry-woocommerce-tools-pro'); ?></strong>
        <?php echo esc_html($info['GoodsWeight']); ?>
        <strong><?php esc_html_e('Package weight', 'ry-woocommerce-tools-pro'); ?></strong>
        <?php echo esc_html($info['ActualWeight']); ?>
        <?php } ?>
    </div>
</div>
