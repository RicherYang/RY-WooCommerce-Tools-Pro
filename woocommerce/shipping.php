<?php

final class RY_WTP_WC_Shipping
{
    protected static $_instance = null;

    public static function instance(): RY_WTP_WC_Shipping
    {
        if (null === self::$_instance) {
            self::$_instance = new self();
            self::$_instance->do_init();
        }

        return self::$_instance;
    }

    protected function do_init(): void
    {
        if (is_admin()) {
            include_once RY_WTP_PLUGIN_DIR . 'woocommerce/admin/shipping.php';
            RY_WTP_WC_Admin_Shipping::instance();
        }

        foreach (WC()->shipping()->get_shipping_methods() as $id => $shipping_method) {
            add_filter('woocommerce_shipping_' . $id . '_is_available', [$this, 'product_skip_shipping'], 11, 3);
        }
    }

    public function product_skip_shipping($available, $package, $shipping_method): bool
    {
        if ($available) {
            foreach ($package['contents'] as $values) {
                $skip_shipping = $values['data']->get_meta('_ry_shipping_skip_shipping', true);
                if (empty($skip_shipping) && 'variation' === $values['data']->get_type()) {
                    $parent_product = wc_get_product($values['data']->get_parent_id());
                    $skip_shipping = $parent_product->get_meta('_ry_shipping_skip_shipping', true);
                }

                if (!empty($skip_shipping)) {
                    $skip_shipping = (array) $skip_shipping;
                    if (in_array($shipping_method->id, $skip_shipping, true)) {
                        return false;
                    }
                    if (in_array($shipping_method->id . ':' . $shipping_method->instance_id, $skip_shipping, true)) {
                        return false;
                    }
                }
            }
        }

        return $available;
    }
}
