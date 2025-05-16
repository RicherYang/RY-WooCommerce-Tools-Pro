<?php

final class RY_WTP_ECPay_Shipping_Admin_Ajax
{
    protected static $_instance = null;

    public static function instance(): RY_WTP_ECPay_Shipping_Admin_Ajax
    {
        if (null === self::$_instance) {
            self::$_instance = new self();
            self::$_instance->do_init();
        }
        return self::$_instance;
    }

    protected function do_init(): void
    {
        add_action('wp_ajax_RY_shipping_info', [$this, 'get_shipping_info']);
    }

    public function get_shipping_info()
    {
        check_ajax_referer('get-shipping-info');
        $order_ID = intval($_POST['orderid'] ?? '');

        $order = wc_get_order($order_ID);
        if (!empty($order)) {
            $info_ID = sanitize_locale_name($_POST['id'] ?? '');
            $shipping_list = $order->get_meta('_ecpay_shipping_info', true);
            if (is_array($shipping_list) && isset($shipping_list[$info_ID])) {
                $data = [
                    'order_number' => $order->get_order_number(),
                    'ID' => $info_ID,
                ];

                $info = RY_WT_WC_ECPay_Shipping_Api::instance()->get_info($info_ID);
                if (empty($info)) {
                    $data['info_html'] = esc_html__('Can not get info from ECPay.', 'ry-woocommerce-tools-pro');
                } else {
                    if (str_starts_with($info['LogisticsType'], 'CVS_')) {
                        if (!str_ends_with($info['LogisticsType'], 'C2C')) {
                            if (empty($shipping_list[$info_ID]['PaymentNo'])) {
                                $shipping_list[$info_ID]['PaymentNo'] = $info['ShipmentNo'];
                                $order->update_meta_data('_ecpay_shipping_info', $shipping_list);
                                $order->save();
                            }
                        }
                    }

                    ob_start();
                    include RY_WTP_PLUGIN_DIR . 'woocommerce/shipping/ecpay/includes/view/shipping-info.php';
                    $data['info_html'] = ob_get_clean();
                }

                wp_send_json_success($data);
            }
        }
    }
}
