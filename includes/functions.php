<?php

function rywtp_link_error_to_msg($error)
{
    $message_list = [
        'Unknown key' => __('Unknown key', 'ry-woocommerce-tools-pro'),
        'Locked key' => __('Locked key', 'ry-woocommerce-tools-pro'),
        'Unknown target url' => __('Unknown target url', 'ry-woocommerce-tools-pro'),
        'Used key' => __('Used key', 'ry-woocommerce-tools-pro'),
        'Is tried' => __('Is tried', 'ry-woocommerce-tools-pro'),
    ];

    return $message_list[$error] ?? $error;
}

function rywtp_ecpay_info_to_name($info)
{
    static $info_name = [];
    if (empty($info_name)) {
        $info_name = [
            'WebATM_TAISHIN' => _x('WebATM_TAISHIN', 'ecpay info', 'ry-woocommerce-tools-pro'), // 台新銀行 WebATM
            'WebATM_ESUN' => _x('WebATM_ESUN', 'ecpay info', 'ry-woocommerce-tools-pro'), // 玉山銀行 WebATM
            'WebATM_BOT' => _x('WebATM_BOT', 'ecpay info', 'ry-woocommerce-tools-pro'), // 台灣銀行 WebATM
            'WebATM_FUBON' => _x('WebATM_FUBON', 'ecpay info', 'ry-woocommerce-tools-pro'), // 台北富邦 WebATM
            'WebATM_CHINATRUST' => _x('WebATM_CHINATRUST', 'ecpay info', 'ry-woocommerce-tools-pro'), // 中國信託 WebATM
            'WebATM_FIRST' => _x('WebATM_FIRST', 'ecpay info', 'ry-woocommerce-tools-pro'), // 第一銀行 WebATM
            'WebATM_CATHAY' => _x('WebATM_CATHAY', 'ecpay info', 'ry-woocommerce-tools-pro'), // 國泰世華 WebATM
            'WebATM_MEGA' => _x('WebATM_MEGA', 'ecpay info', 'ry-woocommerce-tools-pro'), // 兆豐銀行 WebATM
            'WebATM_LAND' => _x('WebATM_LAND', 'ecpay info', 'ry-woocommerce-tools-pro'), // 土地銀行 WebATM
            'WebATM_TACHONG' => _x('WebATM_TACHONG', 'ecpay info', 'ry-woocommerce-tools-pro'), // 大眾銀行 WebATM
            'WebATM_SINOPAC' => _x('WebATM_SINOPAC', 'ecpay info', 'ry-woocommerce-tools-pro'), // 永豐銀行 WebATM
            'ATM_TAISHIN' => _x('ATM_TAISHIN', 'ecpay info', 'ry-woocommerce-tools-pro'), // 台新銀行 ATM
            'ATM_ESUN' => _x('ATM_ESUN', 'ecpay info', 'ry-woocommerce-tools-pro'), // 玉山銀行 ATM
            'ATM_BOT' => _x('ATM_BOT', 'ecpay info', 'ry-woocommerce-tools-pro'), // 台灣銀行 ATM
            'ATM_FUBON' => _x('ATM_FUBON', 'ecpay info', 'ry-woocommerce-tools-pro'), // 台北富邦 ATM
            'ATM_CHINATRUST' => _x('ATM_CHINATRUST', 'ecpay info', 'ry-woocommerce-tools-pro'), // 中國信託 ATM
            'ATM_FIRST' => _x('ATM_FIRST', 'ecpay info', 'ry-woocommerce-tools-pro'), // 第一銀行 ATM
            'ATM_LAND' => _x('ATM_LAND', 'ecpay info', 'ry-woocommerce-tools-pro'), // 土地銀行 ATM
            'ATM_CATHAY' => _x('ATM_CATHAY', 'ecpay info', 'ry-woocommerce-tools-pro'), // 國泰世華銀行 ATM
            'ATM_TACHONG' => _x('ATM_TACHONG', 'ecpay info', 'ry-woocommerce-tools-pro'), // 大眾銀行 ATM
            'ATM_PANHSIN' => _x('ATM_PANHSIN', 'ecpay info', 'ry-woocommerce-tools-pro'), // 板信銀行 ATM
            'CVS_CVS' => _x('CVS_CVS', 'ecpay info', 'ry-woocommerce-tools-pro'), // 超商代碼繳款
            'CVS_OK' => _x('CVS_OK', 'ecpay info', 'ry-woocommerce-tools-pro'), // OK 超商代碼繳款
            'CVS_FAMILY' => _x('CVS_FAMILY', 'ecpay info', 'ry-woocommerce-tools-pro'), // 全家超商代碼繳款
            'CVS_HILIFE' => _x('CVS_HILIFE', 'ecpay info', 'ry-woocommerce-tools-pro'), // 萊爾富超商代碼繳款
            'CVS_IBON' => _x('CVS_IBON', 'ecpay info', 'ry-woocommerce-tools-pro'), // 7-11 ibon 代碼繳款
            'BARCODE_BARCODE' => _x('BARCODE_BARCODE', 'ecpay info', 'ry-woocommerce-tools-pro'), // 超商條碼繳款
            'Credit_CreditCard' => _x('Credit_CreditCard', 'ecpay info', 'ry-woocommerce-tools-pro'), // 信用卡
            'Flexible_Installment' => _x('Flexible_Installment', 'ecpay info', 'ry-woocommerce-tools-pro'), // 圓夢彈性分期
            'TWQR_OPAY' => _x('TWQR_OPAY', 'ecpay info', 'ry-woocommerce-tools-pro'), // 歐付寶TWQR 行動支付
            'BNPL_URICH' => _x('BNPL_URICH', 'ecpay info', 'ry-woocommerce-tools-pro'), // 裕富數位無卡分期

            'family' => _x('family', 'ecpay info', 'ry-woocommerce-tools-pro'), // 全家
            'hilife' => _x('hilife', 'ecpay info', 'ry-woocommerce-tools-pro'), // 萊爾富
            'okmart' => _x('okmart', 'ecpay info', 'ry-woocommerce-tools-pro'), // OK超商
            'ibon' => _x('ibon', 'ecpay info', 'ry-woocommerce-tools-pro'), // 7-11
        ];
    }

    return $info_name[$info] ?? $info;
}

