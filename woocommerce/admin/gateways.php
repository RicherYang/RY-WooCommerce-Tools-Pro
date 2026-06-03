<?php

defined('ABSPATH') or exit;

final class RY_WTP_WC_Admin_Gateways
{
    protected static ?self $_instance = null;

    public static function instance(): RY_WTP_WC_Admin_Gateways
    {
        if (null === self::$_instance) {
            self::$_instance = new self();
            self::$_instance->do_init();
        }

        return self::$_instance;
    }

    protected function do_init(): void
    {
        add_action('add_meta_boxes', [$this, 'add_meta_box'], 10, 2);

        add_filter('ry_admin_payment_info', [$this, 'show_payment_info'], 99, 2);
    }

    public function add_meta_box($post_type, $data_object)
    {
        include_once RY_WTP_PLUGIN_DIR . 'woocommerce/admin/meta-box.php';
        RY_WTP_WC_Admin_Meta_Box::add_meta_box($post_type, $data_object);
    }

    public function show_payment_info($html, $order)
    {
        $payment_method = $order->get_payment_method();
        if (!str_starts_with($payment_method, 'ry_ecpay_')) {
            if (!str_starts_with($payment_method, 'ry_newebpay_')) {
                if (!str_starts_with($payment_method, 'ry_payuni_')) {
                    return $html;
                }
            }
        }

        add_action('admin_footer', [$this, 'payment_info_template']);

        if (empty($html)) {
            $html = '<tr><td>';
        } else {
            $html = '<tr><td><table>' . $html . '</table></td><td>';
        }

        $html .= '<button id="ry-show-payment-info" type="button" class="button" data-orderid="' . esc_attr($order->get_id()) . '">' . esc_html__('Get payment info', 'ry-woocommerce-tools-pro') . '</button></td></tr>';

        return $html;
    }

    public function payment_info_template()
    {
        include RY_WTP_PLUGIN_DIR . 'woocommerce/admin/view/payment-info-template.php';
    }
}
