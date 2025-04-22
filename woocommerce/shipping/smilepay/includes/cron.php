<?php

final class RY_WTP_SmilePay_Shipping_Cron
{
    public static function add_action()
    {
        add_action('ry_wtp_get_smilepay_cvs_code', [RY_WT_WC_SmilePay_Shipping_Api::instance(), 'get_info_no'], 10, 2);
        add_action('ry_wtp_get_smilepay_home_code', [RY_WT_WC_SmilePay_Shipping_Api::instance(), 'get_home_info'], 10, 1);
    }
}
