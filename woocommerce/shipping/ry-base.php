<?php
final class RY_WTP_Shipping
{
    public static function init()
    {
        add_filter('wc_order_statuses', [__CLASS__, 'add_order_statuses']);
        add_filter('woocommerce_reports_order_statuses', [__CLASS__, 'add_reports_order_statuses']);
        add_filter('woocommerce_order_is_paid_statuses', [__CLASS__, 'add_order_is_paid_statuses']);
        self::register_order_statuses();

        if (is_admin()) {
            if ('yes' == RY_WT::get_option('enabled_ecpay_shipping', 'no')) {
                add_action('woocommerce_product_options_shipping', [__CLASS__, 'shipping_options']);
                add_action('woocommerce_admin_process_product_object', [__CLASS__, 'save_shipping_options']);
            }
        } else {
            wp_register_script('ry-pro-shipping', RY_WTP_PLUGIN_URL . 'style/js/ry_shipping.js', ['jquery'], RY_WTP_VERSION, true);
        }
    }

    public static function add_order_statuses($order_statuses)
    {
        $order_statuses['wc-ry-transporting'] = _x('Transporting', 'Order status', 'ry-woocommerce-tools-pro');

        return $order_statuses;
    }

    public static function add_reports_order_statuses($order_statuses)
    {
        $order_statuses[] = 'ry-transporting';

        return $order_statuses;
    }

    public static function add_order_is_paid_statuses($statuses)
    {
        $statuses[] = 'ry-transporting';

        return $statuses;
    }

    public static function register_order_statuses()
    {
        register_post_status('wc-ry-transporting', [
            'label' => _x('Transporting', 'Order status', 'ry-woocommerce-tools-pro'),
            'public' => false,
            'exclude_from_search' => false,
            'show_in_admin_all_list' => true,
            'show_in_admin_status_list' => true,
            /* translators: %s: number of orders */
            'label_count' => _n_noop('Transporting <span class="count">(%s)</span>', 'Transporting <span class="count">(%s)</span>', 'ry-woocommerce-tools-pro'),
        ]);
    }

    public static function shipping_options()
    {
        global $product_object;

        include RY_WTP_PLUGIN_DIR . 'woocommerce/admin/view/product-shipping-option.php';
    }

    public static function save_shipping_options($product)
    {
        $product->update_meta_data('_ry_shipping_temp', isset($_POST['_ry_shipping_temp']) ? wc_clean(wp_unslash($_POST['_ry_shipping_temp'])) : '1');
    }
}

RY_WTP_Shipping::init();
