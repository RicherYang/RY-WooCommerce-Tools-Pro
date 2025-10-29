<div style="display:flex; flex-wrap:wrap;">
    <div style="flex:1 0 0; width:100%">
        <h2><?php esc_html_e('Order info', 'ry-woocommerce-tools-pro'); ?></h2>
        <p>
            <strong><?php esc_html_e('Trade no', 'ry-woocommerce-tools-pro'); ?></strong><br>
            <?php echo esc_html($info['MerchantTradeNo']); ?>
        </p>
        <p>
            <strong><?php esc_html_e('ECPay trade no', 'ry-woocommerce-tools-pro'); ?></strong><br>
            <?php echo esc_html($info['AllPayLogisticsID']); ?>
        </p>
        <p>
            <strong><?php esc_html_e('ECPay trade date', 'ry-woocommerce-tools-pro'); ?></strong><br>
            <?php echo esc_html($info['TradeDate']); ?>
        </p>
        <p>
            <strong><?php esc_html_e('ECPay trade status', 'ry-woocommerce-tools-pro'); ?></strong><br>
            <?php echo esc_html(rywtp_ecpay_status_to_name($info['LogisticsStatus'])); ?>
        </p>
        <p>
            <strong><?php esc_html_e('Sander name', 'ry-woocommerce-tools-pro'); ?></strong><br>
            <?php echo esc_html($info['SenderName']); ?>
        </p>
        <p>
            <strong><?php esc_html_e('Sander phone', 'ry-woocommerce-tools-pro'); ?></strong><br>
            <?php echo esc_html($info['SenderPhone']); ?>
        </p>
        <p>
            <strong><?php esc_html_e('Sander Cellphone', 'ry-woocommerce-tools-pro'); ?></strong><br>
            <?php echo esc_html($info['SenderCellPhone']); ?>
        </p>
    </div>

    <div style="flex:1 0 0; width:100%">
        <h2><?php esc_html_e('Shipping info', 'ry-woocommerce-tools-pro'); ?></h2>
        <p>
            <strong><?php esc_html_e('Shipping type', 'ry-woocommerce-tools-pro'); ?></strong><br>
            <?php echo esc_html(rywtp_ecpay_shipping_to_name($info['LogisticsType'])); ?>
        </p>
        <p>
            <strong><?php esc_html_e('Goods name', 'ry-woocommerce-tools-pro'); ?></strong><br>
            <?php echo esc_html($info['GoodsName']); ?>
        </p>
        <p>
            <strong><?php esc_html_e('Declare amount', 'ry-woocommerce-tools-pro'); ?></strong><br>
            <?php echo esc_html($info['GoodsAmount']); ?>
        </p>
        <p>
            <strong><?php esc_html_e('Shipping fee', 'ry-woocommerce-tools-pro'); ?></strong><br>
            <?php echo esc_html($info['HandlingCharge']); ?>
        </p>
        <p>
            <strong><?php esc_html_e('Shipping fee date', 'ry-woocommerce-tools-pro'); ?></strong><br>
            <?php echo esc_html($info['ShipChargeDate']); ?>
        </p>

        <?php if ($info['CollectionAmount'] > 0) { ?>
        <p>
            <strong><?php esc_html_e('Collection amount', 'ry-woocommerce-tools-pro'); ?></strong><br>
            <?php echo esc_html($info['CollectionAmount']); ?></p>
        </p>
        <p>
            <strong><?php esc_html_e('Collection charge fee', 'ry-woocommerce-tools-pro'); ?></strong><br>
            <?php echo esc_html($info['CollectionChargeFee']); ?>
        </p>
        <p>
            <strong><?php esc_html_e('Collection allocate amount', 'ry-woocommerce-tools-pro'); ?></strong><br>
            <?php echo esc_html($info['CollectionAllocateAmount']); ?>
        </p>
        <p>
            <strong><?php esc_html_e('Collection allocate date', 'ry-woocommerce-tools-pro'); ?></strong><br>
            <?php echo esc_html($info['CollectionAllocateDate']); ?>
        </p>
        <?php } ?>

        <?php if (str_starts_with($info['LogisticsType'], 'CVS_')) { ?>
        <p>
            <strong><?php esc_html_e('Shipping no', 'ry-woocommerce-tools-pro'); ?></strong><br>
            <?php if (str_ends_with($info['LogisticsType'], 'C2C')) { ?>
            <?php echo esc_html($info['CVSPaymentNo'] . $info['CVSValidationNo']); ?>
            <?php } else { ?>
            <?php echo esc_html($info['ShipmentNo']); ?>
            <?php } ?>
        </p>
        <?php } ?>
        <?php if (str_starts_with($info['LogisticsType'], 'HOME_')) { ?>
        <p>
            <strong><?php esc_html_e('Shipping no', 'ry-woocommerce-tools-pro'); ?></strong><br>
            <?php echo esc_html($info['BookingNote']); ?>
        </p>
        <?php } ?>

        <?php if ($info['LogisticsType'] == 'HOME_POST') { ?>
        <p>
            <strong><?php esc_html_e('Declare weight', 'ry-woocommerce-tools-pro'); ?></strong><br>
            <?php echo esc_html($info['GoodsWeight']); ?>
        </p>
        <p>
            <strong><?php esc_html_e('Package weight', 'ry-woocommerce-tools-pro'); ?></strong><br>
            <?php echo esc_html($info['ActualWeight']); ?>
        </p>
        <?php } ?>
    </div>
</div>
