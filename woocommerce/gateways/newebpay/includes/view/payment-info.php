<div style="display:flex; flex-wrap:wrap;">
    <div style="flex:1 0 0; width:100%">
        <h2><?php esc_html_e('Order info', 'ry-woocommerce-tools-pro'); ?></h2>
        <p>
            <strong><?php esc_html_e('Trade no', 'ry-woocommerce-tools-pro'); ?></strong><br>
            <?php echo esc_html($info['MerchantOrderNo']); ?>
        </p>
        <p>
            <strong><?php esc_html_e('NewebPay trade no', 'ry-woocommerce-tools-pro'); ?></strong><br>
            <?php echo esc_html($info['TradeNo']); ?>
        </p>
        <p>
            <strong><?php esc_html_e('NewebPay trade date', 'ry-woocommerce-tools-pro'); ?></strong><br>
            <?php echo esc_html($info['CreateTime']); ?>
        </p>
        <p>
            <strong><?php esc_html_e('NewebPay trade status', 'ry-woocommerce-tools-pro'); ?></strong><br>
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
        </p>
        <p>
            <strong><?php esc_html_e('Trade amount', 'ry-woocommerce-tools-pro'); ?></strong><br>
            <?php echo esc_html($info['Amt']); ?>
        </p>
    </div>

    <div style="flex:1 0 0; width:100%">
        <h2><?php esc_html_e('Payment info', 'ry-woocommerce-tools-pro'); ?></h2>
        <p>
            <strong><?php esc_html_e('Payment type', 'ry-woocommerce-tools-pro'); ?></strong><br>
            <?php echo esc_html(rywtp_newebpay_type_to_name($info['PaymentType'])); ?>
        </p>
        <p>
            <strong><?php esc_html_e('Payment date', 'ry-woocommerce-tools-pro'); ?></strong><br>
            <?php echo esc_html($info['PayTime']); ?>
        </p>

        <?php if ($info['PaymentType'] === 'CREDIT') { ?>
        <p>
            <strong><?php esc_html_e('Card no', 'ry-woocommerce-tools-pro'); ?></strong><br>
            <?php echo esc_html($info['Card6No'] . ' **** ' . $info['Card4No']); ?>
        </p>
        <p>
            <strong><?php esc_html_e('Card type', 'ry-woocommerce-tools-pro'); ?></strong><br>
            <?php echo esc_html(rywtp_newebpay_method_to_name($info['PaymentMethod'])); ?>
        </p>
        <p>
            <strong><?php esc_html_e('Auth code', 'ry-woocommerce-tools-pro'); ?></strong><br>
            <?php echo esc_html($info['Auth']); ?>
        </p>
        <p>
            <strong><?php esc_html_e('eci', 'ry-woocommerce-tools-pro'); ?></strong><br>
            <?php echo esc_html($info['ECI']); ?>
        </p>
        <?php if ($info['Inst']) { ?>
        <p>
            <strong><?php esc_html_e('number of periods', 'ry-woocommerce-tools-pro'); ?></strong><br>
            <?php echo esc_html($info['Inst']); ?>
        </p>
        <p>
            <strong><?php esc_html_e('Period amount ( first / other )', 'ry-woocommerce-tools-pro'); ?></strong><br>
            <?php echo esc_html($info['InstFirst'] . ' / ' . $info['InstEach']); ?>
        </p>
        <?php } ?>
        <?php } ?>
    </div>
</div>