function rywtp_ecpay_shipping_to_name($shipping)
{
    static $shipping_name = [];
    if (empty($shipping_name)) {
        $shipping_name = [
            'shipping-CVS_FAMI' => _x('CVS_FAMI', 'ecpay shipping', 'ry-woocommerce-tools-pro'), // 全家物流(B2C)',
            'shipping-CVS_UNIMART' => _x('CVS_UNIMART', 'ecpay shipping', 'ry-woocommerce-tools-pro'), // 7-ELEVEN超商物流(B2C)',
            'shipping-CVS_UNIMARTFREEZE' => _x('CVS_UNIMARTFREEZE', 'ecpay shipping', 'ry-woocommerce-tools-pro'), // 7-ELEVEN冷凍店取(B2C)',
            'shipping-CVS_FAMIC2C' => _x('CVS_FAMIC2C', 'ecpay shipping', 'ry-woocommerce-tools-pro'), // 全家物流(C2C)',
            'shipping-CVS_UNIMARTC2C' => _x('CVS_UNIMARTC2C', 'ecpay shipping', 'ry-woocommerce-tools-pro'), // 7-ELEVEN超商物流(C2C)',
            'shipping-CVS_HILIFE' => _x('CVS_HILIFE', 'ecpay shipping', 'ry-woocommerce-tools-pro'), // 萊爾富物流(B2C)',
            'shipping-CVS_HILIFEC2C' => _x('CVS_HILIFEC2C', 'ecpay shipping', 'ry-woocommerce-tools-pro'), // 萊爾富物流(C2C)',
            'shipping-CVS_OKMARTC2C' => _x('CVS_OKMARTC2C', 'ecpay shipping', 'ry-woocommerce-tools-pro'), // OK超商(C2C)',
            'shipping-HOME_TCAT' => _x('HOME_TCAT', 'ecpay shipping', 'ry-woocommerce-tools-pro'), // 黑貓物流',
            'shipping-HOME_POST' => _x('HOME_POST', 'ecpay shipping', 'ry-woocommerce-tools-pro'), // 中華郵政',
        ];
    }

    return $shipping_name[$shipping] ?? $shipping;
}

