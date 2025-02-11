<div class="wc-order-preview-addresses">
    <div class="wc-order-preview-address">
        <h2><?php esc_html_e('Order info', 'ry-woocommerce-tools-pro'); ?></h2>
        <strong><?php esc_html_e('Trade no', 'ry-woocommerce-tools-pro'); ?></strong>
        <?php echo esc_html($info['MerchantOrderNo']); ?>
        <strong><?php esc_html_e('NewebPay trade no', 'ry-woocommerce-tools-pro'); ?></strong>
        <?php echo esc_html($info['TradeNo']); ?>
        <strong><?php esc_html_e('NewebPay trade date', 'ry-woocommerce-tools-pro'); ?></strong>
        <?php echo esc_html($info['CreateTime']); ?>
        <strong><?php esc_html_e('NewebPay trade status', 'ry-woocommerce-tools-pro'); ?></strong>
        <?php if ($info['TradeStatus'] == '0') {
            echo esc_html_e('Unpaid', 'ry-woocommerce-tools-pro');
        } elseif ($info['TradeStatus'] == '1') {
            echo esc_html_e('Paid', 'ry-woocommerce-tools-pro');
        } elseif ($info['TradeStatus'] == '2') {
            echo esc_html_e('Payment failed', 'ry-woocommerce-tools-pro');
        } elseif ($info['TradeStatus'] == '3') {
            echo esc_html_e('Payment cancel', 'ry-woocommerce-tools-pro');
        } elseif ($info['TradeStatus'] == '46') {
            echo esc_html_e('Refund', 'ry-woocommerce-tools-pro');
        } ?>
        <strong><?php esc_html_e('Trade amount', 'ry-woocommerce-tools-pro'); ?></strong>
        <?php echo esc_html($info['Amt']); ?>
    </div>

    <div class="wc-order-preview-address">
        <h2><?php esc_html_e('Payment info', 'ry-woocommerce-tools-pro'); ?></h2>
        <strong><?php esc_html_e('Payment type', 'ry-woocommerce-tools-pro'); ?></strong>
        <?php echo esc_html(rywt_newebpay_info_to_name($info['PaymentType'])); ?>
        <strong><?php esc_html_e('Payment date', 'ry-woocommerce-tools-pro'); ?></strong>
        <?php echo esc_html($info['PayTime']); ?>

        <?php if ($info['PaymentType'] === 'CREDIT') { ?>
        <strong><?php esc_html_e('Card no', 'ry-woocommerce-tools-pro'); ?></strong>
        <?php echo esc_html($info['Card6No'] . ' **** ' . $info['Card4No']); ?>
        <strong><?php esc_html_e('Card type', 'ry-woocommerce-tools-pro'); ?></strong>
        <?php echo esc_html(rywt_newebpay_info_to_name('type-' . $info['PaymentMethod'])); ?>
        <strong><?php esc_html_e('Auth code', 'ry-woocommerce-tools-pro'); ?></strong>
        <?php echo esc_html($info['Auth']); ?>
        <strong><?php esc_html_e('eci', 'ry-woocommerce-tools-pro'); ?></strong>
        <?php echo esc_html($info['ECI']); ?>
        <?php if ($info['Inst']) { ?>
        <strong><?php esc_html_e('number of periods', 'ry-woocommerce-tools-pro'); ?></strong>
        <?php echo esc_html($info['Inst']); ?>
        <strong><?php esc_html_e('Period amount ( first / other )', 'ry-woocommerce-tools-pro'); ?></strong>
        <?php echo esc_html($info['InstFirst'] . ' / ' . $info['InstEach']); ?>
        <?php } ?>
        <?php } ?>
    </div>
</div>
