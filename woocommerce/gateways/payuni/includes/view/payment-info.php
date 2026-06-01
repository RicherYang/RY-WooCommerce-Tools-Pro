<?php defined('ABSPATH') or exit; ?>

<div style="display:flex; flex-wrap:wrap;">
    <div style="flex:1 0 0; width:100%">
        <h2><?php esc_html_e('Order info', 'ry-woocommerce-tools-pro'); ?></h2>
        <p>
            <strong><?php esc_html_e('Trade no', 'ry-woocommerce-tools-pro'); ?></strong><br>
            <?php echo esc_html($info['MerTradeNo']); ?>
        </p>
        <p>
            <strong><?php esc_html_e('Service provider trade no', 'ry-woocommerce-tools-pro'); ?></strong><br>
            <?php echo esc_html($info['TradeNo']); ?>
        </p>
        <p>
            <strong><?php esc_html_e('Service provider trade date', 'ry-woocommerce-tools-pro'); ?></strong><br>
            <?php echo esc_html($info['CreateDay']); ?>
        </p>
        <p>
            <strong><?php esc_html_e('Service provider trade status', 'ry-woocommerce-tools-pro'); ?></strong><br>
            <?php echo esc_html(rywtp_payuni_TradeStatus_name($info['TradeStatus'])); ?>
        </p>
        <p>
            <strong><?php esc_html_e('Trade amount', 'ry-woocommerce-tools-pro'); ?></strong><br>
            <?php echo esc_html($info['TradeAmt']); ?>
        </p>
        <p>
            <strong><?php esc_html_e('Transaction fee', 'ry-woocommerce-tools-pro'); ?></strong><br>
            <?php echo esc_html($info['TradeFee']); ?>
        </p>
    </div>

    <div style="flex:1 0 0; width:100%">
        <h2><?php esc_html_e('Payment info', 'ry-woocommerce-tools-pro'); ?></h2>
        <p>
            <strong><?php esc_html_e('Payment type', 'ry-woocommerce-tools-pro'); ?></strong><br>
            <?php echo esc_html(rywtp_payuni_PaymentType_name($info['PaymentType'])); ?>
        </p>
        <p>
            <strong><?php esc_html_e('Payment date', 'ry-woocommerce-tools-pro'); ?></strong><br>
            <?php echo esc_html($info['PaymentDay']); ?>
        </p>

        <?php if ($info['PaymentType'] === '1') { ?>
        <p>
            <strong><?php esc_html_e('Card no', 'ry-woocommerce-tools-pro'); ?></strong><br>
            <?php echo esc_html($info['Card6No'] . ' **** ' . $info['Card4No']); ?>
        </p>
        <p>
            <strong><?php esc_html_e('Auth type', 'ry-woocommerce-tools-pro'); ?></strong><br>
            <?php echo esc_html(rywtp_newebpay_AuthType_name($info['AuthType'])); ?>
        </p>
        <p>
            <strong><?php esc_html_e('Auth code', 'ry-woocommerce-tools-pro'); ?></strong><br>
            <?php echo esc_html($info['AuthCode']); ?>
        </p>
        <?php if ($info['CardInst']) { ?>
        <p>
            <strong><?php esc_html_e('number of periods', 'ry-woocommerce-tools-pro'); ?></strong><br>
            <?php echo esc_html($info['CardInst']); ?>
        </p>
        <?php } ?>
        <?php } ?>

        <?php if ($info['PaymentType'] === '2') { ?>
        <p>
            <strong><?php esc_html_e('Payment bank', 'ry-woocommerce-tools-pro'); ?></strong><br>
            <?php echo esc_html(rywt_bank_code_to_name($info['OffPayChannel'])); ?>
        </p>
        <?php } ?>
    </div>
</div>
