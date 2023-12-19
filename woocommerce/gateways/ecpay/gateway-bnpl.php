<?php

class RY_ECPay_Gateway_Bnpl extends RY_WT_WC_ECPay_Payment_Gateway
{
    public const Payment_Type = 'BNPL';

    protected $check_min_amount = 3000;
    protected $check_max_amount = 300000;

    public function __construct()
    {
        $this->id = 'ry_ecpay_bnpl';
        $this->has_fields = false;
        $this->order_button_text = __('Pay via BNPL', 'ry-woocommerce-tools-pro');
        $this->method_title = __('ECPay BNPL', 'ry-woocommerce-tools-pro');
        $this->method_description = '';
        $this->process_payment_note = __('Pay via ECPay BNPL', 'ry-woocommerce-tools-pro');

        $this->form_fields = include RY_WTP_PLUGIN_DIR . 'woocommerce/gateways/ecpay/includes/settings/bnpl.php';
        $this->init_settings();

        $this->title = $this->get_option('title') ?: $this->method_title;
        $this->description = $this->get_option('description');
        $this->min_amount = (int) $this->get_option('min_amount', 0);
        $this->max_amount = (int) $this->get_option('max_amount', 0);

        add_action('woocommerce_admin_order_data_after_billing_address', [$this, 'admin_payment_info']);

        parent::__construct();
    }

    public function admin_payment_info($order)
    {
        if ($order->get_payment_method() != 'ry_ecpay_bnpl') {
            return;
        }
        ?>
<h3 style="clear:both"><?php esc_html_e('Payment details', 'ry-woocommerce-tools') ?></h3>
<table>
    <tr>
        <td><?php esc_html_e('BNPL Trade No', 'ry-woocommerce-tools-pro') ?>
        </td>
        <td><?php echo esc_html($order->get_meta('_ecpay_bnpl_TradeNo')); ?></td>
    </tr>
    <tr>
        <td><?php esc_html_e('BNPL Installment', 'ry-woocommerce-tools-pro') ?>
        </td>
        <td><?php echo esc_html($order->get_meta('_ecpay_bnpl_Installment')); ?>
        </td>
    </tr>
</table>
<?php
    }
}
?>
