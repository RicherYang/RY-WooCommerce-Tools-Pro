<div style="display:flex; flex-wrap:wrap;">
    <div style="flex:1 0 0; width:100%">
        <h2><?php esc_html_e('Order info', 'ry-woocommerce-tools-pro'); ?></h2>
        <p>
            <strong><?php esc_html_e('Trade no', 'ry-woocommerce-tools-pro'); ?></strong><br>
            <?php echo esc_html($info['MerchantTradeNo']); ?>
        </p>
        <p>
            <strong><?php esc_html_e('ECPay trade no', 'ry-woocommerce-tools-pro'); ?></strong><br>
            <?php echo esc_html($info['TradeNo']); ?>
        </p>
        <p>
            <strong><?php esc_html_e('ECPay trade date', 'ry-woocommerce-tools-pro'); ?></strong><br>
            <?php echo esc_html($info['TradeDate']); ?>
        </p>
        <p>
            <strong><?php esc_html_e('ECPay trade status', 'ry-woocommerce-tools-pro'); ?></strong><br>
            <?php if ($info['TradeStatus'] == '0') {
                echo esc_html_e('Unpaid', 'ry-woocommerce-tools-pro');
            } elseif ($info['TradeStatus'] == '1') {
                echo esc_html_e('Paid', 'ry-woocommerce-tools-pro');
            } elseif ($info['TradeStatus'] == '10200095') {
                echo esc_html_e('Payment failed', 'ry-woocommerce-tools-pro');
            } ?>
        </p>
        <p>
            <strong><?php esc_html_e('Trade amount', 'ry-woocommerce-tools-pro'); ?></strong><br>
            <?php echo esc_html($info['TradeAmt']); ?>
        </p>
        <p>
            <strong><?php esc_html_e('Handling fee', 'ry-woocommerce-tools-pro'); ?></strong><br>
            <?php echo esc_html($info['HandlingCharge']); ?>
        </p>
        <p>
            <strong><?php esc_html_e('Transaction fee', 'ry-woocommerce-tools-pro'); ?></strong><br>
            <?php echo esc_html($info['PaymentTypeChargeFee']); ?>
        </p>
    </div>

    <div style="flex:1 0 0; width:100%">
        <h2><?php esc_html_e('Payment info', 'ry-woocommerce-tools-pro'); ?></h2>
        <p>
            <strong><?php esc_html_e('Payment type', 'ry-woocommerce-tools-pro'); ?></strong><br>
            <?php echo esc_html(rywtp_ecpay_info_to_name($info['PaymentType'])); ?>
        </p>
        <p>
            <strong><?php esc_html_e('Payment date', 'ry-woocommerce-tools-pro'); ?></strong><br>
            <?php echo esc_html($info['PaymentDate']); ?>
        </p>
        <p>
            <strong><?php esc_html_e('Payment amount', 'ry-woocommerce-tools-pro'); ?></strong><br>
            <?php echo esc_html($info['amount']); ?>
        </p>

        <?php if ($info['PaymentType'] === 'Credit_CreditCard') { ?>
        <p>
            <strong><?php esc_html_e('Card no', 'ry-woocommerce-tools-pro'); ?></strong><br>
            <?php echo esc_html($info['card6no'] . ' **** ' . $info['card4no']); ?>
        </p>
        <p>
            <strong><?php esc_html_e('Auth code', 'ry-woocommerce-tools-pro'); ?></strong><br>
            <?php echo esc_html($info['auth_code']); ?>
        </p>
        <p>
            <strong><?php esc_html_e('eci', 'ry-woocommerce-tools-pro'); ?></strong><br>
            <?php echo esc_html($info['eci']); ?>
        </p>
        <?php if ($info['stage']) { ?>
        <p>
            <strong><?php esc_html_e('number of periods', 'ry-woocommerce-tools-pro'); ?></strong><br>
            <?php echo esc_html($info['stage']); ?>
        </p>
        <p>
            <strong><?php esc_html_e('Period amount ( first / other )', 'ry-woocommerce-tools-pro'); ?></strong><br>
            <?php echo esc_html($info['stast'] . ' / ' . $info['staed']); ?>
        </p>
        <?php } ?>
        <?php } ?>
        <?php if ($info['PaymentType'] === 'TWQR_OPAY') { ?>
        <p>
            <strong><?php esc_html_e('TWQR trade no', 'ry-woocommerce-tools-pro'); ?></strong><br>
            <?php echo esc_html($info['TWQRTradeNo']); ?>
        </p>
        <?php } ?>
        <?php if (str_starts_with($info['PaymentType'], 'WebATM_')) { ?>
        <p>
            <strong><?php esc_html_e('Payment bank', 'ry-woocommerce-tools-pro'); ?></strong><br>
            <?php echo esc_html(rywt_bank_code_to_name($info['WebATMAccBank'])); ?>
        </p>
        <p>
            <strong><?php esc_html_e('Payment bank no last 5', 'ry-woocommerce-tools-pro'); ?></strong><br>
            <?php echo esc_html($info['WebATMAccNo']); ?>
        </p>
        <?php } ?>
        <?php if (str_starts_with($info['PaymentType'], 'ATM_')) { ?>
        <p>
            <strong><?php esc_html_e('Payment bank', 'ry-woocommerce-tools-pro'); ?></strong><br>
            <?php echo esc_html(rywt_bank_code_to_name($info['ATMAccBank'])); ?>
        </p>
        <p>
            <strong><?php esc_html_e('Payment bank no last 5', 'ry-woocommerce-tools-pro'); ?></strong><br>
            <?php echo esc_html($info['ATMAccNo']); ?>
        </p>
        <?php } ?>
        <?php if (str_starts_with($info['PaymentType'], 'CVS_')) { ?>
        <p>
            <strong><?php esc_html_e('Payment cvs', 'ry-woocommerce-tools-pro'); ?></strong><br>
            <?php echo esc_html(rywtp_ecpay_info_to_name($info['PayFrom'])); ?>
        </p>
        <?php } ?>
    </div>
</div>