function rywtp_ecpay_status_to_name($status)
{
    static $status_name = [];
    if (empty($status_name)) {
        $status_name = [
            'status-300' => _x('300', 'ecpay status', 'ry-woocommerce-tools-pro'), // 訂單處理中(綠界已收到訂單資料)
            'status-310' => _x('310', 'ecpay status', 'ry-woocommerce-tools-pro'), // 訂單上傳物流中
            'status-311' => _x('311', 'ecpay status', 'ry-woocommerce-tools-pro'), // 訂單傳送物流成功
            'status-320' => _x('320', 'ecpay status', 'ry-woocommerce-tools-pro'), // 郵局已確認資料，可列印
            'status-325' => _x('325', 'ecpay status', 'ry-woocommerce-tools-pro'), // 退貨訂單處理中(綠界已收到訂單資料)
            'status-2000' => _x('2000', 'ecpay status', 'ry-woocommerce-tools-pro'), // 已申請門市變更
            'status-2001' => _x('2001', 'ecpay status', 'ry-woocommerce-tools-pro'), // 訂單傳送超商成功
            'status-2002' => _x('2002', 'ecpay status', 'ry-woocommerce-tools-pro'), // 出貨單號不合規則
            'status-2003' => _x('2003', 'ecpay status', 'ry-woocommerce-tools-pro'), // XML檔內出貨單號重複
            'status-2004' => _x('2004', 'ecpay status', 'ry-woocommerce-tools-pro'), // 出貨單號重複上傳使用(驗收時發現)
            'status-2005' => _x('2005', 'ecpay status', 'ry-woocommerce-tools-pro'), // 日期格式不符
            'status-2006' => _x('2006', 'ecpay status', 'ry-woocommerce-tools-pro'), // 訂單金額或代收金額錯誤
            'status-2007' => _x('2007', 'ecpay status', 'ry-woocommerce-tools-pro'), // 商品類型為空
            'status-2008' => _x('2008', 'ecpay status', 'ry-woocommerce-tools-pro'), // 訂單為空
            'status-2009' => _x('2009', 'ecpay status', 'ry-woocommerce-tools-pro'), // 門市店號為空
            'status-2010' => _x('2010', 'ecpay status', 'ry-woocommerce-tools-pro'), // 出貨日期為空
            'status-2011' => _x('2011', 'ecpay status', 'ry-woocommerce-tools-pro'), // 出貨金額為空
            'status-2012' => _x('2012', 'ecpay status', 'ry-woocommerce-tools-pro'), // 出貨編號不存在
            'status-2013' => _x('2013', 'ecpay status', 'ry-woocommerce-tools-pro'), // 母廠商不存在
            'status-2014' => _x('2014', 'ecpay status', 'ry-woocommerce-tools-pro'), // 子廠商不存在
            'status-2015' => _x('2015', 'ecpay status', 'ry-woocommerce-tools-pro'), // 出貨編號已存在(單筆)
            'status-2016' => _x('2016', 'ecpay status', 'ry-woocommerce-tools-pro'), // 門市已關轉店，將進行退貨處理
            'status-2017' => _x('2017', 'ecpay status', 'ry-woocommerce-tools-pro'), // 出貨日期不符合規定
            'status-2018' => _x('2018', 'ecpay status', 'ry-woocommerce-tools-pro'), // 服務類型不符規定(如只開取貨付款服務，卻使用純取貨服務)
            'status-2019' => _x('2019', 'ecpay status', 'ry-woocommerce-tools-pro'), // 商品類型不符規定
            'status-2020' => _x('2020', 'ecpay status', 'ry-woocommerce-tools-pro'), // 廠商尚未申請店配服務
            'status-2021' => _x('2021', 'ecpay status', 'ry-woocommerce-tools-pro'), // 同一批次出貨編號重複(批次)
            'status-2022' => _x('2022', 'ecpay status', 'ry-woocommerce-tools-pro'), // 出貨金額不符規定
            'status-2023' => _x('2023', 'ecpay status', 'ry-woocommerce-tools-pro'), // 取貨人姓名為空
            'status-2024' => _x('2024', 'ecpay status', 'ry-woocommerce-tools-pro'), // 訂單傳送超商成功
            'status-2025' => _x('2025', 'ecpay status', 'ry-woocommerce-tools-pro'), // 門市轉店號(舊門市店號已更新)
            'status-2026' => _x('2026', 'ecpay status', 'ry-woocommerce-tools-pro'), // 無此門市，將進行退貨處理
            'status-2027' => _x('2027', 'ecpay status', 'ry-woocommerce-tools-pro'), // 門市指定時間不配送，後續配送中
            'status-2028' => _x('2028', 'ecpay status', 'ry-woocommerce-tools-pro'), // 門市關轉店，3日內未更新SUP(新店號)便至退貨流程
            'status-2029' => _x('2029', 'ecpay status', 'ry-woocommerce-tools-pro'), // 門市尚未開店
            'status-2030' => _x('2030', 'ecpay status', 'ry-woocommerce-tools-pro'), // 物流中心驗收成功
            'status-2031' => _x('2031', 'ecpay status', 'ry-woocommerce-tools-pro'), // 等待賣家出貨
            'status-2032' => _x('2032', 'ecpay status', 'ry-woocommerce-tools-pro'), // 包裝異常，請洽客服
            'status-2033' => _x('2033', 'ecpay status', 'ry-woocommerce-tools-pro'), // 包裹超材，退回賣家
            'status-2034' => _x('2034', 'ecpay status', 'ry-woocommerce-tools-pro'), // 違禁品(退貨及罰款處理)
            'status-2035' => _x('2035', 'ecpay status', 'ry-woocommerce-tools-pro'), // 訂單資料重複上傳
            'status-2036' => _x('2036', 'ecpay status', 'ry-woocommerce-tools-pro'), // 訂單超過驗收期限(賣家未出貨)
            'status-2037' => _x('2037', 'ecpay status', 'ry-woocommerce-tools-pro'), // 取件門市關轉，請重選門市
            'status-2038' => _x('2038', 'ecpay status', 'ry-woocommerce-tools-pro'), // 標籤錯誤，廠退處理
            'status-2039' => _x('2039', 'ecpay status', 'ry-woocommerce-tools-pro'), // 標籤錯誤，廠退處理
            'status-2040' => _x('2040', 'ecpay status', 'ry-woocommerce-tools-pro'), // 標籤錯誤，廠退處理
            'status-2041' => _x('2041', 'ecpay status', 'ry-woocommerce-tools-pro'), // 物流中心理貨中
            'status-2042' => _x('2042', 'ecpay status', 'ry-woocommerce-tools-pro'), // 包裹遺失，進入賠償程序
            'status-2043' => _x('2043', 'ecpay status', 'ry-woocommerce-tools-pro'), // 門市指定時間不配送，後續配送中
            'status-2044' => _x('2044', 'ecpay status', 'ry-woocommerce-tools-pro'), // 賣家已取退回包裹
            'status-2045' => _x('2045', 'ecpay status', 'ry-woocommerce-tools-pro'), // 不正常到貨(商品提早到物流中心)，廠退處理
            'status-2046' => _x('2046', 'ecpay status', 'ry-woocommerce-tools-pro'), // 貨件未取退回大智通物流中心
            'status-2047' => _x('2047', 'ecpay status', 'ry-woocommerce-tools-pro'), // 正常二退(退貨時間延長，在判賠期限內退回)
            'status-2048' => _x('2048', 'ecpay status', 'ry-woocommerce-tools-pro'), // 包裝異常，請洽客服
            'status-2049' => _x('2049', 'ecpay status', 'ry-woocommerce-tools-pro'), // 門市關店，將進行退貨處理
            'status-2050' => _x('2050', 'ecpay status', 'ry-woocommerce-tools-pro'), // 門市轉店，將進行退貨處理
            'status-2051' => _x('2051', 'ecpay status', 'ry-woocommerce-tools-pro'), // 賣家要求提早退貨
            'status-2052' => _x('2052', 'ecpay status', 'ry-woocommerce-tools-pro'), // 違禁品(退貨及罰款處理)
            'status-2053' => _x('2053', 'ecpay status', 'ry-woocommerce-tools-pro'), // 門市刷A給B，請洽客服
            'status-2054' => _x('2054', 'ecpay status', 'ry-woocommerce-tools-pro'), // 賣家要求提早退貨
            'status-2055' => _x('2055', 'ecpay status', 'ry-woocommerce-tools-pro'), // 包裹退至物流中心
            'status-2057' => _x('2057', 'ecpay status', 'ry-woocommerce-tools-pro'), // 車輛故障，後續配送中
            'status-2058' => _x('2058', 'ecpay status', 'ry-woocommerce-tools-pro'), // 天候不佳，後續配送中
            'status-2059' => _x('2059', 'ecpay status', 'ry-woocommerce-tools-pro'), // 道路中斷，後續配送中
            'status-2060' => _x('2060', 'ecpay status', 'ry-woocommerce-tools-pro'), // 門市停業，廠退處理
            'status-2061' => _x('2061', 'ecpay status', 'ry-woocommerce-tools-pro'), // 包裹異常，請洽客服
            'status-2062' => _x('2062', 'ecpay status', 'ry-woocommerce-tools-pro'), // 包裹異常，請洽客服
            'status-2063' => _x('2063', 'ecpay status', 'ry-woocommerce-tools-pro'), // 包裹配達取件門市
            'status-2065' => _x('2065', 'ecpay status', 'ry-woocommerce-tools-pro'), // 買家未取包裹，將退回物流中心
            'status-2066' => _x('2066', 'ecpay status', 'ry-woocommerce-tools-pro'), // 包裹異常，請洽客服
            'status-2067' => _x('2067', 'ecpay status', 'ry-woocommerce-tools-pro'), // 買家已到店取貨
            'status-2068' => _x('2068', 'ecpay status', 'ry-woocommerce-tools-pro'), // 賣家已到門市寄件
            'status-2069' => _x('2069', 'ecpay status', 'ry-woocommerce-tools-pro'), // 退貨便收件(商品退回指定C門市)
            'status-2070' => _x('2070', 'ecpay status', 'ry-woocommerce-tools-pro'), // 賣家已取退回包裹
            'status-2071' => _x('2071', 'ecpay status', 'ry-woocommerce-tools-pro'), // 門市代碼格式錯誤
            'status-2072' => _x('2072', 'ecpay status', 'ry-woocommerce-tools-pro'), // 包裹已退至原寄件門市
            'status-2073' => _x('2073', 'ecpay status', 'ry-woocommerce-tools-pro'), // 包裹配達取件門市
            'status-2074' => _x('2074', 'ecpay status', 'ry-woocommerce-tools-pro'), // 買家未取包裹，將退回物流中心
            'status-2075' => _x('2075', 'ecpay status', 'ry-woocommerce-tools-pro'), // 賣家未取包裹，將退回物流中心
            'status-2076' => _x('2076', 'ecpay status', 'ry-woocommerce-tools-pro'), // 買家未取包裹，已退回物流中心
            'status-2077' => _x('2077', 'ecpay status', 'ry-woocommerce-tools-pro'), // 賣家未取包裹，待申請退回
            'status-2078' => _x('2078', 'ecpay status', 'ry-woocommerce-tools-pro'), // 買家未取包裹，已退回物流中心
            'status-2079' => _x('2079', 'ecpay status', 'ry-woocommerce-tools-pro'), // 買家未取貨退回物流中心-商品瑕疵(進物流中心)
            'status-2080' => _x('2080', 'ecpay status', 'ry-woocommerce-tools-pro'), // 買家未取貨退回物流中心-超材
            'status-2081' => _x('2081', 'ecpay status', 'ry-woocommerce-tools-pro'), // 買家未取貨退回物流中心-違禁品(退貨及罰款處理)
            'status-2082' => _x('2082', 'ecpay status', 'ry-woocommerce-tools-pro'), // 買家未取貨退回物流中心-訂單資料重複上傳
            'status-2083' => _x('2083', 'ecpay status', 'ry-woocommerce-tools-pro'), // 買家未取貨退回物流中心-已過門市進貨日(未於指定時間內寄至物流中心)
            'status-2084' => _x('2084', 'ecpay status', 'ry-woocommerce-tools-pro'), // 買家未取貨退回物流中心-第一段標籤規格錯誤
            'status-2085' => _x('2085', 'ecpay status', 'ry-woocommerce-tools-pro'), // 買家未取貨退回物流中心-第一段標籤無法判讀
            'status-2086' => _x('2086', 'ecpay status', 'ry-woocommerce-tools-pro'), // 買家未取貨退回物流中心-第一段標籤資料錯誤
            'status-2087' => _x('2087', 'ecpay status', 'ry-woocommerce-tools-pro'), // 買家未取貨退回物流中心-物流中心理貨中
            'status-2088' => _x('2088', 'ecpay status', 'ry-woocommerce-tools-pro'), // 買家未取貨退回物流中心-商品遺失
            'status-2089' => _x('2089', 'ecpay status', 'ry-woocommerce-tools-pro'), // 買家未取退回物流中心-門市指定不配送(六、日)
            'status-2092' => _x('2092', 'ecpay status', 'ry-woocommerce-tools-pro'), // 買家未取退回物流中心-門市關轉
            'status-2093' => _x('2093', 'ecpay status', 'ry-woocommerce-tools-pro'), // 買家未取退回物流中心-爆量
            'status-2094' => _x('2094', 'ecpay status', 'ry-woocommerce-tools-pro'), // 包裹異常，請洽客服
            'status-2095' => _x('2095', 'ecpay status', 'ry-woocommerce-tools-pro'), // 天候路況不佳
            'status-2096' => _x('2096', 'ecpay status', 'ry-woocommerce-tools-pro'), // 賣家未取包裹，待申請退回
            'status-2097' => _x('2097', 'ecpay status', 'ry-woocommerce-tools-pro'), // 賣家未取包裹，宅配退回中
            'status-2101' => _x('2101', 'ecpay status', 'ry-woocommerce-tools-pro'), // 門市關轉店
            'status-2102' => _x('2102', 'ecpay status', 'ry-woocommerce-tools-pro'), // 門市舊店號更新
            'status-2103' => _x('2103', 'ecpay status', 'ry-woocommerce-tools-pro'), // 無取件門市資料
            'status-2104' => _x('2104', 'ecpay status', 'ry-woocommerce-tools-pro'), // 門市關轉，請重選門市
            'status-2105' => _x('2105', 'ecpay status', 'ry-woocommerce-tools-pro'), // 已申請門市變更
            'status-3001' => _x('3001', 'ecpay status', 'ry-woocommerce-tools-pro'), // 轉運中(即集貨)
            'status-3002' => _x('3002', 'ecpay status', 'ry-woocommerce-tools-pro'), // 不在家
            'status-3003' => _x('3003', 'ecpay status', 'ry-woocommerce-tools-pro'), // 配完
            'status-3004' => _x('3004', 'ecpay status', 'ry-woocommerce-tools-pro'), // 送錯BASE(送錯營業所)
            'status-3005' => _x('3005', 'ecpay status', 'ry-woocommerce-tools-pro'), // 送錯CENTER(送錯轉運中心)
            'status-3006' => _x('3006', 'ecpay status', 'ry-woocommerce-tools-pro'), // 配送中
            'status-3007' => _x('3007', 'ecpay status', 'ry-woocommerce-tools-pro'), // 公司行號休息
            'status-3008' => _x('3008', 'ecpay status', 'ry-woocommerce-tools-pro'), // 地址錯誤，聯繫收件人
            'status-3009' => _x('3009', 'ecpay status', 'ry-woocommerce-tools-pro'), // 搬家
            'status-3010' => _x('3010', 'ecpay status', 'ry-woocommerce-tools-pro'), // 轉寄(如原本寄到A，改寄B)
            'status-3011' => _x('3011', 'ecpay status', 'ry-woocommerce-tools-pro'), // 暫置營業所(收件人要求至營業所取貨)
            'status-3012' => _x('3012', 'ecpay status', 'ry-woocommerce-tools-pro'), // 到所(收件人要求到站所取件)
            'status-3013' => _x('3013', 'ecpay status', 'ry-woocommerce-tools-pro'), // 當配下車(當日配送A至B營業所，已抵達B營業所)
            'status-3014' => _x('3014', 'ecpay status', 'ry-woocommerce-tools-pro'), // 當配上車(當日配送從A至B營業所，已抵達A營業所)
            'status-3015' => _x('3015', 'ecpay status', 'ry-woocommerce-tools-pro'), // 空運配送中
            'status-3016' => _x('3016', 'ecpay status', 'ry-woocommerce-tools-pro'), // 配完狀態刪除
            'status-3017' => _x('3017', 'ecpay status', 'ry-woocommerce-tools-pro'), // 退回狀態刪除(代收退貨刪除)
            'status-3018' => _x('3018', 'ecpay status', 'ry-woocommerce-tools-pro'), // 包裹配達取件門市
            'status-3019' => _x('3019', 'ecpay status', 'ry-woocommerce-tools-pro'), // 包裹已退至原寄件門市
            'status-3020' => _x('3020', 'ecpay status', 'ry-woocommerce-tools-pro'), // 買家未取包裹，將退回物流中心
            'status-3021' => _x('3021', 'ecpay status', 'ry-woocommerce-tools-pro'), // 賣家未取包裹，待申請退回
            'status-3022' => _x('3022', 'ecpay status', 'ry-woocommerce-tools-pro'), // 買家已到店取貨
            'status-3023' => _x('3023', 'ecpay status', 'ry-woocommerce-tools-pro'), // 賣家已取退回包裹
            'status-3024' => _x('3024', 'ecpay status', 'ry-woocommerce-tools-pro'), // 物流中心驗收成功
            'status-3025' => _x('3025', 'ecpay status', 'ry-woocommerce-tools-pro'), // 買家未取包裹，已退回物流中心
            'status-3029' => _x('3029', 'ecpay status', 'ry-woocommerce-tools-pro'), // 包裹已配達指定取件門市
            'status-3031' => _x('3031', 'ecpay status', 'ry-woocommerce-tools-pro'), // 包裹已退至指定寄件門市
            'status-3032' => _x('3032', 'ecpay status', 'ry-woocommerce-tools-pro'), // 賣家已到門市寄件
            'status-3033' => _x('3033', 'ecpay status', 'ry-woocommerce-tools-pro'), // EC客戶要求提早退貨
            'status-3117' => _x('3117', 'ecpay status', 'ry-woocommerce-tools-pro'), // 拒收(調查處理中)
            'status-3118' => _x('3118', 'ecpay status', 'ry-woocommerce-tools-pro'), // 暫置營業所
            'status-3119' => _x('3119', 'ecpay status', 'ry-woocommerce-tools-pro'), // 暫置營業所(假日暫置)
            'status-3120' => _x('3120', 'ecpay status', 'ry-woocommerce-tools-pro'), // 預備配送中
            'status-3121' => _x('3121', 'ecpay status', 'ry-woocommerce-tools-pro'), // 轉交門市配達
            'status-3122' => _x('3122', 'ecpay status', 'ry-woocommerce-tools-pro'), // 另約時間
            'status-3301' => _x('3301', 'ecpay status', 'ry-woocommerce-tools-pro'), // 交寄郵件
            'status-3302' => _x('3302', 'ecpay status', 'ry-woocommerce-tools-pro'), // 各區郵局招領中
            'status-3303' => _x('3303', 'ecpay status', 'ry-woocommerce-tools-pro'), // 投遞不成功
            'status-3304' => _x('3304', 'ecpay status', 'ry-woocommerce-tools-pro'), // 依寄件人指示銷毀
            'status-3305' => _x('3305', 'ecpay status', 'ry-woocommerce-tools-pro'), // 拒收
            'status-3306' => _x('3306', 'ecpay status', 'ry-woocommerce-tools-pro'), // 退回投遞不成功
            'status-3307' => _x('3307', 'ecpay status', 'ry-woocommerce-tools-pro'), // i郵箱取件成功
            'status-3308' => _x('3308', 'ecpay status', 'ry-woocommerce-tools-pro'), // 投遞成功
            'status-3309' => _x('3309', 'ecpay status', 'ry-woocommerce-tools-pro'), // 投遞成功(收受人領取)
            'status-3310' => _x('3310', 'ecpay status', 'ry-woocommerce-tools-pro'), // 已退回寄件人
            'status-3311' => _x('3311', 'ecpay status', 'ry-woocommerce-tools-pro'), // 退回郵件投遞中
            'status-3312' => _x('3312', 'ecpay status', 'ry-woocommerce-tools-pro'), // 貨件投遞中
            'status-3313' => _x('3313', 'ecpay status', 'ry-woocommerce-tools-pro'), // 郵局貨件運轉中
            'status-3314' => _x('3314', 'ecpay status', 'ry-woocommerce-tools-pro'), // 到達i郵箱
            'status-3315' => _x('3315', 'ecpay status', 'ry-woocommerce-tools-pro'), // i郵箱逾期退招領
            'status-4001' => _x('4001', 'ecpay status', 'ry-woocommerce-tools-pro'), // 買家已到門市寄件
            'status-4002' => _x('4002', 'ecpay status', 'ry-woocommerce-tools-pro'), // 退貨商品已至物流中心
            'status-4003' => _x('4003', 'ecpay status', 'ry-woocommerce-tools-pro'), // 退件包裹異常，協尋中
            'status-4004' => _x('4004', 'ecpay status', 'ry-woocommerce-tools-pro'), // 包裹遺失，進入賠償程序
            'status-5001' => _x('5001', 'ecpay status', 'ry-woocommerce-tools-pro'), // 損壞，站所將協助退貨
            'status-5002' => _x('5002', 'ecpay status', 'ry-woocommerce-tools-pro'), // 遺失
            'status-5003' => _x('5003', 'ecpay status', 'ry-woocommerce-tools-pro'), // BASE列管(寄件人和收件人聯絡不到)
            'status-5004' => _x('5004', 'ecpay status', 'ry-woocommerce-tools-pro'), // 賣家未取包裹，待申請退回
            'status-5005' => _x('5005', 'ecpay status', 'ry-woocommerce-tools-pro'), // 代收退貨
            'status-5006' => _x('5006', 'ecpay status', 'ry-woocommerce-tools-pro'), // 代收毀損
            'status-5007' => _x('5007', 'ecpay status', 'ry-woocommerce-tools-pro'), // 代收遺失
            'status-5008' => _x('5008', 'ecpay status', 'ry-woocommerce-tools-pro'), // 退貨配完
            'status-5009' => _x('5009', 'ecpay status', 'ry-woocommerce-tools-pro'), // 包裹異常，請洽客服
            'status-7001' => _x('7001', 'ecpay status', 'ry-woocommerce-tools-pro'), // 超大(通常發生於司機取件，不取件)
            'status-7002' => _x('7002', 'ecpay status', 'ry-woocommerce-tools-pro'), // 超重(通常發生於司機取件，不取件)
            'status-7003' => _x('7003', 'ecpay status', 'ry-woocommerce-tools-pro'), // 地址錯誤，聯繫收件人
            'status-7004' => _x('7004', 'ecpay status', 'ry-woocommerce-tools-pro'), // 航班延誤
            'status-7005' => _x('7005', 'ecpay status', 'ry-woocommerce-tools-pro'), // 託運單刪除
            'status-7006' => _x('7006', 'ecpay status', 'ry-woocommerce-tools-pro'), // 包裹遺失，進入賠償程序
            'status-7007' => _x('7007', 'ecpay status', 'ry-woocommerce-tools-pro'), // 包裹遺失，進入賠償程序
            'status-7008' => _x('7008', 'ecpay status', 'ry-woocommerce-tools-pro'), // 包裹破損，請洽客服
            'status-7009' => _x('7009', 'ecpay status', 'ry-woocommerce-tools-pro'), // 包裝異常，請洽客服
            'status-7010' => _x('7010', 'ecpay status', 'ry-woocommerce-tools-pro'), // 包裝異常，請洽客服
            'status-7011' => _x('7011', 'ecpay status', 'ry-woocommerce-tools-pro'), // 取件門市關轉，請重選門市
            'status-7012' => _x('7012', 'ecpay status', 'ry-woocommerce-tools-pro'), // 條碼錯誤，廠退處理
            'status-7013' => _x('7013', 'ecpay status', 'ry-woocommerce-tools-pro'), // 訂單超過驗收期限(賣家未出貨)
            'status-7014' => _x('7014', 'ecpay status', 'ry-woocommerce-tools-pro'), // 等待賣家出貨
            'status-7015' => _x('7015', 'ecpay status', 'ry-woocommerce-tools-pro'), // 條碼重複，請洽客服
            'status-7016' => _x('7016', 'ecpay status', 'ry-woocommerce-tools-pro'), // 包裹超材，退回賣家
            'status-7017' => _x('7017', 'ecpay status', 'ry-woocommerce-tools-pro'), // 取件包裹異常，協尋中
            'status-7018' => _x('7018', 'ecpay status', 'ry-woocommerce-tools-pro'), // 包裹遺失，進入賠償程序
            'status-7019' => _x('7019', 'ecpay status', 'ry-woocommerce-tools-pro'), // 寄件包裹異常，協尋中
            'status-7020' => _x('7020', 'ecpay status', 'ry-woocommerce-tools-pro'), // 包裹遺失，進入賠償程序
            'status-7021' => _x('7021', 'ecpay status', 'ry-woocommerce-tools-pro'), // 包裹異常，請洽客服
            'status-7022' => _x('7022', 'ecpay status', 'ry-woocommerce-tools-pro'), // 包裹異常，請洽客服
            'status-7023' => _x('7023', 'ecpay status', 'ry-woocommerce-tools-pro'), // 包裹異常，請洽客服
            'status-7024' => _x('7024', 'ecpay status', 'ry-woocommerce-tools-pro'), // 另約時間
            'status-7025' => _x('7025', 'ecpay status', 'ry-woocommerce-tools-pro'), // 電聯不上
            'status-7026' => _x('7026', 'ecpay status', 'ry-woocommerce-tools-pro'), // 資料有誤
            'status-7027' => _x('7027', 'ecpay status', 'ry-woocommerce-tools-pro'), // 無件可退
            'status-7028' => _x('7028', 'ecpay status', 'ry-woocommerce-tools-pro'), // 超大超重
            'status-7029' => _x('7029', 'ecpay status', 'ry-woocommerce-tools-pro'), // 已回收
            'status-7030' => _x('7030', 'ecpay status', 'ry-woocommerce-tools-pro'), // 別家收走
            'status-7031' => _x('7031', 'ecpay status', 'ry-woocommerce-tools-pro'), // 商品未到
            'status-7032' => _x('7032', 'ecpay status', 'ry-woocommerce-tools-pro'), // 寄件門市關轉，請重選門市
            'status-7033' => _x('7033', 'ecpay status', 'ry-woocommerce-tools-pro'), // 資料異常
            'status-7034' => _x('7034', 'ecpay status', 'ry-woocommerce-tools-pro'), // 貨物進店發生異常，請洽客服
            'status-7035' => _x('7035', 'ecpay status', 'ry-woocommerce-tools-pro'), // 逾期未領，貨件銷毀
            'status-7036' => _x('7036', 'ecpay status', 'ry-woocommerce-tools-pro'), // 貨件破損，請洽客服
            'status-7037' => _x('7037', 'ecpay status', 'ry-woocommerce-tools-pro'), // 訂單上傳失敗
            'status-7038' => _x('7038', 'ecpay status', 'ry-woocommerce-tools-pro'), // 門市驗收異常，請洽客服
            'status-7101' => _x('7101', 'ecpay status', 'ry-woocommerce-tools-pro'), // 取件門市關轉，請重選門市
            'status-7102' => _x('7102', 'ecpay status', 'ry-woocommerce-tools-pro'), // 包裹配送驗收異常-無進貨資料
            'status-7103' => _x('7103', 'ecpay status', 'ry-woocommerce-tools-pro'), // 包裹配送驗收異常-條碼錯誤
            'status-7104' => _x('7104', 'ecpay status', 'ry-woocommerce-tools-pro'), // 包裹配送驗收異常-超材
            'status-7105' => _x('7105', 'ecpay status', 'ry-woocommerce-tools-pro'), // 包裹配送驗收異常-大物流包裝不良(滲漏)
            'status-7106' => _x('7106', 'ecpay status', 'ry-woocommerce-tools-pro'), // 包裹配送驗收異常-小物流破損
            'status-7107' => _x('7107', 'ecpay status', 'ry-woocommerce-tools-pro'), // 包裹配送驗收異常-門市反應商品包裝不良(滲漏)
            'status-7201' => _x('7201', 'ecpay status', 'ry-woocommerce-tools-pro'), // 寄件門市關轉，請重選門市
            'status-7202' => _x('7202', 'ecpay status', 'ry-woocommerce-tools-pro'), // 退貨包裹配送驗收異常-無進貨資料
            'status-7203' => _x('7203', 'ecpay status', 'ry-woocommerce-tools-pro'), // 退貨包裹配送驗收異常-條碼錯誤
            'status-7204' => _x('7204', 'ecpay status', 'ry-woocommerce-tools-pro'), // 退貨包裹配送驗收異常-超材
            'status-7205' => _x('7205', 'ecpay status', 'ry-woocommerce-tools-pro'), // 退貨包裹配送驗收異常-大物流包裝不良(滲漏)
            'status-7206' => _x('7206', 'ecpay status', 'ry-woocommerce-tools-pro'), // 退貨包裹配送驗收異常-小物流破損
            'status-7207' => _x('7207', 'ecpay status', 'ry-woocommerce-tools-pro'), // 退貨包裹配送驗收異常-門市反應商品包裝不良(滲漏)
            'status-7255' => _x('7255', 'ecpay status', 'ry-woocommerce-tools-pro'), // 包裹退至物流中心
            'status-7300' => _x('7300', 'ecpay status', 'ry-woocommerce-tools-pro'), // 郵局接收資料異常
            'status-7301' => _x('7301', 'ecpay status', 'ry-woocommerce-tools-pro'), // 郵件破損-無法投遞
            'status-7302' => _x('7302', 'ecpay status', 'ry-woocommerce-tools-pro'), // 郵件遺失
            'status-9001' => _x('9001', 'ecpay status', 'ry-woocommerce-tools-pro'), // 退貨已取
            'status-9002' => _x('9002', 'ecpay status', 'ry-woocommerce-tools-pro'), // 退貨已取
            'status-9999' => _x('9999', 'ecpay status', 'ry-woocommerce-tools-pro'), // 訂單取消
        ];
    }

    return $status_name[$status] ?? $status;
}

