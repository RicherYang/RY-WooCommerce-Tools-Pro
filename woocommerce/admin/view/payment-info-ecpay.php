<div class="wc-order-preview-addresses">
    <div class="wc-order-preview-address">
        <h2><?php esc_html_e('Order info', 'ry-woocommerce-tools-pro'); ?></h2>
        <strong><?php esc_html_e('Trade no', 'ry-woocommerce-tools-pro'); ?></strong>
        <?php echo esc_html($info['MerchantTradeNo']); ?>
        <strong><?php esc_html_e('ECPay trade no', 'ry-woocommerce-tools-pro'); ?></strong>
        <?php echo esc_html($info['TradeNo']); ?>
        <strong><?php esc_html_e('ECPay trade date', 'ry-woocommerce-tools-pro'); ?></strong>
        <?php echo esc_html($info['TradeDate']); ?>
        <strong><?php esc_html_e('ECPay trade status', 'ry-woocommerce-tools-pro'); ?></strong>
        <?php if ($info['TradeStatus'] == '0') {
            echo esc_html_e('Unpaid', 'ry-woocommerce-tools-pro');
        } elseif ($info['TradeStatus'] == '1') {
            echo esc_html_e('Paid', 'ry-woocommerce-tools-pro');
        } elseif ($info['TradeStatus'] == '10200095') {
            echo esc_html_e('Payment failed', 'ry-woocommerce-tools-pro');
        } else {
            echo esc_html($info['TradeStatus']);
        } ?>
        <strong><?php esc_html_e('Trade amount', 'ry-woocommerce-tools-pro'); ?></strong>
        <?php echo esc_html($info['TradeAmt']); ?>
    </div>

    <div class="wc-order-preview-address">
        <h2><?php esc_html_e('Payment info', 'ry-woocommerce-tools-pro'); ?></h2>
        <strong><?php esc_html_e('Payment type', 'ry-woocommerce-tools-pro'); ?></strong>
        <?php echo esc_html(rywt_ecpay_info_to_name($info['PaymentType'])); ?>
        <strong><?php esc_html_e('Payment date', 'ry-woocommerce-tools-pro'); ?></strong>
        <?php echo esc_html($info['PaymentDate']); ?>
        <strong><?php esc_html_e('Payment amount', 'ry-woocommerce-tools-pro'); ?></strong>
        <?php echo esc_html($info['amount']); ?>

        <?php if ($info['PaymentType'] === 'Credit_CreditCard') { ?>
        <strong><?php esc_html_e('Card no', 'ry-woocommerce-tools-pro'); ?></strong>
        <?php echo esc_html($info['card6no'] . ' **** ' . $info['card4no']); ?>
        <strong><?php esc_html_e('Auth code', 'ry-woocommerce-tools-pro'); ?></strong>
        <?php echo esc_html($info['auth_code']); ?>
        <strong><?php esc_html_e('eci', 'ry-woocommerce-tools-pro'); ?></strong>
        <?php echo esc_html($info['eci']); ?>
        <?php } ?>
        <?php if ($info['PaymentType'] === 'TWQR_OPAY') { ?>
        <strong><?php esc_html_e('TWQR trade no', 'ry-woocommerce-tools-pro'); ?></strong>
        <?php echo esc_html($info['TWQRTradeNo']); ?>
        <?php } ?>
        <?php if (str_starts_with($info['PaymentType'], 'WebATM_')) { ?>
        <strong><?php esc_html_e('Payment bank', 'ry-woocommerce-tools-pro'); ?></strong>
        <?php echo esc_html(rywt_bank_code_to_name($info['WebATMAccBank'])); ?>
        <strong><?php esc_html_e('Payment bank no last 5', 'ry-woocommerce-tools-pro'); ?></strong>
        <?php echo esc_html($info['WebATMAccNo']); ?>
        <?php } ?>
        <?php if (str_starts_with($info['PaymentType'], 'ATM_')) { ?>
        <strong><?php esc_html_e('Payment bank', 'ry-woocommerce-tools-pro'); ?></strong>
        <?php echo esc_html(rywt_bank_code_to_name($info['ATMAccBank'])); ?>
        <strong><?php esc_html_e('Payment bank no last 5', 'ry-woocommerce-tools-pro'); ?></strong>
        <?php echo esc_html($info['ATMAccNo']); ?>
        <?php } ?>
        <?php if (str_starts_with($info['PaymentType'], 'CVS_')) { ?>
        <strong><?php esc_html_e('Payment cvs', 'ry-woocommerce-tools-pro'); ?></strong>
        <?php echo esc_html(rywt_ecpay_info_to_name($info['PayFrom'])); ?>
        <?php } ?>
    </div>
</div>
