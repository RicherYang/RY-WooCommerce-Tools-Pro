<?php

use Automattic\WooCommerce\Blocks\Integrations\IntegrationInterface;

class RY_ECPay_Shipping_Cvs_Select_Block implements IntegrationInterface
{
    public function get_name(): string
    {
        return 'ry_ecpay_cvs_select_block';
    }

    public function initialize()
    {
        add_filter('__experimental_woocommerce_blocks_add_data_attributes_to_block', [$this, 'add_attributes_block']);

        $script_asset = include RY_WTP_PLUGIN_DIR . 'assets/blocks/shipping/ecpay/cvs-select-map/index.asset.php';
        wp_register_script('ry-ecpay-cvs-select-map', RY_WTP_PLUGIN_URL . 'assets/blocks/shipping/ecpay/cvs-select-map/index.js', $script_asset['dependencies'], $script_asset['version'], true);
        wp_set_script_translations('ry-ecpay-cvs-select-map', 'ry-woocommerce-tools-pro', RY_WTP_PLUGIN_LANGUAGES_DIR);

        $script_asset = include RY_WTP_PLUGIN_DIR . 'assets/blocks/shipping/ecpay/cvs-select-map/frontend.asset.php';
        wp_register_script('ry-ecpay-cvs-select-map-frontend', RY_WTP_PLUGIN_URL . 'assets/blocks/shipping/ecpay/cvs-select-map/frontend.js', $script_asset['dependencies'], $script_asset['version'], true);
        wp_set_script_translations('ry-ecpay-cvs-select-map-frontend', 'ry-woocommerce-tools-pro', RY_WTP_PLUGIN_LANGUAGES_DIR);
    }

    public function add_attributes_block($allowed_blocks)
    {
        $allowed_blocks[] = 'ry-woocommerce-tools/ry-ecpay-cvs-select-block';

        return $allowed_blocks;
    }

    public function get_script_handles()
    {
        return ['ry-ecpay-cvs-select-map-frontend'];
    }

    public function get_editor_script_handles()
    {
        return ['ry-ecpay-cvs-select-map'];
    }

    public function get_script_data()
    {
        list($MerchantID, $HashKey, $HashIV, $cvs_type) = RY_WT_WC_ECPay_Shipping::instance()->get_api_info();

        return [
            'postUrl' => RY_WT_WC_ECPay_Shipping_Api::instance()->get_map_post_url(),
            'postData' => [
                'MerchantID' => $MerchantID,
                'IsCollection' => 'Y',
                'ServerReplyURL' => esc_url(add_query_arg([
                    'ry-ecpay-map-redirect' => 'ry-ecpay-map-redirect',
                    'lang' => get_locale(),
                ], WC()->api_request_url('ry_ecpay_map_callback'))),
            ],
        ];
    }
}