function rywtp_newebpay_type_to_name($type)
{
    static $type_name = [];
    if (empty($type_name)) {
        $type_name = [
            'CREDIT' => _x('CREDIT', 'newebpay type', 'ry-woocommerce-tools-pro'), // 信用卡
            'VACC' => _x('VACC', 'newebpay type', 'ry-woocommerce-tools-pro'), // 銀行 ATM 轉帳
            'WEBATM' => _x('WEBATM', 'newebpay type', 'ry-woocommerce-tools-pro'), // 網路銀行轉帳
            'BARCODE' => _x('BARCODE', 'newebpay type', 'ry-woocommerce-tools-pro'), // 超商條碼
            'CVS' => _x('CVS', 'newebpay type', 'ry-woocommerce-tools-pro'), // 超商代碼繳
            'LINEPAY' => _x('LINEPAY', 'newebpay type', 'ry-woocommerce-tools-pro'), // LINE Pay
            'ESUNWALLET' => _x('ESUNWALLET', 'newebpay type', 'ry-woocommerce-tools-pro'), // 玉山 Wallet
            'TAIWANPAY' => _x('TAIWANPAY', 'newebpay type', 'ry-woocommerce-tools-pro'), // 台灣 Pay
            'CVSCOM' => _x('CVSCOM', 'newebpay type', 'ry-woocommerce-tools-pro'), // 超商取貨付款
            'FULA' => _x('FULA', 'newebpay type', 'ry-woocommerce-tools-pro'), // Fula 付啦
        ];
    }

    return $type_name[$type] ?? $type;
}

function rywtp_newebpay_method_to_name($method)
{
    static $method_name = [];
    if (empty($method_name)) {
        $method_name = [
            'CREDIT' => _x('CREDIT', 'newebpay method', 'ry-woocommerce-tools-pro'), // 台灣發卡機構核發之信用卡
            'FOREIGN' => _x('FOREIGN', 'newebpay method', 'ry-woocommerce-tools-pro'), // 國外發卡機構核發之卡
            'NTCB' => _x('NTCB', 'newebpay method', 'ry-woocommerce-tools-pro'), // 國民旅遊卡
            'UNIONPAY' => _x('UNIONPAY', 'newebpay method', 'ry-woocommerce-tools-pro'), // 銀聯卡
            'APPLEPAY' => _x('APPLEPAY', 'newebpay method', 'ry-woocommerce-tools-pro'), // ApplePay
            'GOOGLEPAY' => _x('GOOGLEPAY', 'newebpay method', 'ry-woocommerce-tools-pro'), // GooglePay
            'SAMSUNGPAY' => _x('SAMSUNGPAY', 'newebpay method', 'ry-woocommerce-tools-pro'), // SamsungPay
        ];
    }

    return $method_name[$method] ?? $method;
}
